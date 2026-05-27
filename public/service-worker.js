self.addEventListener('push', function (event) {
    if (!event.data) {
        return;
    }

    const data = event.data.json();

    event.waitUntil(
        self.registration.showNotification(data.title || 'Notification', {
            body: data.body || 'Open the portal to view this notification.',
            icon: data.icon || '/default-avatar.png',
            badge: data.badge || '/default-avatar.png',
            tag: data.tag || 'portal-notification',
            data: {
                url: data.url || '/',
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
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }

            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
