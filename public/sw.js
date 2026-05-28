const CACHE_NAME = 'vgltu-shell-v2';
const CORE_ASSETS = [
    '/',
    '/manifest.webmanifest',
    '/logo_en.png',
    '/pwa-icon-192.png',
    '/pwa-icon-512.png',
];

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function (cache) {
            return cache.addAll(CORE_ASSETS);
        })
    );

    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(
                keys
                    .filter(function (key) {
                        return key !== CACHE_NAME;
                    })
                    .map(function (key) {
                        return caches.delete(key);
                    })
            );
        }).then(function () {
            return self.clients.claim();
        })
    );
});

self.addEventListener('fetch', function (event) {
    if (event.request.method !== 'GET') {
        return;
    }

    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(function () {
                return caches.match('/');
            })
        );

        return;
    }

    const requestUrl = new URL(event.request.url);
    const isSameOrigin = requestUrl.origin === self.location.origin;
    const isStaticAsset = ['style', 'script', 'image', 'font'].includes(event.request.destination)
        || requestUrl.pathname.endsWith('.webmanifest');

    if (!isSameOrigin || !isStaticAsset) {
        return;
    }

    event.respondWith(
        caches.match(event.request).then(function (cachedResponse) {
            if (cachedResponse) {
                return cachedResponse;
            }

            return fetch(event.request).then(function (networkResponse) {
                if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') {
                    return networkResponse;
                }

                const responseToCache = networkResponse.clone();

                caches.open(CACHE_NAME).then(function (cache) {
                    cache.put(event.request, responseToCache);
                });

                return networkResponse;
            }).catch(function () {
                return caches.match('/');
            });
        })
    );
});

self.addEventListener('push', function (event) {
    if (!event.data) {
        return;
    }

    let data = {};

    try {
        data = event.data.json();
    } catch (error) {
        data = {
            title: 'VGLTU',
            body: event.data.text(),
        };
    }

    const title = data.title || 'VGLTU';
    const body = data.body || data.message || 'Open VGLTU to view this notification.';
    const icon = data.icon || '/logo_en.png';
    const badge = data.badge || '/pwa-icon-192.png';
    const url = data.url || '/';

    event.waitUntil(
        self.registration.showNotification(title, {
            body: body,
            icon: icon,
            badge: badge,
            tag: data.tag || 'vgltu-notification',
            actions: data.actions || [
                { action: 'open', title: 'Open' },
            ],
            data: {
                url: url,
            },
        })
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const targetUrl = event.notification.data && event.notification.data.url
        ? event.notification.data.url
        : '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            for (const client of clientList) {
                if ('focus' in client && client.url === targetUrl) {
                    return client.focus();
                }
            }

            for (const client of clientList) {
                if ('focus' in client) {
                    client.navigate(targetUrl);
                    return client.focus();
                }
            }

            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
