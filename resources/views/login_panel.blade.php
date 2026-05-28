@extends('layouts.app')

@section('content')
    <div class="container vgltu-student-profile-page">
        <section class="vgltu-student-profile-panel">
            <div class="vgltu-student-profile-panel__hero">
                <div>
                    <span class="vgltu-student-profile-panel__eyebrow">VGLTU Portal Access</span>
                    <h1>{{ $pageTitle ?? 'VGLTU Student Profile' }}</h1>
                </div>
            </div>

            <div class="vgltu-student-profile-panel__content-shell">
                <div class="vgltu-student-profile-panel__content"></div>
            </div>
        </section>
    </div>

    <style>
        .vgltu-student-profile-page .vgltu-student-profile-panel {
            display: grid;
            gap: 20px;
            padding: 18px 0 30px;
        }

        .vgltu-student-profile-page .vgltu-student-profile-panel__hero,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content-shell {
            border-radius: 28px;
            border: 1px solid rgba(148, 163, 184, 0.18);
            background: #ffffff;
            box-shadow: 0 20px 44px rgba(15, 23, 42, 0.08);
        }

        .vgltu-student-profile-page .vgltu-student-profile-panel__hero {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            gap: 18px;
            flex-wrap: wrap;
            padding: 24px;
            background:
                radial-gradient(circle at top right, rgba(241, 115, 170, 0.16), transparent 32%),
                linear-gradient(135deg, #fff9f5, #f6fbff);
            text-align: center;
        }

        .vgltu-student-profile-page .vgltu-student-profile-panel__hero > div {
            width: 100%;
            max-width: 760px;
            margin: 0 auto;
        }

        .vgltu-student-profile-page .vgltu-student-profile-panel__eyebrow {
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

        .vgltu-student-profile-page .vgltu-student-profile-panel__hero h1 {
            margin: 12px 0 10px;
            font-size: clamp(1.9rem, 2.8vw, 2.8rem);
            color: #241726;
        }

        .vgltu-student-profile-page .vgltu-student-profile-panel__hero p {
            margin: 0 auto;
            max-width: 780px;
            color: #5b6474;
            line-height: 1.7;
        }

        .vgltu-student-profile-page .vgltu-student-profile-panel__content-shell {
            overflow: visible;
        }

        .vgltu-student-profile-page .vgltu-student-profile-panel__content {
            width: 100%;
            overflow: visible;
            background: #ffffff;
        }

        .vgltu-student-profile-page .vgltu-student-profile-panel__content img,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content svg,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content video,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content iframe {
            max-width: 100%;
            height: auto;
        }

        .vgltu-student-profile-page .vgltu-student-profile-panel__content .site,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content .main-wrap,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content .content-inner,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content .content,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content .content-block,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content .footer-wrap,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content .footer {
            max-width: 100%;
            overflow: visible !important;
            height: auto !important;
            max-height: none !important;
        }

        .vgltu-student-profile-page .vgltu-student-profile-panel__content .sidebar-wrap,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content .sidebar,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content aside,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content [class*="sidebar"],
        .vgltu-student-profile-page .vgltu-student-profile-panel__content [style*="position:fixed"],
        .vgltu-student-profile-page .vgltu-student-profile-panel__content [style*="position: sticky"],
        .vgltu-student-profile-page .vgltu-student-profile-panel__content [style*="position:sticky"] {
            position: static !important;
            top: auto !important;
            bottom: auto !important;
            left: auto !important;
            right: auto !important;
            height: auto !important;
            max-height: none !important;
            overflow: visible !important;
        }

        .vgltu-student-profile-page .vgltu-student-profile-panel__content .main-wrap {
            display: grid !important;
            grid-template-columns: minmax(260px, 320px) minmax(0, 1fr);
            column-gap: 28px;
            align-items: start !important;
        }

        .vgltu-student-profile-page .vgltu-student-profile-panel__content .sidebar-wrap,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content .sidebar,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content aside,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content [class*="sidebar"] {
            grid-column: 1;
            width: 100% !important;
            float: none !important;
            margin: 0 !important;
            align-self: start !important;
        }

        .vgltu-student-profile-page .vgltu-student-profile-panel__content .content-wrap,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content .content-inner,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content .content,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content .content-block,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content main {
            grid-column: 2;
            width: 100% !important;
            max-width: 100% !important;
            float: none !important;
            margin: 0 !important;
            align-self: start !important;
        }

        @media (max-width: 991.98px) {
            .vgltu-student-profile-page .vgltu-student-profile-panel__content .main-wrap {
                display: block !important;
            }
        }

        .vgltu-student-profile-page .vgltu-student-profile-panel__content .vgltu-portal-layout {
            display: grid;
            grid-template-columns: minmax(260px, 320px) minmax(0, 1fr);
            column-gap: 28px;
            align-items: start;
            width: 100%;
        }

        .vgltu-student-profile-page .vgltu-student-profile-panel__content .vgltu-portal-sidebar,
        .vgltu-student-profile-page .vgltu-student-profile-panel__content .vgltu-portal-main {
            min-width: 0;
            width: 100%;
        }

        .vgltu-student-profile-page .vgltu-student-profile-panel__content .vgltu-portal-main {
            padding-top: 50px;
        }

        @media (max-width: 991.98px) {
            .vgltu-student-profile-page .vgltu-student-profile-panel__content .vgltu-portal-layout {
                display: block;
            }
        }

        @media (max-width: 767.98px) {
            .vgltu-student-profile-page {
                padding-left: 12px;
                padding-right: 12px;
            }

            .vgltu-student-profile-page .vgltu-student-profile-panel__content,
            .vgltu-student-profile-page .vgltu-student-profile-panel__content-shell {
                width: 100%;
                max-width: 100%;
            }

            .vgltu-student-profile-page .vgltu-student-profile-panel {
                gap: 14px;
                padding: 12px 0 20px;
            }

            .vgltu-student-profile-page .vgltu-student-profile-panel__hero,
            .vgltu-student-profile-page .vgltu-student-profile-panel__content-shell {
                border-radius: 22px;
            }

            .vgltu-student-profile-page .vgltu-student-profile-panel__hero {
                padding: 18px;
            }

            .vgltu-student-profile-page .vgltu-student-profile-panel__hero h1 {
                font-size: 1.6rem;
            }

            .vgltu-student-profile-page .vgltu-student-profile-panel__hero p {
                font-size: 0.95rem;
                line-height: 1.6;
            }

            .vgltu-student-profile-page .vgltu-student-profile-panel__content-shell {
                border-radius: 18px;
            }
        }
    </style>

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
                :host {
                    display: block;
                    width: 100%;
                    background: #ffffff;
                    color: #111827;
                }

                *, *::before, *::after {
                    box-sizing: border-box;
                }

                img, svg, video, canvas {
                    max-width: 100%;
                    height: auto;
                }

                table {
                    width: 100% !important;
                    display: block;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }

                input, select, textarea, button {
                    max-width: 100%;
                }

                .vgltu-portal-layout {
                    display: grid;
                    grid-template-columns: minmax(0, 1fr) minmax(0, 2fr);
                    column-gap: 28px;
                    align-items: start;
                    width: 100%;
                }

                .vgltu-portal-sidebar,
                .vgltu-portal-main {
                    min-width: 0;
                    width: 100%;
                    position: relative;
                    z-index: 1;
                }

                .vgltu-portal-sidebar {
                    grid-column: 1;
                }

                .vgltu-portal-main {
                    grid-column: 2;
                    padding-top: 20px;
                }

                @media (max-width: 991.98px) {
                    .vgltu-portal-layout {
                        display: block;
                    }

                    .vgltu-portal-sidebar,
                    .vgltu-portal-main {
                        width: 100%;
                    }

                    .vgltu-portal-main {
                        padding-top: 16px;
                    }
                }

                @media (max-width: 767.98px) {
                    :host {
                        width: 100%;
                    }

                    .site,
                    .main-wrap,
                    .content-wrap,
                    .content-inner,
                    .content,
                    .content-block,
                    .sidebar-wrap,
                    .sidebar,
                    aside,
                    main {
                        display: block !important;
                        width: 100% !important;
                        max-width: 100% !important;
                        min-width: 0 !important;
                        float: none !important;
                        flex: 0 0 100% !important;
                        margin-left: 0 !important;
                        margin-right: 0 !important;
                        padding-left: 0 !important;
                        padding-right: 0 !important;
                    }

                    .vgltu-portal-layout {
                        display: block;
                        width: 100%;
                    }

                    .vgltu-portal-sidebar {
                        margin-bottom: 16px;
                    }

                    .vgltu-portal-main {
                        padding-top: 0;
                    }

                    .vgltu-portal-sidebar,
                    .vgltu-portal-main,
                    .sidebar-wrap,
                    .sidebar,
                    aside {
                        width: 100% !important;
                        max-width: 100% !important;
                        margin-bottom: 16px !important;
                    }

                    body,
                    .site {
                        overflow-x: hidden !important;
                    }

                    img,
                    svg,
                    video,
                    canvas,
                    iframe {
                        max-width: 100% !important;
                        height: auto !important;
                    }
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

            const panelRoot = shadowRoot;

            const normalizePortalLayout = () => {
                const isMobileViewport = () => window.innerWidth <= 991;

                const findSidebarCandidate = () => {
                    const directCandidates = [
                        panelRoot.querySelector('.sidebar-wrap'),
                        panelRoot.querySelector('.sidebar'),
                        panelRoot.querySelector('aside'),
                        panelRoot.querySelector('[class*="sidebar"]')
                    ].filter(Boolean);

                    if (directCandidates.length > 0) {
                        return directCandidates[0];
                    }

                    return Array.from(panelRoot.querySelectorAll('div, section, aside')).find((element) => {
                        const linkCount = element.querySelectorAll('a').length;
                        const text = (element.textContent || '').toLowerCase();

                        return linkCount >= 6 && (
                            text.includes('профиль студента') ||
                            text.includes('расписание') ||
                            text.includes('личный кабинет')
                        );
                    }) || null;
                };

                const findContentCandidate = (sidebar) => {
                    const directCandidates = [
                        panelRoot.querySelector('.content-wrap'),
                        panelRoot.querySelector('.content-inner'),
                        panelRoot.querySelector('.content-block'),
                        panelRoot.querySelector('main')
                    ].filter(Boolean).filter((element) => element !== sidebar && !element.contains(sidebar));

                    if (directCandidates.length > 0) {
                        return directCandidates[0];
                    }

                    return Array.from(panelRoot.querySelectorAll('div, section, main')).find((element) => {
                        if (element === sidebar || element.contains(sidebar)) {
                            return false;
                        }

                        const text = (element.textContent || '').toLowerCase();
                        const hasProfileText = text.includes('идентификационный номер') || text.includes('личные данные');
                        const hasImage = element.querySelector('img') !== null;

                        return hasProfileText || hasImage;
                    }) || null;
                };

                const unwrapTwoColumnLayout = () => {
                    panelRoot.querySelectorAll('.vgltu-portal-layout').forEach((layout) => {
                        const parent = layout.parentElement;

                        if (!parent) {
                            return;
                        }

                        const children = [];
                        layout.querySelectorAll(':scope > .vgltu-portal-sidebar, :scope > .vgltu-portal-main').forEach((shell) => {
                            Array.from(shell.childNodes).forEach((child) => {
                                children.push(child);
                            });
                        });

                        children.forEach((child) => {
                            parent.insertBefore(child, layout);
                        });

                        layout.remove();
                    });
                };

                const ensureTwoColumnLayout = () => {
                    if (isMobileViewport()) {
                        unwrapTwoColumnLayout();
                        return;
                    }

                    const existingLayout = panelRoot.querySelector('.vgltu-portal-layout');
                    const sidebar = findSidebarCandidate();
                    const content = findContentCandidate(sidebar);

                    if (!sidebar || !content || sidebar === content) {
                        return;
                    }

                    if (existingLayout && existingLayout.contains(sidebar) && existingLayout.contains(content)) {
                        return;
                    }

                    const parent = sidebar.parentElement;
                    if (!parent) {
                        return;
                    }

                    const layout = document.createElement('div');
                    layout.className = 'vgltu-portal-layout';

                    const sidebarShell = document.createElement('div');
                    sidebarShell.className = 'vgltu-portal-sidebar';

                    const contentShell = document.createElement('div');
                    contentShell.className = 'vgltu-portal-main';

                    parent.insertBefore(layout, sidebar);
                    layout.appendChild(sidebarShell);
                    layout.appendChild(contentShell);
                    sidebarShell.appendChild(sidebar);
                    contentShell.appendChild(content);
                };

                const selectors = [
                    '.site',
                    '.main-wrap',
                    '.content-wrap',
                    '.content-inner',
                    '.content',
                    '.content-block',
                    '.sidebar-wrap',
                    '.sidebar',
                    'aside',
                    '.footer-wrap',
                    '.footer'
                ];

                selectors.forEach((selector) => {
                    panelRoot.querySelectorAll(selector).forEach((element) => {
                        element.style.position = 'static';
                        element.style.top = 'auto';
                        element.style.right = 'auto';
                        element.style.bottom = 'auto';
                        element.style.left = 'auto';
                        element.style.height = 'auto';
                        element.style.maxHeight = 'none';
                        element.style.minHeight = '0';
                        element.style.overflow = 'visible';
                        element.style.overflowY = 'visible';
                        element.style.overflowX = 'visible';
                        element.style.transform = 'none';
                    });
                });

                panelRoot.querySelectorAll('*').forEach((element) => {
                    const computed = window.getComputedStyle(element);

                    if (computed.position === 'fixed' || computed.position === 'sticky') {
                        element.style.position = 'static';
                        element.style.top = 'auto';
                        element.style.right = 'auto';
                        element.style.bottom = 'auto';
                        element.style.left = 'auto';
                    }

                    if (computed.overflowY === 'auto' || computed.overflowY === 'scroll' || computed.overflow === 'auto' || computed.overflow === 'scroll') {
                        element.style.overflow = 'visible';
                        element.style.overflowY = 'visible';
                        element.style.overflowX = 'visible';
                        element.style.height = 'auto';
                        element.style.maxHeight = 'none';
                    }
                });

                ensureTwoColumnLayout();
            };

            normalizePortalLayout();

            const observer = new MutationObserver(() => {
                requestAnimationFrame(normalizePortalLayout);
            });

            observer.observe(panelRoot, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['class', 'style']
            });

            window.addEventListener('load', normalizePortalLayout);
            window.addEventListener('resize', () => {
                requestAnimationFrame(normalizePortalLayout);
            });
            setTimeout(normalizePortalLayout, 300);
            setTimeout(normalizePortalLayout, 1200);
        });
    </script>
@endsection
