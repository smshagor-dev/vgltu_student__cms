@extends('layouts.app')

@section('content')
    <div class="container">
        <section class="portal-page">
            <div class="portal-page__hero">
                <div>
                    <h1>University Profile</h1>
                    <p>
                        The VGLTU portal is loaded directly from
                        <strong>https://vgltu.ru/lc/profile</strong>.
                    </p>
                </div>
                <a href="https://vgltu.ru/lc/profile" target="_blank" rel="noopener noreferrer" class="portal-page__link">
                    Open In New Tab
                </a>
            </div>

            <div class="portal-page__frame-shell">
                <div class="portal-page__loading" id="portalLoading">
                    <div class="portal-page__spinner"></div>
                    <div>
                        <strong>Loading VGLTU portal</strong>
                        <p>Please wait while the profile page opens.</p>
                    </div>
                </div>

                <iframe
                    id="portalFrame"
                    class="portal-page__frame"
                    src="https://vgltu.ru/lc/profile"
                    title="VGLTU University Profile"
                    loading="eager"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen
                ></iframe>
            </div>

            <div class="portal-page__fallback" id="portalFallback" hidden>
                <h2>The portal could not be loaded here</h2>
                <p>You can still open the VGLTU profile portal in a new browser tab.</p>
                <a href="https://vgltu.ru/lc/profile" target="_blank" rel="noopener noreferrer" class="portal-page__link portal-page__link--primary">
                    Open VGLTU Portal
                </a>
            </div>
        </section>
    </div>

    <style>
        .portal-page {
            display: grid;
            gap: 18px;
            padding: 16px 0 28px;
        }

        .portal-page__hero {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding: 22px;
            border-radius: 24px;
            background: linear-gradient(135deg, #fffaf4, #f5fbff);
            border: 1px solid rgba(148, 163, 184, 0.18);
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
        }

        .portal-page__hero h1 {
            margin: 0 0 8px;
            font-size: clamp(1.7rem, 2.4vw, 2.3rem);
        }

        .portal-page__hero p {
            margin: 0;
            max-width: 760px;
            color: #5b6474;
            line-height: 1.6;
        }

        .portal-page__link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 11px 18px;
            border-radius: 999px;
            background: #ffffff;
            color: #241726;
            border: 1px solid rgba(36, 23, 38, 0.12);
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .portal-page__link--primary {
            background: #241726;
            color: #ffffff;
            border-color: transparent;
        }

        .portal-page__frame-shell {
            position: relative;
            min-height: 420px;
            border-radius: 24px;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid rgba(148, 163, 184, 0.18);
            box-shadow: 0 20px 44px rgba(15, 23, 42, 0.1);
        }

        .portal-page__frame {
            width: 100%;
            height: 420px;
            border: 0;
            display: block;
            background: #ffffff;
            transition: height 0.25s ease;
        }

        .portal-page__loading {
            position: absolute;
            inset: 0;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            padding: 24px;
            background:
                radial-gradient(circle at top, rgba(255, 250, 244, 0.97), rgba(255, 255, 255, 0.98)),
                linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(245, 251, 255, 0.98));
            color: #241726;
            transition: opacity 0.28s ease, visibility 0.28s ease;
        }

        .portal-page__loading.is-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .portal-page__loading strong {
            display: block;
            margin-bottom: 4px;
        }

        .portal-page__loading p {
            margin: 0;
            color: #5b6474;
        }

        .portal-page__spinner {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            border: 4px solid rgba(36, 23, 38, 0.12);
            border-top-color: #bb3e71;
            animation: portalSpin 0.8s linear infinite;
            flex-shrink: 0;
        }

        .portal-page__fallback {
            padding: 24px;
            border-radius: 24px;
            background: #f8fafc;
            border: 1px solid rgba(148, 163, 184, 0.24);
        }

        .portal-page__fallback h2 {
            margin: 0 0 8px;
        }

        .portal-page__fallback p {
            margin: 0 0 14px;
            color: #5b6474;
        }

        @keyframes portalSpin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 767.98px) {
            .portal-page__hero {
                padding: 18px;
            }

            .portal-page__link {
                width: 100%;
            }

            .portal-page__frame-shell {
                min-height: 420px;
            }

            .portal-page__frame {
                height: 420px;
            }

            .portal-page__loading {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const frame = document.getElementById('portalFrame');
            const loading = document.getElementById('portalLoading');
            const fallback = document.getElementById('portalFallback');
            const hideLoading = function () {
                loading.classList.add('is-hidden');
            };

            frame.addEventListener('load', function () {
                window.setTimeout(hideLoading, 500);
            });

            frame.addEventListener('error', function () {
                hideLoading();
                fallback.hidden = false;
            });

            window.setTimeout(function () {
                hideLoading();
            }, 12000);
        });
    </script>
@endsection
