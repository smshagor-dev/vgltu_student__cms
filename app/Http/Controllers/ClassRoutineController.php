<?php

namespace App\Http\Controllers;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ClassRoutineController extends Controller
{
    private const REMOTE_URL = 'https://vgltu.ru/obuchayushchimsya/raspisanie-zanyatij/';
    private const REMOTE_ORIGIN = 'https://vgltu.ru';
    private const KIS_ORIGIN = 'https://kis.vgltu.ru';
    private const PROXY_PATH_PREFIX = '/class_routine/proxy';
    private const OFFICIAL_LOGIN_URL = 'https://vgltu.ru/lc/login';

    public function show()
    {
        $payload = $this->fetchRoutineContent();

        return view('class_routine', [
            'title' => $payload['title'],
            'contentHtml' => $payload['contentHtml'],
            'fallbackUrl' => self::REMOTE_URL,
            'officialLoginUrl' => self::OFFICIAL_LOGIN_URL,
        ]);
    }

    public function proxy(Request $request, ?string $path = null)
    {
        $remoteUrl = $this->buildRemoteUrl($path, $request);

        Log::info('Class routine proxied URL requested.', [
            'method' => $request->method(),
            'remote_url' => $remoteUrl,
        ]);

        if ($this->shouldBlockProxyRequest($request, $remoteUrl)) {
            Log::warning('Blocked class routine proxy URL.', [
                'method' => $request->method(),
                'remote_url' => $remoteUrl,
                'payload_keys' => array_keys($request->all()),
            ]);

            return response('Forbidden', 403);
        }

        $upstream = $this->sendProxyRequest($request, $remoteUrl);

        if (! $upstream->successful()) {
            Log::warning('Class routine upstream request failed.', [
                'method' => $request->method(),
                'remote_url' => $remoteUrl,
                'status' => $upstream->status(),
            ]);

            return response('Upstream request failed.', 502);
        }

        return $this->passthroughResponse($upstream);
    }

    private function fetchRoutineContent(): array
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Accept-Language' => 'en-US,en;q=0.9',
                    'User-Agent' => request()->userAgent() ?: 'Mozilla/5.0',
                ])
                ->get(self::REMOTE_URL);
        } catch (\Throwable $exception) {
            Log::error('Failed to fetch VGLTU class routine page.', [
                'message' => $exception->getMessage(),
            ]);

            return [
                'title' => 'Class Routine',
                'contentHtml' => null,
            ];
        }

        if (! $response->successful()) {
            Log::error('Unexpected class routine upstream status.', [
                'status' => $response->status(),
            ]);

            return [
                'title' => 'Class Routine',
                'contentHtml' => null,
            ];
        }

        $document = new DOMDocument();
        libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="utf-8" ?>' . $response->body(), LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();

        $xpath = new DOMXPath($document);

        return [
            'title' => $this->extractHeading($xpath) ?: 'Class Routine',
            'contentHtml' => $this->extractRoutineSection($document, $xpath),
        ];
    }

    private function buildRemoteUrl(?string $path, Request $request): string
    {
        $path = trim((string) $path, '/');

        if ($path === '') {
            $baseUrl = self::REMOTE_URL;
        } elseif (Str::startsWith($path, 'kis/')) {
            $baseUrl = self::KIS_ORIGIN . '/' . Str::after($path, 'kis/');
        } else {
            $baseUrl = self::REMOTE_ORIGIN . '/' . $path;
        }

        $query = $request->getQueryString();

        return $query ? $baseUrl . '?' . $query : $baseUrl;
    }

    private function sendProxyRequest(Request $request, string $remoteUrl): HttpResponse
    {
        $headers = [
            'Accept' => $request->header('Accept', '*/*'),
            'Accept-Language' => $request->header('Accept-Language', 'en-US,en;q=0.9'),
            'User-Agent' => $request->userAgent() ?: 'Mozilla/5.0',
            'Referer' => self::REMOTE_URL,
            'Origin' => parse_url($remoteUrl, PHP_URL_SCHEME) . '://' . parse_url($remoteUrl, PHP_URL_HOST),
        ];

        if ($request->header('X-Requested-With')) {
            $headers['X-Requested-With'] = $request->header('X-Requested-With');
        }

        if ($request->header('Content-Type')) {
            $headers['Content-Type'] = $request->header('Content-Type');
        }

        $client = Http::timeout(30)
            ->withHeaders($headers)
            ->withOptions([
                'allow_redirects' => true,
            ]);

        if (! in_array($request->method(), ['GET', 'HEAD'], true)) {
            $client = $client->withBody(
                $request->getContent(),
                $request->header('Content-Type', 'application/octet-stream')
            );
        }

        return $client->send($request->method(), $remoteUrl);
    }

    private function passthroughResponse(HttpResponse $upstream): Response
    {
        $response = response($upstream->body(), $upstream->status());

        foreach (['Content-Type', 'Cache-Control', 'Expires', 'Last-Modified', 'ETag'] as $headerName) {
            $headerValue = $upstream->header($headerName);

            if ($headerValue) {
                $response->headers->set($headerName, $headerValue);
            }
        }

        return $response;
    }

    private function extractRoutineSection(DOMDocument $document, DOMXPath $xpath): ?string
    {
        $nodes = [];

        $breadcrumbs = $xpath->query('//ul[contains(@class, "breadcrumbs")]')->item(0);
        if ($breadcrumbs instanceof DOMElement) {
            $nodes[] = $breadcrumbs;
        }

        $heading = $xpath->query('//h1')->item(0);
        if ($heading instanceof DOMElement) {
            $nodes[] = $heading;
        }

        $tabs = $xpath->query('//div[contains(@class, "tabs")]')->item(0);
        if ($tabs instanceof DOMElement) {
            $nodes[] = $tabs;
        }

        $tabsContent = $xpath->query('//div[contains(@class, "tabs-content")]')->item(0);
        if ($tabsContent instanceof DOMElement) {
            $nodes[] = $tabsContent;
        }

        if ($nodes === []) {
            return null;
        }

        $html = '';

        foreach ($nodes as $node) {
            $clone = $document->importNode($node, true);
            $this->rewriteNodeUrls($clone, self::REMOTE_URL);
            $this->removeBlockedDescendants($clone);
            $html .= $document->saveHTML($clone);
        }

        return '<div class="routine-embed">' . $html . '</div>';
    }

    private function rewriteNodeUrls(\DOMNode $node, string $remoteUrl): void
    {
        if ($node instanceof DOMElement) {
            foreach (['href', 'src', 'action', 'poster', 'data-src'] as $attribute) {
                if ($node->hasAttribute($attribute)) {
                    $original = $node->getAttribute($attribute);
                    $absolute = $this->absoluteUrl($original, $remoteUrl);

                    if (! $absolute) {
                        continue;
                    }

                    if ($this->isBlockedSignal($absolute)) {
                        $node->removeAttribute($attribute);
                        continue;
                    }

                    $node->setAttribute($attribute, $this->proxyifyUrl($absolute));
                }
            }

            if ($node->hasAttribute('style')) {
                $node->setAttribute(
                    'style',
                    $this->rewriteCssUrls($node->getAttribute('style'), $remoteUrl)
                );
            }

            if (strtolower($node->tagName) === 'script' && ! $node->hasAttribute('src')) {
                $node->nodeValue = $this->rewriteInlineScript($node->textContent ?? '');
            }
        }

        foreach ($node->childNodes as $childNode) {
            $this->rewriteNodeUrls($childNode, $remoteUrl);
        }
    }

    private function removeBlockedDescendants(\DOMNode $node): void
    {
        if (! $node->hasChildNodes()) {
            return;
        }

        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            if ($child instanceof DOMElement) {
                $signal = mb_strtolower(trim(
                    ($child->getAttribute('href') ?: '') . ' ' .
                    ($child->getAttribute('action') ?: '') . ' ' .
                    ($child->getAttribute('class') ?: '') . ' ' .
                    ($child->getAttribute('id') ?: '') . ' ' .
                    ($child->textContent ?? '')
                ));

                $isPasswordField = strtolower($child->tagName) === 'input'
                    && strtolower($child->getAttribute('type')) === 'password';

                if ($isPasswordField || $this->isBlockedSignal($signal)) {
                    $node->removeChild($child);
                    continue;
                }
            }

            $this->removeBlockedDescendants($child);
        }
    }

    private function extractHeading(DOMXPath $xpath): ?string
    {
        $titleNode = $xpath->query('//h1')->item(0);

        return $titleNode ? trim($titleNode->textContent) : null;
    }

    private function rewriteInlineScript(string $script): string
    {
        return str_replace(
            [
                'https://kis.vgltu.ru',
                'http://kis.vgltu.ru',
                'https://vgltu.ru',
                'http://vgltu.ru',
            ],
            [
                self::PROXY_PATH_PREFIX . '/kis',
                self::PROXY_PATH_PREFIX . '/kis',
                self::PROXY_PATH_PREFIX,
                self::PROXY_PATH_PREFIX,
            ],
            $script
        );
    }

    private function rewriteCssUrls(string $css, string $remoteUrl): string
    {
        return preg_replace_callback('/url\(([^)]+)\)/i', function ($matches) use ($remoteUrl) {
            $rawUrl = trim($matches[1], " \t\n\r\0\x0B'\"");
            $absolute = $this->absoluteUrl($rawUrl, $remoteUrl);

            if (! $absolute) {
                return $matches[0];
            }

            if ($this->isBlockedSignal($absolute)) {
                return "url('')";
            }

            return "url('" . $this->proxyifyUrl($absolute) . "')";
        }, $css) ?? $css;
    }

    private function absoluteUrl(string $url, string $remoteUrl): ?string
    {
        if ($url === '') {
            return null;
        }

        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        if (Str::startsWith($url, '//')) {
            return 'https:' . $url;
        }

        if (Str::startsWith($url, '/')) {
            $origin = parse_url($remoteUrl, PHP_URL_SCHEME) . '://' . parse_url($remoteUrl, PHP_URL_HOST);

            return $origin . $url;
        }

        $origin = parse_url($remoteUrl, PHP_URL_SCHEME) . '://' . parse_url($remoteUrl, PHP_URL_HOST);
        $basePath = rtrim(dirname(parse_url($remoteUrl, PHP_URL_PATH) ?: '/'), '/');

        return $origin . ($basePath === '' ? '' : $basePath) . '/' . ltrim($url, '/');
    }

    private function shouldBlockProxyRequest(Request $request, string $remoteUrl): bool
    {
        $signal = mb_strtolower(
            $remoteUrl . ' ' .
            json_encode($request->all(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        $hasPassword = $request->has('password')
            || $request->input('LoginForm.password') !== null
            || $request->input('LoginForm[password]') !== null
            || str_contains($request->getContent(), 'password');

        if ($request->isMethod('post') && $hasPassword) {
            return true;
        }

        return $this->isBlockedSignal($signal);
    }

    private function isBlockedSignal(string $signal): bool
    {
        foreach ([
            '/lc',
            '/lc/',
            '/lc/login',
            'login',
            'auth',
            'account',
            'cabinet',
            'portfolio',
            'personal',
            'user',
            'password',
            'личный кабинет',
            'портфолио',
        ] as $keyword) {
            if (str_contains(mb_strtolower($signal), mb_strtolower($keyword))) {
                return true;
            }
        }

        return false;
    }

    private function proxyifyUrl(string $absolute): string
    {
        if (str_starts_with($absolute, self::KIS_ORIGIN)) {
            $pathWithQuery = Str::after($absolute, self::KIS_ORIGIN);

            return self::PROXY_PATH_PREFIX . '/kis' . ($pathWithQuery === '' ? '/' : $pathWithQuery);
        }

        if (str_starts_with($absolute, self::REMOTE_ORIGIN)) {
            $pathWithQuery = Str::after($absolute, self::REMOTE_ORIGIN);

            return self::PROXY_PATH_PREFIX . ($pathWithQuery === '' ? '/' : $pathWithQuery);
        }

        return $absolute;
    }
}
