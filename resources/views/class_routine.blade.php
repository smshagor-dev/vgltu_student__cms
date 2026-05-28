@extends('layouts.app')

@section('content')
    <div class="container">
        <section class="routine-page">
            <div class="routine-page__hero">
                <h1>Class Routine</h1>
            </div>

            @if ($contentHtml)
                <div class="routine-page__content">
                    {!! $contentHtml !!}
                </div>
            @else
                <div class="routine-page__fallback">
                    <h2>Routine view is temporarily unavailable</h2>
                    <p>The integrated schedule could not be loaded right now. You can still use the original VGLTU page directly.</p>
                    <a href="{{ $fallbackUrl }}" target="_blank" rel="noopener noreferrer" class="routine-page__link">
                        Open Original Schedule
                    </a>
                </div>
            @endif
        </section>
    </div>

    <style>
        .routine-page {
            display: grid;
            gap: 20px;
            padding: 16px 0 28px;
        }

        .routine-page__hero,
        .routine-page__content,
        .routine-page__fallback {
            border-radius: 24px;
            border: 1px solid rgba(148, 163, 184, 0.18);
            background: #ffffff;
            box-shadow: 0 20px 44px rgba(15, 23, 42, 0.08);
        }

        .routine-page__hero {
            padding: 24px;
            background: linear-gradient(135deg, #fffaf4, #f5fbff);
            text-align: center;
        }

        .routine-page__hero h1 {
            margin: 0;
            font-size: clamp(1.8rem, 2.6vw, 2.5rem);
        }

        .routine-page__fallback p {
            margin: 0;
            color: #5b6474;
            line-height: 1.65;
        }

        .routine-page__link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 11px 18px;
            border-radius: 999px;
            background: #241726;
            color: #ffffff;
            border: 1px solid transparent;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .routine-page__link--ghost {
            background: #ffffff;
            color: #241726;
            border-color: rgba(36, 23, 38, 0.14);
        }

        .routine-page__content {
            padding: 24px;
            overflow: hidden;
            min-height: calc(100vh - 220px);
        }

        .routine-page__fallback {
            padding: 24px;
        }

        .routine-page__fallback h2 {
            margin: 0 0 8px;
        }

        .routine-embed {
            color: #241726;
        }

        .routine-embed .breadcrumbs {
            display: flex;
            flex-wrap: wrap;
            gap: 6px 10px;
            padding: 0;
            margin: 0 0 14px;
            list-style: none;
            color: #6b7280;
            font-size: 0.95rem;
        }

        .routine-embed .breadcrumbs a {
            color: #6b7280;
            text-decoration: none;
        }

        .routine-embed h1 {
            margin: 0 0 20px;
            font-size: clamp(1.55rem, 2.3vw, 2.15rem);
        }

        .routine-embed .tabs {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }

        .routine-embed .tab {
            padding: 15px 16px;
            border-radius: 18px;
            border: 1px solid rgba(36, 23, 38, 0.12);
            background: #fff7fb;
            cursor: pointer;
            font-weight: 700;
            line-height: 1.4;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .routine-embed .tab:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
        }

        .routine-embed .tab.active {
            background: linear-gradient(135deg, #241726, #bb3e71);
            border-color: transparent;
            color: #ffffff;
        }

        .routine-embed .tab-content {
            display: none;
            padding: 22px;
            border-radius: 22px;
            background: #fcfcfd;
            border: 1px solid rgba(148, 163, 184, 0.16);
        }

        .routine-embed .tab-content.is-active {
            display: block;
        }

        .routine-embed .content {
            width: 100%;
        }

        .routine-embed .button,
        .routine-embed button {
            appearance: none;
            border: 0;
            border-radius: 16px;
            background: linear-gradient(135deg, #241726, #bb3e71);
            color: #ffffff;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 14px 26px rgba(187, 62, 113, 0.24);
        }

        .routine-embed input,
        .routine-embed select,
        .routine-embed textarea {
            width: 100%;
            border-radius: 14px;
            border: 1px solid rgba(148, 163, 184, 0.35);
            background: #ffffff;
            color: #241726;
            padding: 12px 14px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .routine-embed input:focus,
        .routine-embed select:focus,
        .routine-embed textarea:focus {
            border-color: rgba(187, 62, 113, 0.72);
            box-shadow: 0 0 0 4px rgba(187, 62, 113, 0.12);
        }

        .routine-embed h2,
        .routine-embed h4 {
            color: #241726;
        }

        .routine-embed img {
            max-width: 100%;
        }

        .routine-embed #raspTableGroup,
        .routine-embed #raspTableTeacher,
        .routine-embed #raspTableAud {
            width: 100%;
            overflow-x: auto;
            overflow-y: visible;
        }

        .routine-embed table {
            width: 100%;
            border-collapse: collapse;
            min-width: 640px;
            background: #ffffff;
        }

        .routine-embed th,
        .routine-embed td {
            padding: 10px 12px;
            border: 1px solid rgba(148, 163, 184, 0.2);
            vertical-align: top;
        }

        .routine-embed a[href*="/lc"],
        .routine-embed a[href*="login"],
        .routine-embed a[href*="auth"],
        .routine-embed a[href*="account"],
        .routine-embed a[href*="portfolio"],
        .routine-embed form[action*="/lc"],
        .routine-embed form[action*="login"],
        .routine-embed input[type="password"] {
            display: none !important;
        }

        @media (max-width: 991.98px) {
            .routine-embed .tabs {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767.98px) {
            .routine-page__hero,
            .routine-page__content,
            .routine-page__fallback {
                padding: 18px;
            }

            .routine-page__content {
                min-height: calc(100vh - 180px);
            }

            .routine-embed .tab-content {
                padding: 16px;
            }

            .routine-embed .form-item-field {
                flex-direction: column !important;
                gap: 12px;
                align-items: stretch !important;
            }

            .routine-embed .form-item-field > div,
            .routine-embed .form-item-field > button {
                width: 100% !important;
                margin-left: 0 !important;
            }

            .routine-embed table {
                min-width: 560px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const officialLoginUrl = @json($officialLoginUrl);
            window.setMenuHeight = window.setMenuHeight || function () {};

            const root = document.querySelector('.routine-embed');
            if (!root) {
                return;
            }

            const authPatterns = ['/lc', 'login', 'auth', 'account', 'cabinet', 'portfolio', 'personal', 'user', 'password'];

            const isBlockedSignal = function (value) {
                const text = String(value || '').toLowerCase();
                return authPatterns.some(function (pattern) {
                    return text.includes(pattern);
                });
            };

            const removeAuthNodes = function (scope) {
                scope.querySelectorAll('a, form, input[type="password"]').forEach(function (node) {
                    const signal = [
                        node.getAttribute && node.getAttribute('href'),
                        node.getAttribute && node.getAttribute('action'),
                        node.className,
                        node.id,
                        node.textContent
                    ].join(' ');

                    if (node.matches('input[type="password"]') || isBlockedSignal(signal)) {
                        node.remove();
                    }
                });
            };

            const openOfficialLogin = function () {
                window.open(officialLoginUrl, '_blank', 'noopener,noreferrer');
            };

            const tabs = Array.from(root.querySelectorAll('.tab'));
            const panels = Array.from(root.querySelectorAll('.tab-content'));

            const showTab = function (tabId) {
                tabs.forEach(function (tab) {
                    tab.classList.toggle('active', tab.id === tabId);
                });

                panels.forEach(function (panel) {
                    panel.classList.toggle('is-active', panel.id === tabId + '-content');
                });
            };

            tabs.forEach(function (tab, index) {
                tab.addEventListener('click', function () {
                    showTab(tab.id);
                });

                if (index === 0) {
                    showTab(tab.id);
                }
            });

            removeAuthNodes(root);

            const observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    mutation.addedNodes.forEach(function (node) {
                        if (node.nodeType === 1) {
                            removeAuthNodes(node);
                        }
                    });
                });
            });

            observer.observe(root, {
                childList: true,
                subtree: true
            });

            root.addEventListener('click', function (event) {
                const link = event.target.closest && event.target.closest('a[href], button, [role="button"]');
                if (!link) {
                    return;
                }

                const signal = [
                    link.getAttribute && link.getAttribute('href'),
                    link.getAttribute && link.getAttribute('data-href'),
                    link.className,
                    link.id,
                    link.textContent
                ].join(' ');

                if (!isBlockedSignal(signal)) {
                    return;
                }

                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();
                openOfficialLogin();
            }, true);

            root.addEventListener('submit', function (event) {
                const form = event.target;
                if (!form || form.tagName !== 'FORM') {
                    return;
                }

                const signal = [
                    form.getAttribute('action'),
                    form.className,
                    form.id,
                    form.textContent
                ].join(' ');

                if (!isBlockedSignal(signal) && !form.querySelector('input[type="password"]')) {
                    return;
                }

                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();
                openOfficialLogin();
            }, true);
        });
    </script>
@endsection
