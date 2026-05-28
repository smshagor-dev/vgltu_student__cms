import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        window.vgltuPushRegistration = navigator.serviceWorker.register('/sw.js')
            .then(function (registration) {
                window.vgltuPushRegistrationError = null;
                return registration;
            })
            .catch(function (error) {
                window.vgltuPushRegistrationError = error;
                console.error('Service worker registration failed.', error);
                throw error;
            });
    });
}

const INSTALL_BANNER_DISMISS_KEY = 'vgltu-install-banner-dismissed-at';
const INSTALL_BANNER_HIDE_DAYS = 3;

const isStandaloneMode = function () {
    return window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
};

const wasBannerDismissedRecently = function () {
    const rawValue = window.localStorage.getItem(INSTALL_BANNER_DISMISS_KEY);

    if (!rawValue) {
        return false;
    }

    const dismissedAt = Number(rawValue);
    const expiresAt = dismissedAt + (INSTALL_BANNER_HIDE_DAYS * 24 * 60 * 60 * 1000);

    return Number.isFinite(dismissedAt) && Date.now() < expiresAt;
};

const markBannerDismissed = function () {
    window.localStorage.setItem(INSTALL_BANNER_DISMISS_KEY, String(Date.now()));
};

const createInstallBanner = function (options) {
    if (document.getElementById('vgltuInstallBanner')) {
        return;
    }

    const banner = document.createElement('div');
    banner.id = 'vgltuInstallBanner';
    banner.innerHTML = `
        <div class="vgltu-install-banner__content">
            <div class="vgltu-install-banner__icon-wrap">
                <img src="/pwa-icon-192.png" alt="VGLTU app icon" class="vgltu-install-banner__icon">
            </div>
            <div class="vgltu-install-banner__text">
                <strong>Install VGLTU</strong>
                <p>${options.message}</p>
            </div>
            <div class="vgltu-install-banner__actions">
                ${options.actionLabel ? `<button type="button" class="vgltu-install-banner__primary" id="vgltuInstallAction">${options.actionLabel}</button>` : ''}
                <button type="button" class="vgltu-install-banner__secondary" id="vgltuInstallDismiss">Later</button>
            </div>
        </div>
    `;

    const style = document.createElement('style');
    style.textContent = `
        #vgltuInstallBanner {
            position: fixed;
            left: 16px;
            right: 16px;
            bottom: 16px;
            z-index: 9999;
            animation: vgltuInstallSlideUp 0.28s ease;
        }
        .vgltu-install-banner__content {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            gap: 14px;
            align-items: center;
            padding: 16px 18px;
            border-radius: 22px;
            background: linear-gradient(135deg, #241726, #bb3e71);
            color: #fff;
            box-shadow: 0 18px 40px rgba(36, 23, 38, 0.32);
        }
        .vgltu-install-banner__icon-wrap {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }
        .vgltu-install-banner__icon {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border-radius: 12px;
        }
        .vgltu-install-banner__text strong {
            display: block;
            font-size: 1rem;
            margin-bottom: 4px;
        }
        .vgltu-install-banner__text p {
            margin: 0;
            color: rgba(255, 255, 255, 0.82);
            font-size: 0.92rem;
            line-height: 1.45;
        }
        .vgltu-install-banner__actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .vgltu-install-banner__primary,
        .vgltu-install-banner__secondary {
            min-height: 42px;
            padding: 10px 16px;
            border-radius: 999px;
            border: 0;
            font-weight: 700;
        }
        .vgltu-install-banner__primary {
            background: #fff;
            color: #241726;
        }
        .vgltu-install-banner__secondary {
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
        }
        @media (max-width: 767.98px) {
            .vgltu-install-banner__content {
                grid-template-columns: 1fr;
                text-align: left;
            }
            .vgltu-install-banner__actions {
                justify-content: stretch;
            }
            .vgltu-install-banner__primary,
            .vgltu-install-banner__secondary {
                width: 100%;
            }
        }
        @keyframes vgltuInstallSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;

    document.head.appendChild(style);
    document.body.appendChild(banner);

    const dismissButton = document.getElementById('vgltuInstallDismiss');
    const actionButton = document.getElementById('vgltuInstallAction');

    const removeBanner = function () {
        banner.remove();
        style.remove();
    };

    dismissButton.addEventListener('click', function () {
        markBannerDismissed();
        removeBanner();
    });

    if (actionButton && typeof options.onAction === 'function') {
        actionButton.addEventListener('click', async function () {
            await options.onAction();
            removeBanner();
        });
    }
};

window.addEventListener('load', function () {
    if (isStandaloneMode() || wasBannerDismissedRecently()) {
        return;
    }

    let deferredInstallPrompt = null;

    window.addEventListener('beforeinstallprompt', function (event) {
        event.preventDefault();
        deferredInstallPrompt = event;

        createInstallBanner({
            message: 'Get faster access from your home screen and open the portal like a real app.',
            actionLabel: 'Install',
            onAction: async function () {
                if (!deferredInstallPrompt) {
                    return;
                }

                deferredInstallPrompt.prompt();
                await deferredInstallPrompt.userChoice.catch(function () {
                    return null;
                });
                deferredInstallPrompt = null;
                markBannerDismissed();
            },
        });
    });

    const isIos = /iphone|ipad|ipod/i.test(window.navigator.userAgent);
    const isSafari = /^((?!chrome|android).)*safari/i.test(window.navigator.userAgent);

    if (isIos && isSafari) {
        window.setTimeout(function () {
            if (!document.getElementById('vgltuInstallBanner')) {
                createInstallBanner({
                    message: 'On iPhone or iPad, tap Share and then choose "Add to Home Screen" to install VGLTU.',
                    actionLabel: '',
                });
            }
        }, 1500);
    }
});
