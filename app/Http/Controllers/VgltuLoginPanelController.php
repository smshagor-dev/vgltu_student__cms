<?php

namespace App\Http\Controllers;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class VgltuLoginPanelController extends Controller
{
    private const REMOTE_ORIGIN = 'https://vgltu.ru';
    private const DEFAULT_REMOTE_PATH = '/lc/login';
    private const PROXY_PATH_PREFIX = '/university-student-profile/proxy';
    private const COOKIE_SESSION_KEY = 'vgltu_login_panel.cookies';

    public function show(Request $request)
    {
        $path = trim((string) $request->query('path', trim(self::DEFAULT_REMOTE_PATH, '/')), '/');
        $remoteUrl = $this->buildRemoteUrl($path, $request);
        $upstream = $this->sendProxyRequest($request, $remoteUrl);
        $this->storeCookies($request, $upstream);

        if ($this->isRedirect($upstream)) {
            $location = $upstream->header('Location');
            $absoluteLocation = $this->absoluteUrl((string) $location, $remoteUrl);
            $absoluteLocation = $this->normalizePortalLocation($absoluteLocation, $path);

            if ($absoluteLocation !== null) {
                $remoteUrl = $absoluteLocation;
                $upstream = $this->sendProxyRequest($request, $remoteUrl);
                $this->storeCookies($request, $upstream);
            }
        }

        if ($this->shouldFallbackToProfile($path, $upstream, $request)) {
            $remoteUrl = self::REMOTE_ORIGIN . '/lc/profile';
            $upstream = $this->sendProxyRequest($request, $remoteUrl);
            $this->storeCookies($request, $upstream);
        }

        $html = $this->rewriteHtml($upstream->body(), $remoteUrl);
        return $this->makeEmbeddedViewResponse($html, $upstream->status());
    }

    public function reset(Request $request)
    {
        $request->session()->forget(self::COOKIE_SESSION_KEY);

        return redirect()->route('university-student-profile.show');
    }

    public function proxy(Request $request, ?string $path = null)
    {
        $remoteUrl = $this->buildRemoteUrl($path, $request);
        $normalizedPath = trim((string) $path, '/');

        $upstream = $this->sendProxyRequest($request, $remoteUrl);
        $this->storeCookies($request, $upstream);

        if ($this->isRedirect($upstream)) {
            $location = $upstream->header('Location');
            $absoluteLocation = $this->absoluteUrl((string) $location, $remoteUrl);
            $absoluteLocation = $this->normalizePortalLocation($absoluteLocation, $normalizedPath);
            $redirectLocation = $absoluteLocation
                ? ($this->shouldRenderInsideLayout($request)
                    ? $this->pageifyUrl($absoluteLocation)
                    : $this->proxyifyUrl($absoluteLocation))
                : route('university-student-profile.show');

            return response('', $upstream->status(), [
                'Location' => $redirectLocation,
            ]);
        }

        if ($this->shouldFallbackToProfile($normalizedPath, $upstream, $request)) {
            $remoteUrl = self::REMOTE_ORIGIN . '/lc/profile';
            $upstream = $this->sendProxyRequest($request, $remoteUrl);
            $this->storeCookies($request, $upstream);
        }

        $contentType = strtolower((string) $upstream->header('Content-Type'));
        $body = $upstream->body();

        if (str_contains($contentType, 'text/html')) {
            $body = $this->rewriteHtml($body, $remoteUrl);

            if ($this->shouldRenderInsideLayout($request)) {
                return $this->makeEmbeddedViewResponse($body, $upstream->status());
            }
        } elseif (str_contains($contentType, 'text/css')) {
            $body = $this->rewriteCssUrls($body, $remoteUrl);
        } elseif (str_contains($contentType, 'javascript') || str_contains($contentType, 'ecmascript')) {
            $body = $this->rewriteInlineScript($body);
        }

        return $this->makeResponse($upstream, $body);
    }

    private function buildRemoteUrl(?string $path, Request $request): string
    {
        $path = trim((string) $path, '/');
        $basePath = $path === '' ? self::DEFAULT_REMOTE_PATH : '/' . $path;
        $query = $request->getQueryString();

        return self::REMOTE_ORIGIN . $basePath . ($query ? '?' . $query : '');
    }

    private function sendProxyRequest(Request $request, string $remoteUrl): HttpResponse
    {
        $headers = [
            'Accept' => $request->header('Accept', '*/*'),
            'Accept-Language' => $request->header('Accept-Language', 'en-US,en;q=0.9'),
            'User-Agent' => $request->userAgent() ?: 'Mozilla/5.0',
            'Referer' => self::REMOTE_ORIGIN . self::DEFAULT_REMOTE_PATH,
            'Origin' => self::REMOTE_ORIGIN,
        ];

        $cookieHeader = $this->buildCookieHeader($request);
        if ($cookieHeader !== null) {
            $headers['Cookie'] = $cookieHeader;
        }

        if ($request->header('X-Requested-With')) {
            $headers['X-Requested-With'] = $request->header('X-Requested-With');
        }

        if ($request->header('Content-Type')) {
            $headers['Content-Type'] = $request->header('Content-Type');
        }

        $client = Http::timeout(45)
            ->withHeaders($headers)
            ->withOptions([
                'allow_redirects' => false,
            ]);

        if (! in_array($request->method(), ['GET', 'HEAD'], true)) {
            $client = $client->withBody(
                $request->getContent(),
                $request->header('Content-Type', 'application/x-www-form-urlencoded')
            );
        }

        return $client->send($request->method(), $remoteUrl);
    }

    private function buildCookieHeader(Request $request): ?string
    {
        $cookies = $request->session()->get(self::COOKIE_SESSION_KEY, []);

        if (! is_array($cookies) || $cookies === []) {
            return null;
        }

        return collect($cookies)
            ->map(fn ($value, $name) => $name . '=' . $value)
            ->implode('; ');
    }

    private function storeCookies(Request $request, HttpResponse $upstream): void
    {
        $cookieStore = $request->session()->get(self::COOKIE_SESSION_KEY, []);
        $setCookieHeaders = method_exists($upstream, 'toPsrResponse')
            ? $upstream->toPsrResponse()->getHeader('Set-Cookie')
            : [];

        foreach ($setCookieHeaders as $header) {
            $pair = trim(Str::before($header, ';'));

            if ($pair === '' || ! str_contains($pair, '=')) {
                continue;
            }

            [$name, $value] = explode('=', $pair, 2);
            $name = trim($name);

            if ($name === '') {
                continue;
            }

            $cookieStore[$name] = $value;
        }

        $request->session()->put(self::COOKIE_SESSION_KEY, $cookieStore);
    }

    private function rewriteHtml(string $html, string $remoteUrl): string
    {
        $document = new DOMDocument();
        libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();

        $xpath = new DOMXPath($document);

        foreach ($xpath->query('//*[@href or @src or @action or @poster or @data-src]') as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            foreach (['href', 'src', 'action', 'poster', 'data-src'] as $attribute) {
                if (! $node->hasAttribute($attribute)) {
                    continue;
                }

                $absolute = $this->absoluteUrl($node->getAttribute($attribute), $remoteUrl);
                if ($absolute !== null) {
                    $node->setAttribute($attribute, $this->rewriteElementUrl($node, $attribute, $absolute));
                }
            }
        }

        foreach ($xpath->query('//*[@style]') as $node) {
            if ($node instanceof DOMElement) {
                $node->setAttribute('style', $this->rewriteCssUrls($node->getAttribute('style'), $remoteUrl));
            }
        }

        foreach ($xpath->query('//script[not(@src)]') as $scriptNode) {
            if ($scriptNode instanceof DOMElement) {
                $scriptNode->nodeValue = $this->rewriteInlineScript($scriptNode->textContent ?? '');
            }
        }

        foreach ($xpath->query('//meta[translate(@http-equiv,"ABCDEFGHIJKLMNOPQRSTUVWXYZ","abcdefghijklmnopqrstuvwxyz")="content-security-policy"]') as $metaNode) {
            if ($metaNode->parentNode) {
                $metaNode->parentNode->removeChild($metaNode);
            }
        }

        $this->injectBaseEnhancements($document);

        return $document->saveHTML();
    }

    private function injectBaseEnhancements(DOMDocument $document): void
    {
        $head = $document->getElementsByTagName('head')->item(0);
        if (! $head instanceof DOMElement) {
            return;
        }

        $style = $document->createElement('style', 'body{background:#fff;}');
        $head->appendChild($style);
    }

    private function rewriteInlineScript(string $script): string
    {
        return str_replace(
            [
                'https://vgltu.ru',
                'http://vgltu.ru',
                '"/lc',
                "'/lc",
                '"/templates/',
                "'/templates/",
                '"/img/',
                "'/img/",
            ],
            [
                self::PROXY_PATH_PREFIX,
                self::PROXY_PATH_PREFIX,
                '"' . self::PROXY_PATH_PREFIX . '/lc',
                "'" . self::PROXY_PATH_PREFIX . '/lc',
                '"' . self::PROXY_PATH_PREFIX . '/templates/',
                "'" . self::PROXY_PATH_PREFIX . '/templates/',
                '"' . self::PROXY_PATH_PREFIX . '/img/',
                "'" . self::PROXY_PATH_PREFIX . '/img/',
            ],
            $script
        );
    }

    private function rewriteCssUrls(string $css, string $remoteUrl): string
    {
        return preg_replace_callback('/url\(([^)]+)\)/i', function ($matches) use ($remoteUrl) {
            $rawUrl = trim($matches[1], " \t\n\r\0\x0B'\"");
            $absolute = $this->absoluteUrl($rawUrl, $remoteUrl);

            if ($absolute === null) {
                return $matches[0];
            }

            return "url('" . $this->proxyifyUrl($absolute) . "')";
        }, $css) ?? $css;
    }

    private function absoluteUrl(string $url, string $remoteUrl): ?string
    {
        if ($url === '' || Str::startsWith($url, ['data:', 'mailto:', 'tel:', '#', 'javascript:'])) {
            return null;
        }

        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        if (Str::startsWith($url, '//')) {
            return 'https:' . $url;
        }

        if (Str::startsWith($url, '/')) {
            return self::REMOTE_ORIGIN . $url;
        }

        $basePath = rtrim(dirname(parse_url($remoteUrl, PHP_URL_PATH) ?: '/'), '/');

        return self::REMOTE_ORIGIN . ($basePath === '' ? '' : $basePath) . '/' . ltrim($url, '/');
    }

    private function proxyifyUrl(string $absoluteUrl): string
    {
        if (! str_starts_with($absoluteUrl, self::REMOTE_ORIGIN)) {
            return $absoluteUrl;
        }

        $pathWithQuery = Str::after($absoluteUrl, self::REMOTE_ORIGIN);

        return self::PROXY_PATH_PREFIX . ($pathWithQuery === '' ? '/' : $pathWithQuery);
    }

    private function pageifyUrl(string $absoluteUrl): string
    {
        if (! str_starts_with($absoluteUrl, self::REMOTE_ORIGIN)) {
            return $absoluteUrl;
        }

        $path = trim((string) parse_url($absoluteUrl, PHP_URL_PATH), '/');
        $query = parse_url($absoluteUrl, PHP_URL_QUERY);
        $parameters = [];

        if ($path !== '') {
            $parameters['path'] = $path;
        }

        if (is_string($query) && $query !== '') {
            parse_str($query, $queryParameters);
            $parameters = array_merge($parameters, $queryParameters);
        }

        return route('university-student-profile.show', $parameters);
    }

    private function rewriteElementUrl(DOMElement $node, string $attribute, string $absoluteUrl): string
    {
        $tagName = strtolower($node->tagName);

        if ($attribute === 'href' && $tagName === 'a') {
            return $this->pageifyUrl($absoluteUrl);
        }

        if ($attribute === 'action' && $tagName === 'form') {
            return $this->proxyifyUrl($absoluteUrl);
        }

        return $this->proxyifyUrl($absoluteUrl);
    }

    private function normalizePortalLocation(?string $absoluteUrl, ?string $sourcePath = null): ?string
    {
        if ($absoluteUrl === null) {
            return null;
        }

        if ($sourcePath === 'lc/logout') {
            return self::REMOTE_ORIGIN . self::DEFAULT_REMOTE_PATH;
        }

        return preg_match('#^https://vgltu\.ru/lc/?(?:\?.*)?$#', $absoluteUrl) === 1
            ? preg_replace('#^https://vgltu\.ru/lc/?#', 'https://vgltu.ru/lc/profile', $absoluteUrl, 1)
            : $absoluteUrl;
    }

    private function shouldFallbackToProfile(string $normalizedPath, HttpResponse $upstream, Request $request): bool
    {
        if (! in_array($normalizedPath, ['lc', 'lc/'], true) && $normalizedPath !== 'lc') {
            return false;
        }

        if ($upstream->status() !== 404) {
            return false;
        }

        return $this->buildCookieHeader($request) !== null;
    }

    private function isRedirect(HttpResponse $upstream): bool
    {
        return $upstream->status() >= 300 && $upstream->status() < 400;
    }

    private function makeResponse(HttpResponse $upstream, string $body): Response
    {
        $response = response($body, $upstream->status());

        foreach (['Content-Type', 'Cache-Control', 'Expires', 'Last-Modified', 'ETag'] as $headerName) {
            $headerValue = $upstream->header($headerName);

            if ($headerValue) {
                $response->headers->set($headerName, $headerValue);
            }
        }

        $response->headers->remove('Content-Security-Policy');
        $response->headers->remove('X-Frame-Options');

        return $response;
    }

    private function makeEmbeddedViewResponse(string $html, int $status = 200): Response
    {
        [$headHtml, $contentHtml, $pageTitle] = $this->extractEmbeddedSegments($html);

        return response()->view('login_panel', [
            'headHtml' => $headHtml,
            'contentHtml' => $contentHtml,
            'pageTitle' => $pageTitle ?: 'VGLTU Student Profile',
        ], $status);
    }

    private function shouldRenderInsideLayout(Request $request): bool
    {
        if ($request->ajax() || $request->isXmlHttpRequest()) {
            return false;
        }

        $secFetchDest = strtolower((string) $request->header('Sec-Fetch-Dest', ''));
        if ($secFetchDest !== '' && $secFetchDest !== 'document' && $secFetchDest !== 'iframe') {
            return false;
        }

        $accept = strtolower((string) $request->header('Accept', ''));

        return $accept === '' || str_contains($accept, 'text/html');
    }

    private function extractEmbeddedSegments(string $html): array
    {
        $document = new DOMDocument();
        libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();

        $headHtml = '';
        $contentHtml = '';
        $pageTitle = '';

        $head = $document->getElementsByTagName('head')->item(0);
        if ($head instanceof DOMElement) {
            foreach ($head->childNodes as $childNode) {
                if (in_array($childNode->nodeName, ['script', 'style', 'link', 'meta', 'title'], true)) {
                    $headHtml .= $document->saveHTML($childNode);
                }
            }
        }

        $body = $document->getElementsByTagName('body')->item(0);
        if ($body instanceof DOMElement) {
            foreach ($body->childNodes as $childNode) {
                $contentHtml .= $document->saveHTML($childNode);
            }
        } else {
            $contentHtml = $html;
        }

        $title = $document->getElementsByTagName('title')->item(0);
        if ($title) {
            $pageTitle = trim($title->textContent ?? '');
        }

        return [$headHtml, $contentHtml, $pageTitle];
    }
}
