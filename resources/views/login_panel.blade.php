@extends('layouts.app')

@php
    $normalizedPath = trim((string) request('path', 'lc/login'), '/');
    $isLoginPage = $normalizedPath === '' || $normalizedPath === 'lc/login' || str_contains($contentHtml ?? '', 'id="login-form"');
    $remoteCsrf = '';
    $loginError = '';

    if ($isLoginPage) {
        preg_match('/name="_frontendCSRF"\s+value="([^"]+)"/', $contentHtml ?? '', $csrfMatch);
        $remoteCsrf = $csrfMatch[1] ?? '';

        preg_match_all('/<p class="help-block help-block-error">([\s\S]*?)<\/p>/', $contentHtml ?? '', $errorMatches);
        $loginErrors = collect($errorMatches[1] ?? [])
            ->map(fn ($error) => trim(strip_tags(html_entity_decode($error, ENT_QUOTES | ENT_HTML5, 'UTF-8'))))
            ->filter()
            ->values();

        $loginError = $loginErrors->first() ?? '';
    }
@endphp

@section('content')
    <div class="container vgltu-student-profile-page">
        @if ($isLoginPage)
            <section class="vgltu-student-profile-hero">
                <div>
                    <span class="vgltu-student-profile-hero__eyebrow">VGLTU Portal Access</span>
                    <h1>{{ $pageTitle ?: 'VGLTU Student Profile' }}</h1>
                </div>
            </section>
        @endif

        @if ($isLoginPage)
            <section class="vgltu-login-screen">
                <form action="{{ url('/university-student-profile/proxy/lc/login') }}" method="POST" class="vgltu-login-form" novalidate>
                    @csrf
                    @if ($remoteCsrf !== '')
                        <input type="hidden" name="_frontendCSRF" value="{{ $remoteCsrf }}">
                    @endif

                    <div class="vgltu-login-form__header">
                        <span class="vgltu-login-form__eyebrow">Login To Your Profile</span>
                        <p>Use your 5-digit Student ID and password to sign in. Password format: <strong>YYYY-MM-DD</strong>.</p>
                    </div>

                    @if ($loginError !== '')
                        <div class="vgltu-login-error">{{ $loginError }}</div>
                    @endif

                    <div class="vgltu-login-field">
                        <label for="loginform-login">Student ID</label>
                        <input
                            id="loginform-login"
                            type="text"
                            name="LoginForm[login]"
                            inputmode="numeric"
                            maxlength="5"
                            value="{{ old('LoginForm.login') }}"
                            required
                        >
                    </div>

                    <div class="vgltu-login-field">
                        <label for="loginform-password">Password</label>
                        <div class="vgltu-login-password-wrap">
                            <input
                                id="loginform-password"
                                type="password"
                                name="LoginForm[password]"
                                required
                            >
                            <button type="button" class="vgltu-login-password-toggle" aria-label="Show password" aria-pressed="false">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M1.5 12s3.8-6.5 10.5-6.5S22.5 12 22.5 12s-3.8 6.5-10.5 6.5S1.5 12 1.5 12Z"></path>
                                    <circle cx="12" cy="12" r="3.25"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="vgltu-login-submit">Login</button>
                </form>
            </section>
        @else
            <section class="vgltu-student-profile-panel">
                <div class="vgltu-student-profile-panel__content-shell">
                    <div class="vgltu-student-profile-panel__content"></div>
                </div>
            </section>
        @endif
    </div>

    <style>
        .vgltu-student-profile-page {
            padding-top: 24px;
            padding-bottom: 32px;
        }

        .vgltu-student-profile-hero {
            margin-bottom: 18px;
            padding: 24px;
            border-radius: 28px;
            border: 1px solid rgba(148, 163, 184, 0.18);
            background:
                radial-gradient(circle at top right, rgba(241, 115, 170, 0.16), transparent 32%),
                linear-gradient(135deg, #fff9f5, #f6fbff);
            box-shadow: 0 20px 44px rgba(15, 23, 42, 0.08);
            text-align: center;
        }

        .vgltu-student-profile-hero > div {
            width: 100%;
            max-width: 760px;
            margin: 0 auto;
        }

        .vgltu-student-profile-hero__eyebrow,
        .vgltu-login-form__eyebrow {
            display: inline-flex;
            align-items: center;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(187, 62, 113, 0.1);
            color: #bb3e71;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .vgltu-student-profile-hero h1 {
            margin: 12px 0 0;
            font-size: clamp(1.9rem, 2.8vw, 2.8rem);
            color: #241726;
        }

        .vgltu-login-screen {
            display: flex;
            justify-content: center;
            padding: 8px 0 20px;
        }

        .vgltu-login-form {
            width: 100%;
            max-width: 760px;
            padding: 32px 28px;
            border-radius: 24px;
            background: #ffffff;
            box-shadow: 0 22px 48px rgba(15, 23, 42, 0.12);
        }

        .vgltu-login-form__header {
            margin-bottom: 24px;
            text-align: center;
        }

        .vgltu-login-form__header p {
            margin: 12px 0 0;
            color: #5b6474;
            font-size: 1rem;
            line-height: 1.6;
        }

        .vgltu-login-error {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 14px;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 0.94rem;
        }

        .vgltu-login-field + .vgltu-login-field {
            margin-top: 18px;
        }

        .vgltu-login-field label {
            display: block;
            margin-bottom: 8px;
            color: #0f172a;
            font-weight: 700;
        }

        .vgltu-login-field input {
            width: 100%;
            min-height: 54px;
            padding: 14px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 16px;
            background: #f8fafc;
            color: #0f172a;
            font-size: 16px;
            line-height: 1.4;
        }

        .vgltu-login-field input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
            background: #ffffff;
        }

        .vgltu-login-password-wrap {
            position: relative;
            width: 100%;
        }

        .vgltu-login-password-wrap input {
            padding-right: 84px;
        }

        .vgltu-login-password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            width: 42px;
            height: 42px;
            padding: 0;
            border: 0;
            border-radius: 12px;
            background: #e2e8f0;
            color: #0f172a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .vgltu-login-password-toggle svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
        }

        .vgltu-login-submit {
            width: 100%;
            min-height: 52px;
            margin-top: 22px;
            border: 0;
            border-radius: 16px;
            background: linear-gradient(135deg, #bb3e71, #8b1e4f);
            color: #ffffff;
            font-size: 1rem;
            font-weight: 800;
            box-shadow: 0 16px 32px rgba(187, 62, 113, 0.24);
        }

        .vgltu-login-submit:hover,
        .vgltu-login-submit:focus {
            background: linear-gradient(135deg, #c94f84, #9d235b);
        }

        .vgltu-student-profile-panel__content-shell {
            overflow: visible;
            border-radius: 24px;
            border: 1px solid rgba(148, 163, 184, 0.18);
            background: #ffffff;
            box-shadow: 0 20px 44px rgba(15, 23, 42, 0.08);
        }

        .vgltu-student-profile-panel__content {
            width: 100%;
            overflow: visible;
            background: #ffffff;
        }

        @media (max-width: 767.98px) {
            .vgltu-student-profile-page {
                padding-left: 12px;
                padding-right: 12px;
            }

            .vgltu-student-profile-hero {
                padding: 18px;
                border-radius: 22px;
                margin-bottom: 14px;
            }

            .vgltu-student-profile-hero h1 {
                font-size: 1.6rem;
            }

            .vgltu-login-screen {
                padding: 10px 0;
            }

            .vgltu-login-form {
                padding: 24px 18px;
                border-radius: 20px;
            }

            .vgltu-login-form__header {
                margin-bottom: 20px;
            }

            .vgltu-login-form__header p {
                font-size: 0.95rem;
            }
        }
    </style>

        @unless($isLoginPage)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const panelHost = document.querySelector('.vgltu-student-profile-panel__content');
                const remoteHeadHtml = @json($headHtml ?? '');
                const remoteContentHtml = @json($contentHtml ?? '');

                if (!panelHost) {
                    return;
                }

                const shadowRoot = panelHost.shadowRoot || panelHost.attachShadow({ mode: 'open' });
                const baseStyle = document.createElement('style');
                baseStyle.textContent = `
                    @font-face {
                        font-family: "univer-icons";
                        src:
                            url("/university-student-profile/proxy/lc/fonts/univer-icons/fonts/univer-icons.woff") format("woff"),
                            url("/university-student-profile/proxy/lc/fonts/univer-icons/fonts/univer-icons.ttf") format("truetype"),
                            url("/university-student-profile/proxy/lc/fonts/univer-icons/fonts/univer-icons.svg#univer-icons") format("svg");
                        font-weight: normal;
                        font-style: normal;
                    }

                    :host {
                        display: block;
                        width: 100%;
                        background: #ffffff;
                        color: #111827;
                    }

                    *, *::before, *::after {
                        box-sizing: border-box;
                    }

                    img, svg, video, canvas, iframe {
                        max-width: 100%;
                        height: auto;
                    }

                    table {
                        width: 100% !important;
                    }

                    form table {
                        display: table !important;
                        overflow: visible !important;
                        border-collapse: separate !important;
                    }

                    form tbody {
                        display: table-row-group !important;
                    }

                    form tr {
                        display: table-row !important;
                    }

                    form td,
                    form th {
                        display: table-cell !important;
                        vertical-align: middle !important;
                    }

                    label,
                    .control-label {
                        position: static !important;
                        transform: none !important;
                        white-space: normal !important;
                    }

                    input,
                    select,
                    textarea {
                        line-height: normal !important;
                    }

                    [class^="icon-"],
                    [class*=" icon-"] {
                        font-size: 0 !important;
                        color: transparent !important;
                    }

                    [class^="icon-"]::before,
                    [class*=" icon-"]::before {
                        font-family: "univer-icons" !important;
                        color: transparent !important;
                    }

                    .vgltu-fallback-icon {
                        display: inline-flex !important;
                        align-items: center;
                        justify-content: center;
                        width: 24px;
                        min-width: 24px;
                        height: 24px;
                        margin-right: 10px;
                        color: #67757E;
                        vertical-align: middle;
                    }

                    .vgltu-fallback-icon svg {
                        width: 20px;
                        height: 20px;
                        stroke: currentColor;
                        fill: none;
                        stroke-width: 1.8;
                        stroke-linecap: round;
                        stroke-linejoin: round;
                    }

                    .menu-item.active .vgltu-fallback-icon,
                    .menu-item a:hover .vgltu-fallback-icon {
                        color: #4CAF50;
                    }

                    .vgltu-hide-before::before {
                        content: "" !important;
                        display: none !important;
                    }

                    .logout {
                        display: block !important;
                        padding-left: 45px !important;
                        min-height: 24px !important;
                        width: 100% !important;
                        line-height: 1 !important;
                        color: #67757E !important;
                        font-size: 14px !important;
                        position: relative !important;
                    }

                    .logout form {
                        display: block !important;
                        margin: 0 !important;
                        width: 100% !important;
                    }

                    .logout .btn-link {
                        display: inline-flex !important;
                        align-items: center !important;
                        gap: 12px !important;
                        position: relative !important;
                        padding: 0 !important;
                        border: 0 !important;
                        background: transparent !important;
                        box-shadow: none !important;
                        width: 100% !important;
                        text-align: left !important;
                        color: inherit !important;
                        font-size: inherit !important;
                        line-height: inherit !important;
                    }

                    .logout:hover,
                    .logout:hover .btn-link,
                    .logout .btn-link:hover,
                    .logout .btn-link:focus {
                        color: #67757E !important;
                    }

                    .logout .vgltu-fallback-icon {
                        position: static !important;
                        transform: none !important;
                        margin-right: 0 !important;
                        flex: 0 0 24px !important;
                    }

                    .cookie,
                    .cookies,
                    .cookie-wrap,
                    .cookie-block,
                    .cookie-banner,
                    .cookie-popup,
                    .footer,
                    .footer-wrap,
                    footer {
                        display: none !important;
                    }

                    button,
                    button:not(.tabs__btn),
                    .btn,
                    .btn-primary,
                    .btn-success,
                    .btn-default,
                    .btn-secondary,
                    .btn-info,
                    .btn-warning,
                    .btn-danger,
                    [class*="btn-"],
                    [class^="btn-"],
                    input[type="submit"],
                    input[type="button"],
                    input[type="reset"],
                    a.btn,
                    .button,
                    .custom-payment-form button,
                    .select-date__btn,
                    .user-pay-btn,
                    .payment-btn,
                    .kv-date-picker,
                    .fancybox-button {
                        background: linear-gradient(135deg, #bb3e71, #8b1e4f) !important;
                        border-color: #8b1e4f !important;
                        color: #ffffff !important;
                        box-shadow: 0 12px 24px rgba(187, 62, 113, 0.18) !important;
                    }

                    button:hover,
                    button:not(.tabs__btn):hover,
                    .btn:hover,
                    .btn-primary:hover,
                    .btn-success:hover,
                    .btn-default:hover,
                    .btn-secondary:hover,
                    .btn-info:hover,
                    .btn-warning:hover,
                    .btn-danger:hover,
                    [class*="btn-"]:hover,
                    [class^="btn-"]:hover,
                    input[type="submit"]:hover,
                    input[type="button"]:hover,
                    input[type="reset"]:hover,
                    a.btn:hover,
                    .button:hover,
                    .custom-payment-form button:hover,
                    .select-date__btn:hover,
                    .user-pay-btn:hover,
                    .payment-btn:hover,
                    .kv-date-picker:hover,
                    .fancybox-button:hover,
                    button:focus,
                    button:not(.tabs__btn):focus,
                    .btn:focus,
                    .btn-primary:focus,
                    .btn-success:focus,
                    .btn-default:focus,
                    .btn-secondary:focus,
                    .btn-info:focus,
                    .btn-warning:focus,
                    .btn-danger:focus,
                    [class*="btn-"]:focus,
                    [class^="btn-"]:focus,
                    input[type="submit"]:focus,
                    input[type="button"]:focus,
                    input[type="reset"]:focus,
                    a.btn:focus,
                    .button:focus {
                        background: linear-gradient(135deg, #c94f84, #9d235b) !important;
                        border-color: #9d235b !important;
                        color: #ffffff !important;
                    }

                    .btn-link,
                    a.btn-link {
                        color: #8b1e4f !important;
                    }

                    .tabs__btn {
                        background: transparent !important;
                        box-shadow: none !important;
                        padding: 0 !important;
                        color: #67757E !important;
                        border-bottom: 2px solid transparent !important;
                        border-top: 0 !important;
                        border-left: 0 !important;
                        border-right: 0 !important;
                    }

                    .tabs__btn:hover,
                    .tabs__btn:focus {
                        background: transparent !important;
                        box-shadow: none !important;
                        color: #67757E !important;
                        border-bottom-color: transparent !important;
                    }

                    .tabs__btn.active {
                        background: transparent !important;
                        box-shadow: none !important;
                        color: #67757E !important;
                        border-bottom-color: #bb3e71 !important;
                    }

                    @media (max-width: 992px) {
                        .site,
                        body {
                            overflow: visible !important;
                            height: auto !important;
                            min-height: 0 !important;
                        }

                        .sidebar-wrap {
                            width: 100% !important;
                            padding: 0 16px !important;
                            position: relative !important;
                            left: auto !important;
                            top: auto !important;
                        }

                        .menu-body,
                        .menu-body.opened {
                            position: relative !important;
                            top: 0 !important;
                            width: 100% !important;
                            padding: 16px 0 32px !important;
                            background: transparent !important;
                            box-shadow: none !important;
                        }

                        .menu-body {
                            left: 5000px !important;
                            display: none !important;
                        }

                        .menu-body.opened {
                            left: 0 !important;
                            display: block !important;
                        }

                        .main-wrap {
                            padding-top: 20px !important;
                            padding-left: 16px !important;
                            padding-right: 16px !important;
                            border: 0 !important;
                            min-height: 0 !important;
                        }

                        .hamburger,
                        button.hamburger {
                            display: block !important;
                            visibility: visible !important;
                            pointer-events: auto !important;
                            background: transparent !important;
                            box-shadow: none !important;
                            border: 0 !important;
                        }

                        .hamburger:hover,
                        .hamburger:focus,
                        .hamburger.is-active,
                        button.hamburger:hover,
                        button.hamburger:focus,
                        button.hamburger.is-active {
                            background: transparent !important;
                            box-shadow: none !important;
                        }

                        .hamburger-inner,
                        .hamburger-inner::before,
                        .hamburger-inner::after {
                            background-color: #67757E !important;
                        }

                        .logout {
                            margin-top: 0 !important;
                            margin-bottom: 0 !important;
                        }
                    }
                `;

                const fragment = document.createDocumentFragment();
                fragment.appendChild(baseStyle);

                const appendHtmlWithScripts = (html) => {
                    if (!html) {
                        return;
                    }

                    const template = document.createElement('template');
                    template.innerHTML = html;

                    const scripts = template.content.querySelectorAll('script');
                    scripts.forEach((script) => {
                        const replacement = document.createElement('script');

                        Array.from(script.attributes).forEach((attribute) => {
                            replacement.setAttribute(attribute.name, attribute.value);
                        });

                        replacement.textContent = script.textContent || '';
                        script.replaceWith(replacement);
                    });

                    fragment.appendChild(template.content.cloneNode(true));
                };

                appendHtmlWithScripts(remoteHeadHtml);
                appendHtmlWithScripts(remoteContentHtml);

                shadowRoot.innerHTML = '';
                shadowRoot.appendChild(fragment);

                const forceHideBrokenPseudoIcons = () => {
                    if (shadowRoot.querySelector('#vgltu-hide-broken-icons')) {
                        return;
                    }

                    const style = document.createElement('style');
                    style.id = 'vgltu-hide-broken-icons';
                    style.textContent = `
                        .menu-item a::before,
                        .menu-item--edit a::before,
                        a[href*="edit"]::before,
                        .edit::before,
                        .logout .logout_icon::before,
                        .go-home a::before,
                        .custom-checkbox__input:checked + .custom-checkbox__btn::before {
                            content: "" !important;
                            display: none !important;
                        }

                        .logout .logout_icon,
                        .menu-item [class^="icon-"],
                        .menu-item [class*=" icon-"] {
                            display: none !important;
                        }
                    `;

                    shadowRoot.appendChild(style);
                };

                forceHideBrokenPseudoIcons();

                const fallbackIcons = {
                    'icon-grad': '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 9l10-5 10 5-10 5-10-5Z"></path><path d="M6 11.5v4.5c0 1.5 2.7 3 6 3s6-1.5 6-3v-4.5"></path></svg>',
                    'icon-study': '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6.5h7a3 3 0 0 1 3 3V20H6a3 3 0 0 0-3 3Z" transform="translate(0 -2)"></path><path d="M21 6.5h-7a3 3 0 0 0-3 3V20h7a3 3 0 0 1 3 3Z" transform="translate(0 -2)"></path></svg>',
                    'icon-books': '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h6v14H4z"></path><path d="M10 4h6v16h-6z"></path><path d="M16 7h4v13h-4z"></path></svg>',
                    'icon-bag': '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 8h12l-1 11H7L6 8Z"></path><path d="M9 8a3 3 0 0 1 6 0"></path></svg>',
                    'icon-document': '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3h7l5 5v13H7z"></path><path d="M14 3v5h5"></path><path d="M9 13h6"></path><path d="M9 17h6"></path></svg>',
                    'icon-book': '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4h11a3 3 0 0 1 3 3v13H8a3 3 0 0 0-3 3Z"></path><path d="M8 4v16"></path></svg>',
                    'icon-edit': '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20h4l10-10-4-4L4 16v4Z"></path><path d="M13 7l4 4"></path></svg>',
                    'icon-calendar': '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3v4"></path><path d="M17 3v4"></path><path d="M4 8h16"></path><rect x="4" y="5" width="16" height="15" rx="2"></rect></svg>',
                    'icon-logout': '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 7l5 5-5 5"></path><path d="M19 12H9"></path><path d="M11 5H5v14h6"></path></svg>'
                };

                const sidebarTextFallbacks = [
                    { match: 'ÐŸÑ€Ð¾Ñ„Ð¸Ð»ÑŒ ÑÑ‚ÑƒÐ´ÐµÐ½Ñ‚Ð°', icon: fallbackIcons['icon-grad'] },
                    { match: 'ÐžÑÐ²Ð¾ÐµÐ½Ð¸Ðµ Ð¾Ð±Ñ€Ð°Ð·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÑŒÐ½Ð¾Ð¹ Ð¿Ñ€Ð¾Ð³Ñ€Ð°Ð¼Ð¼Ñ‹', icon: fallbackIcons['icon-study'] },
                    { match: 'Ð­Ð»ÐµÐºÑ‚Ñ€Ð¾Ð½Ð½Ð¾-Ð±Ð¸Ð±Ð»Ð¸Ð¾Ñ‚ÐµÑ‡Ð½Ð°Ñ ÑÐ¸ÑÑ‚ÐµÐ¼Ð°', icon: fallbackIcons['icon-books'] },
                    { match: 'Ð¤Ð¾Ñ€Ð¼Ð¸Ñ€Ð¾Ð²Ð°Ð½Ð¸Ðµ Ð¿Ð¾Ñ€Ñ‚Ñ„Ð¾Ð»Ð¸Ð¾', icon: fallbackIcons['icon-bag'] },
                    { match: 'ÐŸÑ€Ð¸ÐºÐ°Ð·Ñ‹', icon: fallbackIcons['icon-document'] },
                    { match: 'ÐžÐ±Ñ€Ð°Ð·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÑŒÐ½Ð°Ñ Ð¿Ñ€Ð¾Ð³Ñ€Ð°Ð¼Ð¼Ð°', icon: fallbackIcons['icon-book'] },
                    { match: 'Ð’Ð¾Ð¿Ñ€Ð¾Ñ Ð¿Ñ€ÐµÐ¿Ð¾Ð´Ð°Ð²Ð°Ñ‚ÐµÐ»ÑŽ', icon: fallbackIcons['icon-document'] },
                    { match: 'Ð ÐµÐ´Ð°ÐºÑ‚Ð¸Ñ€Ð¾Ð²Ð°Ñ‚ÑŒ', icon: fallbackIcons['icon-edit'] },
                    { match: 'Ð Ð°ÑÐ¿Ð¸ÑÐ°Ð½Ð¸Ðµ', icon: fallbackIcons['icon-calendar'] },
                    { match: 'Ð’Ñ‹Ñ…Ð¾Ð´', icon: fallbackIcons['icon-logout'] }
                ];

                const createFallbackIcon = (svg) => {
                    const holder = document.createElement('span');
                    holder.className = 'vgltu-fallback-icon';
                    holder.innerHTML = svg;
                    return holder;
                };

                const removeBrokenMenuIcons = () => {
                    shadowRoot.querySelectorAll('*').forEach((node) => {
                        try {
                            const beforeContent = window.getComputedStyle(node, '::before').content;
                            const normalized = (beforeContent || '').replace(/["']/g, '').trim();
                            if (normalized.length > 0 && normalized.length <= 2 && /^[a-z]$/i.test(normalized)) {
                                node.classList.add('vgltu-hide-before');
                            }
                        } catch (error) {
                        }
                    });

                    const selectors = [
                        '[class^="icon-"]',
                        '[class*=" icon-"]',
                        '.logout_icon',
                        '.menu-item--edit a',
                        'a[href*="edit"]',
                        '.edit',
                        '.menu-item a > i',
                        '.menu-item a > span:first-child',
                        '.menu-item a > div:first-child'
                    ];

                    shadowRoot.querySelectorAll(selectors.join(', ')).forEach((node) => {
                        if (node.classList.contains('vgltu-fallback-icon')) {
                            return;
                        }

                        const text = (node.textContent || '').replace(/\s+/g, ' ').trim();
                        const looksLikeBrokenIcon = text.length <= 2 || node.matches('[class^="icon-"], [class*=" icon-"], .logout_icon');

                        if (!looksLikeBrokenIcon) {
                            return;
                        }

                        node.textContent = '';
                        node.style.display = 'none';
                        node.style.visibility = 'hidden';
                        node.style.width = '0';
                        node.style.minWidth = '0';
                        node.style.margin = '0';
                        node.style.padding = '0';
                    });

                    shadowRoot.querySelectorAll('.menu-item a, .logout').forEach((item) => {
                        Array.from(item.childNodes).forEach((node) => {
                            if (node.nodeType !== Node.TEXT_NODE) {
                                return;
                            }

                            const text = (node.textContent || '').trim();
                            if (text.length > 0 && text.length <= 2 && /^[a-z]$/i.test(text)) {
                                node.textContent = '';
                            }
                        });
                    });

                    shadowRoot.querySelectorAll('a, button, [role="button"], label, span').forEach((item) => {
                        const text = (item.textContent || '').replace(/\s+/g, ' ').trim();
                        if (text.length < 2) {
                            return;
                        }

                        const firstChild = item.firstChild;
                        if (firstChild && firstChild.nodeType === Node.TEXT_NODE) {
                            const raw = firstChild.textContent || '';
                            const trimmed = raw.trim();
                            if (trimmed.length > 0 && trimmed.length <= 2 && /^[a-z]$/i.test(trimmed)) {
                                firstChild.textContent = raw.replace(trimmed, '');
                            }
                        }

                        const children = Array.from(item.children);
                        if (children.length > 0) {
                            const firstElement = children[0];
                            if (!firstElement.classList.contains('vgltu-fallback-icon')) {
                                const value = (firstElement.textContent || '').replace(/\s+/g, ' ').trim();
                                if (value.length > 0 && value.length <= 2 && /^[a-z]$/i.test(value) && !firstElement.querySelector('svg, img, i')) {
                                    firstElement.textContent = '';
                                    firstElement.style.display = 'none';
                                    firstElement.style.visibility = 'hidden';
                                }
                            }
                        }
                    });

                    shadowRoot.querySelectorAll('a[href*="edit"], .menu-item--edit a, .edit, button').forEach((item) => {
                        const text = (item.textContent || '').replace(/\s+/g, ' ').trim();
                        if (!text.includes('Редактировать')) {
                            return;
                        }

                        Array.from(item.childNodes).forEach((node) => {
                            if (node.nodeType === Node.TEXT_NODE) {
                                const value = (node.textContent || '').trim();
                                if (value.length > 0 && value.length <= 2 && /^[a-z]$/i.test(value)) {
                                    node.textContent = '';
                                }
                            }
                        });

                        Array.from(item.children).forEach((child) => {
                            if (child.classList.contains('vgltu-fallback-icon')) {
                                return;
                            }

                            const value = (child.textContent || '').trim();
                            if (value.length > 0 && value.length <= 2 && /^[a-z]$/i.test(value) && !child.querySelector('svg, img, i')) {
                                child.textContent = '';
                                child.style.display = 'none';
                                child.style.visibility = 'hidden';
                            }
                        });
                    });
                };

                let isApplyingFallbackIcons = false;
                let fallbackIconsScheduled = false;

                const applyFallbackIcons = () => {
                    if (isApplyingFallbackIcons) {
                        return;
                    }

                    isApplyingFallbackIcons = true;

                    removeBrokenMenuIcons();

                    const classBasedFallbacks = [
                        { selector: '.menu-item--profile a', icon: fallbackIcons['icon-grad'] },
                        { selector: '.menu-item--mastering a', icon: fallbackIcons['icon-study'] },
                        { selector: '.menu-item--library a', icon: fallbackIcons['icon-books'] },
                        { selector: '.menu-item--portfolio a', icon: fallbackIcons['icon-bag'] },
                        { selector: '.menu-item--orders a', icon: fallbackIcons['icon-document'] },
                        { selector: '.menu-item--programm a', icon: fallbackIcons['icon-book'] },
                        { selector: '.menu-item--question a', icon: fallbackIcons['icon-document'] },
                        { selector: '.menu-item--timetable a', icon: fallbackIcons['icon-calendar'] },
                        { selector: '.menu-item--edit a, [href*="edit"]', icon: fallbackIcons['icon-edit'] },
                        { selector: 'a, button', icon: fallbackIcons['icon-edit'], textMatch: 'Редактировать' },
                        { selector: '.logout .btn-link, .logout button, .logout a', icon: fallbackIcons['icon-logout'] }
                    ];

                    classBasedFallbacks.forEach(({ selector, icon, textMatch }) => {
                        shadowRoot.querySelectorAll(selector).forEach((target) => {
                            if (textMatch) {
                                const text = (target.textContent || '').replace(/\s+/g, ' ').trim();
                                if (!text.includes(textMatch)) {
                                    return;
                                }
                            }

                            if (target.querySelector(':scope > .vgltu-fallback-icon')) {
                                return;
                            }

                            target.insertBefore(createFallbackIcon(icon), target.firstChild);
                        });
                    });

                    shadowRoot.querySelectorAll('.logout > .vgltu-fallback-icon').forEach((node) => {
                        node.remove();
                    });

                    Object.entries(fallbackIcons).forEach(([className, svg]) => {
                        shadowRoot.querySelectorAll(`.${className}`).forEach((icon) => {
                            if (icon.querySelector('.vgltu-fallback-icon')) {
                                return;
                            }

                            icon.textContent = '';
                            icon.style.fontSize = '0';
                            icon.style.color = 'transparent';
                            icon.appendChild(createFallbackIcon(svg));
                        });
                    });

                    sidebarTextFallbacks.forEach(({ match, icon }) => {
                        const targetLink = Array.from(shadowRoot.querySelectorAll('a, button, [role="button"]')).find((node) => {
                            const text = (node.textContent || '').replace(/\s+/g, ' ').trim();
                            return text.includes(match);
                        });

                        if (!targetLink || targetLink.querySelector('.vgltu-fallback-icon')) {
                            return;
                        }

                        const candidates = Array.from(targetLink.children);
                        const brokenIcon = candidates.find((child) => {
                            const text = (child.textContent || '').trim();
                            return text.length === 1 && /^[a-z]$/i.test(text);
                        });

                        const firstChildElement = candidates[0] || null;

                        if (brokenIcon) {
                            brokenIcon.textContent = '';
                            brokenIcon.style.display = 'none';
                            brokenIcon.style.visibility = 'hidden';
                            targetLink.insertBefore(createFallbackIcon(icon), targetLink.firstChild);
                            return;
                        }

                        if (firstChildElement && !firstChildElement.querySelector('svg, img') && !firstChildElement.classList.contains('vgltu-fallback-icon')) {
                            firstChildElement.style.display = 'none';
                            firstChildElement.style.visibility = 'hidden';
                            targetLink.insertBefore(createFallbackIcon(icon), targetLink.firstChild);
                            return;
                        }

                        targetLink.insertBefore(createFallbackIcon(icon), targetLink.firstChild);
                    });

                    shadowRoot.querySelectorAll('[class^="icon-"], [class*=" icon-"]').forEach((node) => {
                        node.style.display = 'none';
                        node.style.visibility = 'hidden';
                        node.textContent = '';
                    });

                    shadowRoot.querySelectorAll('a, button, [role="button"]').forEach((item) => {
                        Array.from(item.childNodes).forEach((node) => {
                            if (node.nodeType === Node.TEXT_NODE) {
                                const text = (node.textContent || '').trim();
                                if (text.length > 0 && text.length <= 2 && /^[a-z]$/i.test(text)) {
                                    node.textContent = '';
                                }
                            }
                        });

                        Array.from(item.children).forEach((child) => {
                            if (child.classList.contains('vgltu-fallback-icon')) {
                                return;
                            }

                            const text = (child.textContent || '').trim();
                            if (text.length > 0 && text.length <= 2 && /^[a-z]$/i.test(text) && !child.querySelector('svg, img, i')) {
                                child.textContent = '';
                                child.style.display = 'none';
                            }
                        });
                    });

                    const walker = document.createTreeWalker(shadowRoot, NodeFilter.SHOW_TEXT);
                    const textNodesToClear = [];
                    while (walker.nextNode()) {
                        const node = walker.currentNode;
                        const text = (node.textContent || '').trim();
                        const parent = node.parentElement;

                        if (!parent) {
                            continue;
                        }

                        const parentText = (parent.textContent || '').replace(/\s+/g, ' ').trim();
                        const looksLikeBrokenIconLetter = text.length === 1
                            && /^[a-z]$/i.test(text)
                            && parentText.length <= 2
                            && !parent.closest('.vgltu-fallback-icon');

                        if (looksLikeBrokenIconLetter) {
                            textNodesToClear.push(node);
                        }
                    }

                    textNodesToClear.forEach((node) => {
                        node.textContent = '';
                        if (node.parentElement) {
                            node.parentElement.style.fontSize = '0';
                            node.parentElement.style.color = 'transparent';
                        }
                    });

                    isApplyingFallbackIcons = false;
                };

                applyFallbackIcons();

                const scheduleFallbackIcons = () => {
                    if (fallbackIconsScheduled || isApplyingFallbackIcons) {
                        return;
                    }

                    fallbackIconsScheduled = true;
                    requestAnimationFrame(() => {
                        fallbackIconsScheduled = false;
                        applyFallbackIcons();
                    });
                };

                const observer = new MutationObserver(() => {
                    scheduleFallbackIcons();
                });

                observer.observe(shadowRoot, {
                    childList: true,
                    subtree: true,
                    characterData: true
                });

                const wireMobileMenu = () => {
                    const hamburger = shadowRoot.querySelector('.hamburger, button.hamburger');
                    const menuBody = shadowRoot.querySelector('.menu-body');

                    if (!hamburger || !menuBody || hamburger.dataset.vgltuMenuBound === 'true') {
                        return;
                    }

                    hamburger.dataset.vgltuMenuBound = 'true';
                    hamburger.addEventListener('click', function (event) {
                        event.preventDefault();
                        hamburger.classList.toggle('is-active');
                        menuBody.classList.toggle('opened');
                    });
                };

                wireMobileMenu();
                setTimeout(wireMobileMenu, 300);

                const wireShadowTabs = () => {
                    const tabButtons = Array.from(shadowRoot.querySelectorAll('.js-toggle-tab'));
                    const tabItems = Array.from(shadowRoot.querySelectorAll('.tabs__item'));

                    if (tabButtons.length === 0 || tabItems.length === 0) {
                        return;
                    }

                    const activateTab = (button) => {
                        const tabId = button.getAttribute('data-tabid');
                        if (!tabId) {
                            return;
                        }

                        tabButtons.forEach((btn) => btn.classList.remove('active'));
                        tabItems.forEach((item) => {
                            item.style.display = 'none';
                        });

                        button.classList.add('active');
                        const target = shadowRoot.querySelector(tabId);
                        if (target) {
                            target.style.display = 'block';
                        }
                    };

                    tabButtons.forEach((button, index) => {
                        if (button.dataset.vgltuTabBound === 'true') {
                            return;
                        }

                        button.dataset.vgltuTabBound = 'true';
                        button.addEventListener('click', function (event) {
                            event.preventDefault();
                            activateTab(button);
                        });

                        if (button.classList.contains('active') || index === 0) {
                            activateTab(button);
                        }
                    });
                };

                wireShadowTabs();
                setTimeout(wireShadowTabs, 300);

                setTimeout(scheduleFallbackIcons, 300);
                setTimeout(scheduleFallbackIcons, 1200);
            });
        </script>
    @endunless

    @if($isLoginPage)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const passwordInput = document.getElementById('loginform-password');
                const toggleButton = document.querySelector('.vgltu-login-password-toggle');

                if (!passwordInput || !toggleButton) {
                    return;
                }

                toggleButton.addEventListener('click', function () {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    toggleButton.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
                    toggleButton.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
                    toggleButton.style.background = isPassword ? '#f8d7e5' : '#e2e8f0';
                });
            });
        </script>
    @endif
@endsection
