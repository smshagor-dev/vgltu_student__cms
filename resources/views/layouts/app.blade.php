<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $settings = $publicShell['settings'] ?? [];
    $menus = $publicShell['menus'] ?? [];
    $siteName = $settings['site_name'] ?? config('app.name', 'Global Study Gateway');
    $pwaName = 'VGLTU';
    $logoUrl = $settings['logo_url'] ?? 'https://vgltu.ru/templates/default/images/logo_en.png';
    $iconVersion = '20260528-1';
    $faviconUrl = asset('logo_en.png') . '?v=' . $iconVersion;
    $pwaIconUrl = asset('logo_en.png') . '?v=' . $iconVersion;
    $languages = $settings['available_languages'] ?? ['EN'];
    $authUser = auth()->user();
    $allNotifications = $authUser ? $authUser->userNotifications()->get() : collect();
    $unreadNotifications = $allNotifications->filter(fn ($notification) => $notification->read_at === null)->values();
    $readNotifications = $allNotifications->filter(fn ($notification) => $notification->read_at !== null)->values();
    $notificationCount = $unreadNotifications->count();
    $browserNotificationsEnabled = (bool) ($authUser?->browser_notifications_enabled ?? false);
    $webPushPublicKey = config('webpush.vapid.public_key');
    $unreadBrowserNotifications = $unreadNotifications->map(function ($notification) {
        return [
            'id' => $notification->id,
            'title' => $notification->title,
            'description' => $notification->description,
            'open_url' => route('notifications.open', $notification),
        ];
    })->values();
    $profilePhoto = $authUser && !empty($authUser->photo) ? asset('storage/' . $authUser->photo) : asset('default-avatar.png');
    $menuIconMap = [
        'home' => 'fas fa-home',
        'universities' => 'fas fa-university',
        'university' => 'fas fa-university',
        'department' => 'fas fa-building',
        'departments' => 'fas fa-building',
        'course' => 'fas fa-book-open',
        'courses' => 'fas fa-book-open',
        'contact us' => 'fas fa-envelope',
        'contact' => 'fas fa-envelope',
    ];
    $studentServicesMenu = [
        [
            'title' => 'Campaigns',
            'description' => 'Open active campaign forms and submit updates.',
            'icon' => 'fas fa-bullhorn',
            'url' => route('campaigns.index'),
        ],
        [
            'title' => 'Submit Data',
            'description' => 'Fill in and send your required information.',
            'icon' => 'fas fa-file-signature',
            'url' => route('user.custom-fields.create'),
        ],
        [
            'title' => 'View Submitted Data',
            'description' => 'Review the information you already submitted.',
            'icon' => 'fas fa-folder-open',
            'url' => route('user.custom-fields.index'),
        ],
        [
            'title' => 'Submit a Complaint',
            'description' => 'Report an issue or send a support request.',
            'icon' => 'fas fa-comment-dots',
            'url' => route('complaints.create'),
        ],
    ];
    $mobileFooterMenu = $authUser
        ? [
            [
                'title' => 'Home',
                'icon' => 'fas fa-home',
                'type' => 'link',
                'url' => route('welcome'),
            ],
            [
                'title' => 'Menu',
                'icon' => 'fas fa-compass',
                'type' => 'drawer',
                'target' => '#mobileMenuDrawer',
            ],
            [
                'title' => 'Campaigns',
                'icon' => 'fas fa-bullhorn',
                'type' => 'link',
                'url' => route('campaigns.index'),
            ],
            [
                'title' => 'Profile',
                'icon' => 'fas fa-user-circle',
                'type' => 'drawer',
                'target' => '#mobileProfileDrawer',
                'url' => null,
            ],
        ]
        : [
            [
                'title' => 'Login',
                'icon' => 'fas fa-sign-in-alt',
                'type' => 'modal',
                'target' => '#loginModal',
                'url' => null,
            ],
            [
                'title' => 'Registration',
                'icon' => 'fas fa-user-plus',
                'type' => 'link',
                'url' => route('register'),
            ],
        ];
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $siteName }}</title>
    <meta name="application-name" content="{{ $pwaName }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ $pwaName }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#241726">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $pwaIconUrl }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/homepage.css', 'resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        html,
        body {
            width: 100%;
            overflow-x: hidden;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        .edu-login-modal .modal-content {
            border: 0;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(29, 18, 31, 0.24);
        }

        .edu-login-btn--modal-trigger {
            background: #241726;
            color: #fff;
            border: 1px solid #241726;
            box-shadow: 0 12px 24px rgba(36, 23, 38, 0.18);
        }

        .edu-login-btn--modal-trigger:hover {
            color: #fff;
            background: #bb3e71;
            border-color: #bb3e71;
        }

        .edu-login-modal .modal-header {
            border-bottom: 0;
            padding: 28px 28px 0;
        }

        .edu-login-modal .modal-title {
            color: #241726;
            font-size: 1.65rem;
            font-weight: 800;
        }

        .edu-login-modal .btn-close {
            box-shadow: none;
        }

        .edu-login-modal .modal-body {
            padding: 20px 28px 28px;
        }

        .edu-login-modal__lead {
            color: #6f6572;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .edu-login-modal__group {
            margin-bottom: 16px;
        }

        .edu-login-modal__group label {
            display: block;
            margin-bottom: 8px;
            color: #241726;
            font-weight: 600;
        }

        .edu-login-modal__group .form-control {
            min-height: 50px;
            border-radius: 14px;
        }

        .edu-login-modal__toggle {
            border-radius: 0 14px 14px 0;
            min-width: 52px;
        }

        .edu-login-modal__submit {
            width: 100%;
            min-height: 50px;
            border: 0;
            border-radius: 999px;
            background: linear-gradient(135deg, #f173aa, #bb3e71);
            color: #fff;
            font-weight: 700;
            box-shadow: 0 10px 24px rgba(187, 62, 113, 0.3);
        }

        .edu-login-modal__submit:hover {
            color: #fff;
        }

        .edu-login-modal__links {
            margin-top: 18px;
            text-align: center;
            color: #6f6572;
        }

        .edu-login-modal__links a {
            color: #bb3e71;
            font-weight: 700;
            text-decoration: none;
        }

        .edu-login-modal__remember {
            margin-bottom: 18px;
        }

        .edu-auth-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            transform: translateX(-5px);
        }

        .edu-header-tools--auth .edu-desktop-tool {
            transform: translateX(-10px);
        }

        .edu-auth-btn {
            min-height: 46px;
            padding: 10px 18px;
            border-radius: 999px;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, color 0.2s ease;
        }

        .edu-auth-btn:hover,
        .edu-auth-btn:focus {
            transform: translateY(-1px);
        }

        .edu-auth-btn--secondary {
            background: #fff;
            border: 1px solid rgba(36, 23, 38, 0.14);
            color: #241726;
            box-shadow: 0 10px 22px rgba(76, 42, 65, 0.08);
        }

        .edu-auth-btn--secondary:hover,
        .edu-auth-btn--secondary:focus {
            color: #bb3e71;
            border-color: rgba(187, 62, 113, 0.28);
        }

        .edu-auth-btn--primary {
            background: linear-gradient(135deg, #241726, #bb3e71);
            border: 1px solid transparent;
            color: #fff;
            box-shadow: 0 12px 24px rgba(187, 62, 113, 0.24);
        }

        .edu-auth-btn--primary:hover,
        .edu-auth-btn--primary:focus {
            color: #fff;
            box-shadow: 0 16px 28px rgba(187, 62, 113, 0.3);
        }

        .edu-notification-btn,
        .edu-profile-trigger {
            width: 48px;
            height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 1px solid rgba(35, 23, 38, 0.1);
            background: #fff;
            color: #241726;
            box-shadow: 0 14px 30px rgba(76, 42, 65, 0.08);
            text-decoration: none;
            position: relative;
            padding: 0;
            transition: transform 0.2s ease, box-shadow 0.2s ease, color 0.2s ease;
        }

        .edu-profile-trigger {
            overflow: hidden;
        }

        .edu-notification-btn:hover,
        .edu-profile-trigger:hover,
        .edu-profile-trigger:focus {
            color: #bb3e71;
            transform: translateY(-1px);
            box-shadow: 0 18px 34px rgba(76, 42, 65, 0.12);
        }

        .edu-notification-btn i {
            font-size: 1.05rem;
        }

        .edu-notification-dropdown .dropdown-toggle::after {
            display: none;
        }

        .edu-notification-count {
            position: absolute;
            top: -10px;
            right: 50%;
            transform: translateX(50%);
            min-width: 20px;
            height: 20px;
            padding: 0 5px;
            border-radius: 999px;
            background: linear-gradient(135deg, #f173aa, #bb3e71);
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 18px rgba(187, 62, 113, 0.3);
            z-index: 2;
        }

        .edu-desktop-tool .edu-notification-count {
            top: -10px;
            right: 50%;
            transform: translateX(50%);
        }

        .edu-notification-menu {
            min-width: 320px;
            padding: 0.65rem;
            border: 1px solid rgba(35, 23, 38, 0.08);
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 22px 48px rgba(48, 28, 46, 0.16);
            margin-top: 12px;
        }

        .edu-notification-menu__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.6rem 0.7rem 0.95rem;
            margin-bottom: 0.45rem;
            border-bottom: 1px solid rgba(35, 23, 38, 0.08);
        }

        .edu-notification-menu__header strong {
            color: #241726;
            font-size: 0.98rem;
        }

        .edu-notification-menu__header span {
            color: #6f6572;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .edu-notification-item {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            padding: 0.85rem 0.9rem;
            border-radius: 16px;
            text-decoration: none;
            color: #241726;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .edu-notification-item:hover,
        .edu-notification-item:focus {
            background: rgba(215, 89, 139, 0.08);
            color: #241726;
            transform: translateX(2px);
        }

        .edu-notification-item i {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: rgba(215, 89, 139, 0.12);
            color: #bb3e71;
            flex-shrink: 0;
        }

        .edu-notification-item strong,
        .edu-notification-item span {
            display: block;
        }

        .edu-notification-item strong {
            color: #241726;
            font-size: 0.92rem;
            line-height: 1.35;
        }

        .edu-notification-item span {
            margin-top: 0.2rem;
            color: #6f6572;
            font-size: 0.83rem;
            line-height: 1.55;
        }

        .edu-notification-empty {
            padding: 1rem 0.9rem;
            text-align: center;
            color: #6f6572;
        }

        .edu-notification-empty i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            margin-bottom: 0.75rem;
            border-radius: 16px;
            background: rgba(215, 89, 139, 0.1);
            color: #bb3e71;
        }

        .edu-notification-empty strong,
        .edu-notification-empty span {
            display: block;
        }

        .edu-notification-empty strong {
            color: #241726;
            font-size: 0.94rem;
        }

        .edu-notification-empty span {
            margin-top: 0.2rem;
            font-size: 0.84rem;
        }

        .edu-notification-modal .modal-content {
            border: 0;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(29, 18, 31, 0.18);
        }

        .edu-notification-modal .modal-header {
            padding: 22px 24px 14px;
            border-bottom: 1px solid rgba(35, 23, 38, 0.08);
        }

        .edu-notification-modal .modal-title {
            color: #241726;
            font-size: 1.2rem;
            font-weight: 800;
        }

        .edu-notification-modal .modal-body {
            padding: 18px 24px 24px;
        }

        .edu-notification-tools {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .edu-notification-tools__actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .edu-notification-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(215, 89, 139, 0.12);
            color: #bb3e71;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .edu-notification-secondary-btn,
        .edu-notification-primary-btn {
            border: 0;
            border-radius: 999px;
            min-height: 42px;
            padding: 10px 16px;
            font-weight: 700;
        }

        .edu-notification-secondary-btn {
            background: #fff4f7;
            color: #241726;
        }

        .edu-notification-primary-btn {
            background: linear-gradient(135deg, #241726, #bb3e71);
            color: #fff;
        }

        .edu-notification-tabs {
            border-bottom: 0;
            gap: 8px;
            margin-bottom: 16px;
        }

        .edu-notification-tabs .nav-link {
            border: 0;
            border-radius: 999px;
            background: #fff4f7;
            color: #6f6572;
            font-weight: 700;
            padding: 10px 16px;
        }

        .edu-notification-tabs .nav-link.active {
            background: linear-gradient(135deg, #241726, #bb3e71);
            color: #fff;
        }

        .edu-notification-table-wrap {
            overflow-x: auto;
        }

        .edu-notification-table {
            width: 100%;
            min-width: 720px;
        }

        .edu-notification-table td,
        .edu-notification-table th {
            vertical-align: middle;
        }

        .edu-notification-status {
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.03em;
        }

        .edu-notification-status--unread {
            background: rgba(215, 89, 139, 0.12);
            color: #bb3e71;
        }

        .edu-notification-status--read {
            background: rgba(36, 23, 38, 0.08);
            color: #241726;
        }

        .edu-notification-row-title {
            color: #241726;
            font-weight: 700;
        }

        .edu-notification-row-description {
            color: #6f6572;
            font-size: 0.86rem;
            line-height: 1.55;
        }

        .edu-notification-row {
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .edu-notification-row:hover {
            background: rgba(215, 89, 139, 0.06);
        }

        .edu-profile-dropdown .dropdown-toggle::after {
            display: none;
        }

        .edu-profile-trigger img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            display: block;
        }

        .edu-profile-menu {
            min-width: 270px;
            padding: 0.65rem;
            border: 1px solid rgba(35, 23, 38, 0.08);
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 22px 48px rgba(48, 28, 46, 0.16);
            margin-top: 12px;
        }

        .edu-profile-menu__header {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.6rem 0.6rem 0.95rem;
            margin-bottom: 0.45rem;
            border-bottom: 1px solid rgba(35, 23, 38, 0.08);
        }

        .edu-profile-menu__header img {
            width: 52px;
            height: 52px;
            object-fit: cover;
            border-radius: 50%;
            display: block;
            flex-shrink: 0;
            border: 2px solid rgba(215, 89, 139, 0.14);
        }

        .edu-profile-menu__header strong,
        .edu-profile-menu__header span {
            display: block;
        }

        .edu-profile-menu__header strong {
            color: #241726;
            line-height: 1.3;
            font-size: 0.98rem;
        }

        .edu-profile-menu__header span {
            margin-top: 0.2rem;
            font-size: 0.84rem;
            color: #6f6572;
            word-break: break-word;
        }

        .edu-profile-menu .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.78rem;
            border-radius: 14px;
            padding: 0.8rem 0.9rem;
            color: #241726;
            font-weight: 600;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .edu-profile-menu button.dropdown-item {
            width: 100%;
            border: 0;
            background: transparent;
            text-align: left;
        }

        .edu-profile-menu .dropdown-item i {
            width: 18px;
            text-align: center;
            color: #bb3e71;
        }

        .edu-profile-menu .dropdown-item:hover,
        .edu-profile-menu .dropdown-item:focus {
            background: rgba(215, 89, 139, 0.08);
            color: #bb3e71;
            transform: translateX(2px);
        }

        .edu-profile-menu__logout {
            color: #b7345f !important;
        }

        .edu-profile-menu__logout i {
            color: #b7345f !important;
        }

        .edu-services-menu {
            min-width: 310px;
            padding: 0.6rem;
        }

        .edu-services-menu .dropdown-item {
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
            border-radius: 16px;
            padding: 0.9rem;
        }

        .edu-services-menu .dropdown-item i {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(215, 89, 139, 0.12);
            color: #bb3e71;
            flex-shrink: 0;
        }

        .edu-services-menu__content {
            display: block;
        }

        .edu-services-menu__content strong,
        .edu-services-menu__content span {
            display: block;
        }

        .edu-services-menu__content strong {
            color: #241726;
            font-size: 0.92rem;
            line-height: 1.35;
        }

        .edu-services-menu__content span {
            margin-top: 0.2rem;
            color: #6f6572;
            font-size: 0.8rem;
            line-height: 1.5;
        }

        .edu-mobile-tool {
            display: none;
        }

        .edu-mobile-header {
            display: none;
        }

        .edu-toggler--mobile {
            display: none;
        }

        .edu-mobile-drawer {
            border: 0;
            border-radius: 24px 24px 0 0;
            height: calc((100dvh - var(--edu-mobile-header-offset, 0px)) * 0.5);
            min-height: 700px;
            max-height: 700px;
        }

        .edu-mobile-drawer .offcanvas-header {
            padding: 1rem 1rem 0.5rem;
        }

        .edu-mobile-drawer .offcanvas-title {
            color: #241726;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .edu-mobile-drawer .offcanvas-body {
            padding: 0.75rem 1rem 1rem;
            overflow-y: auto;
        }

        .edu-mobile-drawer__stack {
            display: grid;
            gap: 0.75rem;
        }

        .edu-mobile-drawer__card {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            padding: 0.9rem;
            border-radius: 18px;
            background: #fffaf7;
            border: 1px solid rgba(35, 23, 38, 0.06);
            text-decoration: none;
            color: #241726;
        }

        .edu-mobile-drawer__card--button {
            width: 100%;
            border: 0;
            text-align: left;
        }

        .edu-mobile-drawer__card:hover,
        .edu-mobile-drawer__card:focus {
            color: #241726;
        }

        .edu-mobile-drawer__card i {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: rgba(215, 89, 139, 0.12);
            color: #bb3e71;
            flex-shrink: 0;
        }

        .edu-mobile-drawer__card strong,
        .edu-mobile-drawer__card span {
            display: block;
        }

        .edu-mobile-drawer__card strong {
            font-size: 0.93rem;
            line-height: 1.35;
        }

        .edu-mobile-drawer__card span {
            margin-top: 0.2rem;
            color: #6f6572;
            font-size: 0.84rem;
            line-height: 1.55;
        }

        .edu-mobile-notification-list {
            display: grid;
            gap: 0.75rem;
        }

        .edu-mobile-notification-item {
            display: block;
            padding: 0.95rem;
            border-radius: 18px;
            background: #fffaf7;
            border: 1px solid rgba(35, 23, 38, 0.06);
            color: #241726;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .edu-mobile-notification-item:hover,
        .edu-mobile-notification-item:focus {
            color: #241726;
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(76, 42, 65, 0.08);
        }

        .edu-mobile-notification-item__top {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .edu-mobile-notification-item__icon {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: rgba(215, 89, 139, 0.12);
            color: #bb3e71;
            flex-shrink: 0;
        }

        .edu-mobile-notification-item__content {
            min-width: 0;
        }

        .edu-mobile-notification-item__content strong,
        .edu-mobile-notification-item__content span {
            display: block;
        }

        .edu-mobile-notification-item__content strong {
            color: #241726;
            font-size: 0.94rem;
            line-height: 1.4;
        }

        .edu-mobile-notification-item__content span {
            margin-top: 0.25rem;
            color: #6f6572;
            font-size: 0.84rem;
            line-height: 1.55;
        }

        .edu-mobile-notification-item__meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-top: 0.8rem;
            flex-wrap: wrap;
        }

        .edu-mobile-footer-nav {
            display: none;
        }

        @media (max-width: 1199.98px) {
            body.edu-body {
                padding-bottom: 92px;
            }

            .edu-topbar .container,
            .edu-header .container,
            .edu-footer .container {
                width: 100%;
                max-width: 1200px;
                padding-left: 14px;
                padding-right: 14px;
            }

            .edu-profile-trigger.edu-mobile-tool {
                display: none !important;
            }

            .edu-navbar {
                min-height: 78px;
                justify-content: space-between;
            }

            .edu-brand {
                display: inline-flex !important;
                align-items: center;
                min-width: 0;
                max-width: calc(100% - 78px);
            }

            .edu-brand img {
                width: 42px;
                height: 42px;
                flex-shrink: 0;
            }

            .edu-brand span {
                display: block;
                font-size: 0.96rem;
                line-height: 1.25;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .edu-topbar__location {
                display: none !important;
            }

            .edu-topbar__inner {
                align-items: center;
                justify-content: center;
                padding: 0.65rem 0;
            }

            .edu-topbar__links {
                width: 100%;
                justify-content: center;
                gap: 0.45rem;
            }

            .edu-topbar__links a {
                min-height: 38px;
                padding: 0.55rem 0.8rem;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.96);
                border: 1px solid rgba(35, 23, 38, 0.08);
                font-size: 0.8rem;
                box-shadow: 0 8px 20px rgba(76, 42, 65, 0.08);
            }

            .edu-header .container {
                padding-left: 14px;
                padding-right: 14px;
            }

            .edu-header-tools {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 12px;
            }

            .edu-header-tools > select,
            .edu-header-tools > .edu-login-btn,
            .edu-header-tools > .edu-contact-btn,
            .edu-header-tools > .edu-desktop-tool,
            .edu-header-tools > .edu-notification-btn.edu-mobile-tool,
            .edu-header-tools > .edu-profile-trigger.edu-mobile-tool {
                display: none !important;
            }

            .edu-language-select {
                flex: 1 1 auto;
                min-width: 0;
            }

            .edu-login-modal .modal-header {
                padding: 22px 18px 0;
            }

            .edu-login-modal .modal-body {
                padding: 18px 18px 22px;
            }

            .edu-profile-menu {
                min-width: 0;
                width: min(100vw - 32px, 280px);
            }

            .edu-desktop-tool {
                display: none !important;
            }

            .edu-mobile-tool {
                display: inline-flex;
            }

            .edu-toggler {
                display: inline-flex !important;
                margin-right: 0 !important;
            }

            .edu-mobile-header {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-left: auto;
                max-width: 100%;
                flex-shrink: 0;
            }

            .edu-mobile-header .edu-notification-btn {
                display: inline-flex !important;
                transform: translateX(-10px);
            }

            .edu-mobile-header .edu-notification-count {
                top: -6px;
                right: -6px;
                transform: none;
            }

            .edu-toggler--desktop {
                display: none !important;
            }

            .edu-toggler--mobile {
                display: inline-flex !important;
            }

            .edu-mobile-footer-nav {
                position: fixed;
                left: 14px;
                right: 14px;
                bottom: 14px;
                z-index: 1040;
                display: grid;
                gap: 10px;
                padding: 10px 12px calc(10px + env(safe-area-inset-bottom));
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(18px);
                border: 1px solid rgba(35, 23, 38, 0.08);
                border-radius: 28px;
                box-shadow: 0 18px 36px rgba(48, 28, 46, 0.14);
            }

            .edu-mobile-footer-nav--guest {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .edu-mobile-footer-nav--auth {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }

            .edu-mobile-footer-nav__item {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
                gap: 10px;
                min-height: 52px;
                border: 0;
                padding: 0 12px;
                border-radius: 18px;
                background: rgba(255, 247, 241, 0.9);
                color: #241726;
                text-decoration: none;
                font-size: 0.84rem;
                font-weight: 700;
                position: relative;
            }

            .edu-mobile-footer-nav--auth .edu-mobile-footer-nav__item {
                flex-direction: column;
                gap: 6px;
                min-height: 58px;
                padding: 0;
                border-radius: 16px;
                font-size: 11px;
            }

            .edu-mobile-footer-nav__item i {
                width: 34px;
                height: 34px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 12px;
                background: rgba(215, 89, 139, 0.12);
                color: #bb3e71;
                font-size: 14px;
            }

            .edu-mobile-footer-nav--auth .edu-mobile-footer-nav__item i {
                width: 38px;
                height: 38px;
                border-radius: 14px;
                font-size: 15px;
            }

            .edu-mobile-footer-nav__item span {
                line-height: 1.2;
                overflow-wrap: anywhere;
            }
        }

        @media (max-width: 1199.98px) {
            .edu-toggler--desktop {
                display: none !important;
            }

            .edu-toggler--mobile {
                display: inline-flex !important;
            }
        }

        @media (max-width: 767.98px) {
            .edu-navbar {
                width: 100%;
                max-width: 100%;
                overflow: hidden;
                border-radius: 22px;
                padding: 0.75rem;
            }

            .edu-brand {
                max-width: calc(100% - 64px);
            }

            .edu-brand span {
                white-space: normal;
                overflow: visible;
                text-overflow: unset;
                overflow-wrap: anywhere;
            }

            .edu-topbar__links a {
                max-width: 100%;
                text-align: center;
            }

            .edu-auth-actions {
                width: 100%;
                flex-wrap: wrap;
                justify-content: stretch;
                transform: none;
            }

            .edu-auth-btn {
                flex: 1 1 160px;
                width: 100%;
            }

            .edu-mobile-footer-nav {
                left: 10px;
                right: 10px;
                bottom: 10px;
                padding-left: 8px;
                padding-right: 8px;
            }

            .edu-mobile-footer-nav__item {
                min-width: 0;
            }

            .edu-mobile-footer-nav__item span {
                text-align: center;
            }
        }
    </style>
</head>
<body class="edu-body">
    <div class="edu-app-shell">
        <div class="edu-topbar">
            <div class="container edu-topbar__inner">
                <div class="edu-topbar__location">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $settings['topbar_location'] ?? 'Voronezh, Russian Federation' }}</span>
                </div>
                <div class="edu-topbar__links">
                    <a href="{{ $settings['class_routine_link'] ?? url('/class_routine') }}">
                        <i class="far fa-calendar-alt"></i>{{ $settings['class_routine_text'] ?? 'Class Routine' }}
                    </a>
                    <a href="{{ url('/university-student-profile') }}">
                        <i class="fas fa-user-shield"></i>{{ $settings['university_profile_text'] ?? 'University Profile' }}
                    </a>
                </div>
            </div>
        </div>

        <header class="edu-header sticky-top">
            <div class="container">
                <nav class="navbar navbar-expand-xl edu-navbar">
                    <a class="navbar-brand edu-brand" href="{{ route('welcome') }}" style="transform: translateX(5px);">
                        <img src="{{ $logoUrl }}" alt="{{ $siteName }}">
                        <span>{{ $siteName }}</span>
                    </a>

                    <div class="edu-mobile-header">
                        @auth
                            <button class="btn edu-notification-btn edu-mobile-tool" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNotificationsDrawer" aria-controls="mobileNotificationsDrawer" aria-label="Notifications">
                                <i class="far fa-bell"></i>
                                <span class="edu-notification-count" style="{{ $notificationCount > 0 ? '' : 'display:none;' }}">{{ $notificationCount }}</span>
                            </button>
                        @endauth
                    </div>

                    <button class="navbar-toggler edu-toggler edu-toggler--desktop" type="button" data-bs-toggle="collapse" data-bs-target="#eduNavbar" aria-controls="eduNavbar" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}" style="margin-right: 5px;">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    @guest
                        <button class="navbar-toggler edu-toggler edu-toggler--mobile" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenuDrawer" aria-controls="mobileMenuDrawer" aria-label="{{ __('Open menu') }}" style="transform: translateX(-10px);">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    @endguest

                    <div class="collapse navbar-collapse" id="eduNavbar">
                        <ul class="navbar-nav mx-auto edu-nav-list">
                            @foreach ($menus as $menu)
                                @php
                                    $menuTitle = trim((string) ($menu['title'] ?? ''));
                                    $menuKey = \Illuminate\Support\Str::of($menuTitle)->lower()->squish()->value();
                                    $menuIcon = $menuIconMap[$menuKey] ?? null;
                                @endphp

                                @if ($menuKey === 'about')
                                    @continue
                                @endif

                                @if (!empty($menu['children']))
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="{{ $menu['url'] ?: '#' }}" id="menu-{{ $loop->index }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            @if ($menuIcon)
                                                <i class="{{ $menuIcon }} me-2"></i>
                                            @endif
                                            {{ $menu['title'] }}
                                        </a>
                                        <ul class="dropdown-menu edu-dropdown" aria-labelledby="menu-{{ $loop->index }}">
                                            @foreach ($menu['children'] as $child)
                                                <li><a class="dropdown-item" href="{{ $child['url'] }}" target="{{ $child['target'] }}">{{ $child['title'] }}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ $menu['url'] }}" target="{{ $menu['target'] }}">
                                            @if ($menuIcon)
                                                <i class="{{ $menuIcon }} me-2"></i>
                                            @endif
                                            {{ $menu['title'] }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach

                            @auth
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="student-services-menu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-briefcase me-2"></i>Student Services
                                    </a>
                                    <ul class="dropdown-menu edu-dropdown edu-services-menu" aria-labelledby="student-services-menu">
                                        @foreach ($studentServicesMenu as $serviceItem)
                                            <li>
                                                <a class="dropdown-item" href="{{ $serviceItem['url'] }}">
                                                    <i class="{{ $serviceItem['icon'] }}"></i>
                                                    <span class="edu-services-menu__content">
                                                        <strong>{{ $serviceItem['title'] }}</strong>
                                                        <span>{{ $serviceItem['description'] }}</span>
                                                    </span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endauth
                        </ul>

                        <div class="edu-header-tools{{ $authUser ? ' edu-header-tools--auth' : '' }}">
                            @guest
                                <div class="edu-auth-actions">
                                    <button type="button" class="btn edu-auth-btn edu-auth-btn--secondary" data-bs-toggle="modal" data-bs-target="#loginModal">
                                        Login
                                    </button>
                                    <a class="btn edu-auth-btn edu-auth-btn--primary" href="{{ route('register') }}">
                                        Registration
                                    </a>
                                </div>
                            @else
                                <div class="edu-desktop-tool">
                                    <button class="btn edu-notification-btn" type="button" data-bs-toggle="modal" data-bs-target="#userNotificationsModal" aria-label="Notifications">
                                        <i class="far fa-bell"></i>
                                        <span class="edu-notification-count" style="{{ $notificationCount > 0 ? '' : 'display:none;' }}">{{ $notificationCount }}</span>
                                    </button>
                                </div>

                                <div class="dropdown edu-profile-dropdown edu-desktop-tool">
                                    <button class="btn edu-profile-trigger dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="{{ $profilePhoto }}" alt="{{ $authUser->full_name }}" width="48" height="48" style="width:48px;height:48px;display:block;object-fit:cover;border-radius:50%;" onerror="this.onerror=null;this.src='{{ asset('default-avatar.png') }}';">
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end edu-profile-menu">
                                        <div class="edu-profile-menu__header">
                                            <img src="{{ $profilePhoto }}" alt="{{ $authUser->full_name }}" width="52" height="52" style="width:52px;height:52px;display:block;object-fit:cover;border-radius:50%;flex-shrink:0;" onerror="this.onerror=null;this.src='{{ asset('default-avatar.png') }}';">
                                            <div>
                                                <strong>{{ $authUser->full_name }}</strong>
                                                <span>{{ $authUser->email }}</span>
                                            </div>
                                        </div>
                                        <a class="dropdown-item" href="{{ route('home') }}"><i class="fas fa-th-large"></i> Dashboard</a>
                                        <button type="button" class="dropdown-item edu-notification-modal-trigger" data-bs-toggle="modal" data-bs-target="#userNotificationsModal">
                                            <i class="far fa-bell"></i> Notifications
                                        </button>
                                        <a class="dropdown-item" href="{{ route('user.edit') }}"><i class="fas fa-user-edit"></i> Edit Profile</a>
                                        <a class="dropdown-item" href="{{ route('students_data.index') }}"><i class="far fa-id-card"></i> My Documents</a>
                                        <a class="dropdown-item" href="{{ route('user.medicalStatus') }}"><i class="fas fa-heartbeat"></i> Medical Status</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item edu-profile-menu__logout" href="{{ route('logout.get') }}"><i class="fas fa-sign-out-alt"></i> Logout</a>
                                    </div>
                                </div>

                                <button class="btn edu-notification-btn edu-mobile-tool" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNotificationsDrawer" aria-controls="mobileNotificationsDrawer" aria-label="Notifications">
                                    <i class="far fa-bell"></i>
                                    <span class="edu-notification-count" style="{{ $notificationCount > 0 ? '' : 'display:none;' }}">{{ $notificationCount }}</span>
                                </button>
                            @endguest
                        </div>
                    </div>
                </nav>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        @include('layouts.partials.footer', ['settings' => $settings])

        @if (!empty($mobileFooterMenu))
            <nav class="edu-mobile-footer-nav {{ $authUser ? 'edu-mobile-footer-nav--auth' : 'edu-mobile-footer-nav--guest' }}" aria-label="Mobile footer navigation">
                @foreach ($mobileFooterMenu as $footerItem)
                    @if ($footerItem['type'] === 'link')
                        <a class="edu-mobile-footer-nav__item" href="{{ $footerItem['url'] }}">
                            <i class="{{ $footerItem['icon'] }}"></i>
                            <span>{{ $footerItem['title'] }}</span>
                        </a>
                    @elseif ($footerItem['type'] === 'modal')
                        <button type="button" class="edu-mobile-footer-nav__item" data-bs-toggle="modal" data-bs-target="{{ $footerItem['target'] }}">
                            <i class="{{ $footerItem['icon'] }}"></i>
                            <span>{{ $footerItem['title'] }}</span>
                        </button>
                    @else
                        <button type="button" class="edu-mobile-footer-nav__item" data-bs-toggle="offcanvas" data-bs-target="{{ $footerItem['target'] }}" aria-controls="{{ ltrim($footerItem['target'], '#') }}">
                            <i class="{{ $footerItem['icon'] }}"></i>
                            <span>{{ $footerItem['title'] }}</span>
                        </button>
                    @endif
                @endforeach
            </nav>
        @endif
    </div>

    @guest
        <div class="modal fade edu-login-modal" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h2 class="modal-title" id="loginModalLabel">Login to Your Account</h2>
                            <p class="edu-login-modal__lead">Access your dashboard, student profile, and university services without leaving this page.</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="login_modal" value="1">

                            <div class="edu-login-modal__group">
                                <label for="modal-email">Email Address</label>
                                <input id="modal-email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="edu-login-modal__group">
                                <label for="modal-password">Password</label>
                                <div class="input-group">
                                    <input id="modal-password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    <button type="button" class="btn btn-outline-secondary edu-login-modal__toggle" id="toggleModalPassword" aria-label="Show password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-check edu-login-modal__remember">
                                <input class="form-check-input" type="checkbox" name="remember" id="modal-remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="modal-remember">Remember Me</label>
                            </div>

                            <button type="submit" class="edu-login-modal__submit">Login</button>

                            <div class="edu-login-modal__links">
                                <a href="{{ route('password.request') }}">Forgot Your Password?</a>
                            </div>
                            <div class="edu-login-modal__links">
                                <span>Don't have an account?</span>
                                <a href="{{ route('register') }}">Registration</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endguest

    @auth
        @php
            $notificationGroups = [
                'all' => $allNotifications,
                'unread' => $unreadNotifications,
                'read' => $readNotifications,
            ];
        @endphp

        <div class="modal fade edu-notification-modal" id="userNotificationsModal" tabindex="-1" aria-labelledby="userNotificationsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title" id="userNotificationsModalLabel">Your Notifications</h5>
                            <div class="text-muted small mt-1">Track unread, read, and all updates in one place.</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="edu-notification-tools">
                            <div class="edu-notification-chip" data-notification-count-label>
                                <i class="far fa-bell"></i>
                                {{ $notificationCount }} unread
                            </div>
                            <div class="edu-notification-tools__actions">
                                <button type="button" class="edu-notification-secondary-btn" id="markAllNotificationsRead">Mark all as read</button>
                            </div>
                        </div>

                        <ul class="nav nav-tabs edu-notification-tabs" id="userNotificationTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#notification-all" type="button" role="tab">All</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#notification-unread" type="button" role="tab">Unread</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#notification-read" type="button" role="tab">Read</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="desktopNotificationTabContent">
                            @foreach ($notificationGroups as $groupKey => $groupNotifications)
                                <div class="tab-pane fade {{ $groupKey === 'all' ? 'show active' : '' }}" id="notification-{{ $groupKey }}" role="tabpanel">
                                    @if ($groupNotifications->isEmpty())
                                        <div class="edu-notification-empty">
                                            <i class="far fa-bell"></i>
                                            <strong>No {{ $groupKey }} notifications</strong>
                                            <span>We will show updates here when they arrive.</span>
                                        </div>
                                    @else
                                        <div class="edu-notification-table-wrap">
                                            <table class="table edu-notification-table">
                                                <thead>
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Description</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($groupNotifications as $notification)
                                                        <tr class="edu-notification-row" data-open-url="{{ route('notifications.open', $notification) }}">
                                                            <td>
                                                                <div class="edu-notification-row-title">{{ $notification->title }}</div>
                                                            </td>
                                                            <td>
                                                                <div class="edu-notification-row-description">{{ $notification->description ?: 'No extra details provided.' }}</div>
                                                            </td>
                                                            <td>
                                                                <span class="edu-notification-status {{ $notification->read_at ? 'edu-notification-status--read' : 'edu-notification-status--unread' }}">
                                                                    {{ $notification->read_at ? 'Read' : 'Unread' }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $notification->created_at?->format('d M Y, h:i A') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-bottom edu-mobile-drawer" tabindex="-1" id="mobileNotificationsDrawer" aria-labelledby="mobileNotificationsDrawerLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="mobileNotificationsDrawerLabel">Your Notifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="edu-notification-tools">
                    <div class="edu-notification-chip" data-notification-count-label>
                        <i class="far fa-bell"></i>
                        {{ $notificationCount }} unread
                    </div>
                    <div class="edu-notification-tools__actions">
                        <button type="button" class="edu-notification-secondary-btn" id="mobileMarkAllNotificationsRead">Mark all as read</button>
                    </div>
                </div>

                <ul class="nav nav-tabs edu-notification-tabs" id="mobileUserNotificationTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#mobile-notification-all" type="button" role="tab">All</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#mobile-notification-unread" type="button" role="tab">Unread</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#mobile-notification-read" type="button" role="tab">Read</button>
                    </li>
                </ul>

                <div class="tab-content" id="mobileNotificationTabContent">
                    @foreach ($notificationGroups as $groupKey => $groupNotifications)
                        <div class="tab-pane fade {{ $groupKey === 'all' ? 'show active' : '' }}" id="mobile-notification-{{ $groupKey }}" role="tabpanel">
                            @if ($groupNotifications->isEmpty())
                                <div class="edu-notification-empty">
                                    <i class="far fa-bell"></i>
                                    <strong>No {{ $groupKey }} notifications</strong>
                                    <span>We will show updates here when they arrive.</span>
                                </div>
                            @else
                                <div class="edu-mobile-notification-list">
                                    @foreach ($groupNotifications as $notification)
                                        <a class="edu-mobile-notification-item" href="{{ route('notifications.open', $notification) }}">
                                            <div class="edu-mobile-notification-item__top">
                                                <div class="edu-mobile-notification-item__icon">
                                                    <i class="{{ filled(trim((string) $notification->icon)) ? $notification->icon : 'far fa-bell' }}"></i>
                                                </div>
                                                <div class="edu-mobile-notification-item__content">
                                                    <strong>{{ $notification->title }}</strong>
                                                    <span>{{ $notification->description ?: 'No extra details provided.' }}</span>
                                                </div>
                                            </div>
                                            <div class="edu-mobile-notification-item__meta">
                                                <span class="edu-notification-status {{ $notification->read_at ? 'edu-notification-status--read' : 'edu-notification-status--unread' }}">
                                                    {{ $notification->read_at ? 'Read' : 'Unread' }}
                                                </span>
                                                <span class="text-muted small">{{ $notification->created_at?->format('d M Y, h:i A') }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endauth

        <div class="offcanvas offcanvas-bottom edu-mobile-drawer" tabindex="-1" id="mobileMenuDrawer" aria-labelledby="mobileMenuDrawerLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="mobileMenuDrawerLabel">Main Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="edu-mobile-drawer__stack">
                    @foreach ($menus as $menu)
                        @php
                            $menuTitle = trim((string) ($menu['title'] ?? ''));
                            $menuKey = \Illuminate\Support\Str::of($menuTitle)->lower()->squish()->value();
                            $menuIcon = $menuIconMap[$menuKey] ?? 'fas fa-angle-right';
                        @endphp

                        @if ($menuKey === 'about')
                            @continue
                        @endif

                        @if (!empty($menu['children']))
                            @foreach ($menu['children'] as $child)
                                <a
                                    class="edu-mobile-drawer__card"
                                    href="{{ $child['url'] }}"
                                    target="{{ $child['target'] }}"
                                    @if (($child['target'] ?? '_self') === '_blank')
                                        onclick="window.open('{{ $child['url'] }}', '_blank', 'noopener'); return false;"
                                    @else
                                        onclick="window.location.href='{{ $child['url'] }}'; return false;"
                                    @endif
                                >
                                    <i class="{{ $menuIcon }}"></i>
                                    <div>
                                        <strong>{{ $child['title'] }}</strong>
                                        <span>{{ $menu['title'] }}</span>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <a
                                class="edu-mobile-drawer__card"
                                href="{{ $menu['url'] }}"
                                target="{{ $menu['target'] }}"
                                @if (($menu['target'] ?? '_self') === '_blank')
                                    onclick="window.open('{{ $menu['url'] }}', '_blank', 'noopener'); return false;"
                                @else
                                    onclick="window.location.href='{{ $menu['url'] }}'; return false;"
                                @endif
                            >
                                <i class="{{ $menuIcon }}"></i>
                                <div>
                                    <strong>{{ $menu['title'] }}</strong>
                                    <span>Open this section</span>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

    @auth
        <div class="offcanvas offcanvas-bottom edu-mobile-drawer" tabindex="-1" id="mobileServicesDrawer" aria-labelledby="mobileServicesDrawerLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="mobileServicesDrawerLabel">Student Services</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="edu-mobile-drawer__stack">
                    @auth
                        @foreach ($studentServicesMenu as $serviceItem)
                            <a class="edu-mobile-drawer__card" href="{{ $serviceItem['url'] }}">
                                <i class="{{ $serviceItem['icon'] }}"></i>
                                <div>
                                    <strong>{{ $serviceItem['title'] }}</strong>
                                    <span>{{ $serviceItem['description'] }}</span>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <button type="button" class="edu-mobile-drawer__card edu-mobile-drawer__card--button" data-bs-dismiss="offcanvas" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="fas fa-sign-in-alt"></i>
                            <div>
                                <strong>Login</strong>
                                <span>Access student services from your account.</span>
                            </div>
                        </button>
                        <a class="edu-mobile-drawer__card" href="{{ route('register') }}">
                            <i class="fas fa-user-plus"></i>
                            <div>
                                <strong>Registration</strong>
                                <span>Create a new student account.</span>
                            </div>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-bottom edu-mobile-drawer" tabindex="-1" id="mobileProfileDrawer" aria-labelledby="mobileProfileDrawerLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="mobileProfileDrawerLabel">Profile Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="edu-mobile-drawer__stack">
                    <div class="edu-profile-menu__header">
                        <img src="{{ $profilePhoto }}" alt="{{ $authUser->full_name }}" width="52" height="52" style="width:52px;height:52px;display:block;object-fit:cover;border-radius:50%;flex-shrink:0;" onerror="this.onerror=null;this.src='{{ asset('default-avatar.png') }}';">
                        <div>
                            <strong>{{ $authUser->full_name }}</strong>
                            <span>{{ $authUser->email }}</span>
                        </div>
                    </div>
                    <a class="edu-mobile-drawer__card" href="{{ route('home') }}"><i class="fas fa-th-large"></i><div><strong>Dashboard</strong><span>Open your student dashboard.</span></div></a>
                    <a class="edu-mobile-drawer__card" href="{{ route('user.edit') }}"><i class="fas fa-user-edit"></i><div><strong>Edit Profile</strong><span>Update your account information.</span></div></a>
                    <a class="edu-mobile-drawer__card" href="{{ route('students_data.index') }}"><i class="far fa-id-card"></i><div><strong>My Documents</strong><span>See your student personal records.</span></div></a>
                    <a class="edu-mobile-drawer__card" href="{{ route('user.medicalStatus') }}"><i class="fas fa-heartbeat"></i><div><strong>Medical Status</strong><span>Check and update your medical status.</span></div></a>
                    <a class="edu-mobile-drawer__card" href="{{ route('logout.get') }}"><i class="fas fa-sign-out-alt"></i><div><strong>Logout</strong><span>Sign out from your account.</span></div></a>
                </div>
            </div>
        </div>

    @endauth

    @if ($authUser || session('login_success') || session('registration_success') || (old('login_modal') && ($errors->has('email') || $errors->has('password'))))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endif
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const browserNotificationToggles = document.querySelectorAll('[data-browser-notification-toggle]');
            const browserNotificationStatuses = document.querySelectorAll('[data-browser-notification-status]');
            const markAllNotificationsRead = document.getElementById('markAllNotificationsRead');
            const mobileMarkAllNotificationsRead = document.getElementById('mobileMarkAllNotificationsRead');
            const notificationCountBadges = document.querySelectorAll('.edu-notification-count');
            const notificationCountLabels = document.querySelectorAll('[data-notification-count-label]');
            const mobileDrawerLinks = document.querySelectorAll('.edu-mobile-drawer a[href]');
            const desktopNotificationTabContent = document.getElementById('desktopNotificationTabContent');
            const mobileNotificationTabContent = document.getElementById('mobileNotificationTabContent');
            const webPushPublicKey = @json($webPushPublicKey);
            const authUserId = @json($authUser?->id);
            const canPersistPushSubscription = @json((bool) $authUser);
            let unreadBrowserNotifications = @json($unreadBrowserNotifications);
            let browserNotificationsEnabled = @json($browserNotificationsEnabled);
            let currentUnreadCount = unreadBrowserNotifications.length;

            const syncMobileDrawerOffset = function () {
                const header = document.querySelector('.edu-header');
                const headerHeight = header ? header.offsetHeight : 0;
                document.documentElement.style.setProperty('--edu-mobile-header-offset', headerHeight + 'px');
            };

            syncMobileDrawerOffset();
            window.addEventListener('resize', syncMobileDrawerOffset);

            const togglePasswordButton = document.getElementById('toggleModalPassword');
            const passwordInput = document.getElementById('modal-password');

            if (togglePasswordButton && passwordInput) {
                togglePasswordButton.addEventListener('click', function () {
                    const icon = this.querySelector('i');
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    if (icon) {
                        icon.classList.toggle('fa-eye');
                        icon.classList.toggle('fa-eye-slash');
                    }
                });
            }

            const renderUnreadCount = function (count) {
                currentUnreadCount = count;

                notificationCountBadges.forEach(function (badge) {
                    badge.textContent = count;
                    badge.style.display = count > 0 ? 'inline-flex' : 'none';
                });

                notificationCountLabels.forEach(function (label) {
                    label.innerHTML = '<i class="far fa-bell"></i> ' + count + ' unread';
                });
            };

            const escapeHtml = function (value) {
                return String(value || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            };

            const buildDesktopNotificationPanel = function (groupKey, notifications) {
                if (!notifications.length) {
                    return '<div class="tab-pane fade ' + (groupKey === 'all' ? 'show active' : '') + '" id="notification-' + groupKey + '" role="tabpanel">'
                        + '<div class="edu-notification-empty"><i class="far fa-bell"></i><strong>No ' + groupKey + ' notifications</strong><span>We will show updates here when they arrive.</span></div>'
                        + '</div>';
                }

                const rows = notifications.map(function (notification) {
                    const statusClass = notification.read_at ? 'edu-notification-status--read' : 'edu-notification-status--unread';
                    const statusLabel = notification.read_at ? 'Read' : 'Unread';

                    return '<tr class="edu-notification-row" data-open-url="' + escapeHtml(notification.open_url) + '">'
                        + '<td><div class="edu-notification-row-title">' + escapeHtml(notification.title) + '</div></td>'
                        + '<td><div class="edu-notification-row-description">' + escapeHtml(notification.description || 'No extra details provided.') + '</div></td>'
                        + '<td><span class="edu-notification-status ' + statusClass + '">' + statusLabel + '</span></td>'
                        + '<td>' + escapeHtml(notification.created_at || '') + '</td>'
                        + '</tr>';
                }).join('');

                return '<div class="tab-pane fade ' + (groupKey === 'all' ? 'show active' : '') + '" id="notification-' + groupKey + '" role="tabpanel">'
                    + '<div class="edu-notification-table-wrap"><table class="table edu-notification-table"><thead><tr><th>Title</th><th>Description</th><th>Status</th><th>Date</th></tr></thead><tbody>'
                    + rows
                    + '</tbody></table></div></div>';
            };

            const buildMobileNotificationPanel = function (groupKey, notifications) {
                if (!notifications.length) {
                    return '<div class="tab-pane fade ' + (groupKey === 'all' ? 'show active' : '') + '" id="mobile-notification-' + groupKey + '" role="tabpanel">'
                        + '<div class="edu-notification-empty"><i class="far fa-bell"></i><strong>No ' + groupKey + ' notifications</strong><span>We will show updates here when they arrive.</span></div>'
                        + '</div>';
                }

                const items = notifications.map(function (notification) {
                    const statusClass = notification.read_at ? 'edu-notification-status--read' : 'edu-notification-status--unread';
                    const statusLabel = notification.read_at ? 'Read' : 'Unread';

                    return '<a class="edu-mobile-notification-item" href="' + escapeHtml(notification.open_url) + '">'
                        + '<div class="edu-mobile-notification-item__top">'
                        + '<div class="edu-mobile-notification-item__icon"><i class="' + escapeHtml(notification.icon && notification.icon.trim() ? notification.icon : 'far fa-bell') + '"></i></div>'
                        + '<div class="edu-mobile-notification-item__content"><strong>' + escapeHtml(notification.title) + '</strong><span>' + escapeHtml(notification.description || 'No extra details provided.') + '</span></div>'
                        + '</div>'
                        + '<div class="edu-mobile-notification-item__meta"><span class="edu-notification-status ' + statusClass + '">' + statusLabel + '</span><span class="text-muted small">' + escapeHtml(notification.created_at || '') + '</span></div>'
                        + '</a>';
                }).join('');

                return '<div class="tab-pane fade ' + (groupKey === 'all' ? 'show active' : '') + '" id="mobile-notification-' + groupKey + '" role="tabpanel">'
                    + '<div class="edu-mobile-notification-list">' + items + '</div></div>';
            };

            const renderNotificationPanels = function (notifications) {
                const groups = {
                    all: notifications,
                    unread: notifications.filter(function (notification) {
                        return !notification.read_at;
                    }),
                    read: notifications.filter(function (notification) {
                        return !!notification.read_at;
                    }),
                };

                if (desktopNotificationTabContent) {
                    desktopNotificationTabContent.innerHTML = Object.keys(groups).map(function (groupKey) {
                        return buildDesktopNotificationPanel(groupKey, groups[groupKey]);
                    }).join('');
                }

                if (mobileNotificationTabContent) {
                    mobileNotificationTabContent.innerHTML = Object.keys(groups).map(function (groupKey) {
                        return buildMobileNotificationPanel(groupKey, groups[groupKey]);
                    }).join('');
                }
            };

            const setPushToggleLabel = function () {
                if (!browserNotificationToggles.length) {
                    return;
                }

                const isSupported = 'serviceWorker' in navigator && 'PushManager' in window && 'Notification' in window;
                const setStatus = function (message) {
                    browserNotificationStatuses.forEach(function (statusNode) {
                        statusNode.textContent = message;
                    });
                };
                const setToggleState = function (label, disabled) {
                    browserNotificationToggles.forEach(function (toggleNode) {
                        toggleNode.disabled = !!disabled;

                        const strongNode = toggleNode.querySelector('strong');
                        if (strongNode) {
                            strongNode.textContent = label;
                        } else {
                            toggleNode.textContent = label;
                        }
                    });
                };

                if (!webPushPublicKey) {
                    setToggleState('Push Alerts Unavailable', true);
                    setStatus('Push notifications are not configured on the server yet.');
                    return;
                }

                if (!isSupported) {
                    setToggleState('Browser Push Unsupported', true);
                    setStatus('This browser does not support Service Worker or PushManager notifications.');
                    return;
                }

                if (Notification.permission === 'denied') {
                    setToggleState('Notifications Blocked', true);
                    setStatus('Notification permission is blocked in this browser. Please enable it from browser settings.');
                    return;
                }

                setToggleState(browserNotificationsEnabled
                    ? 'Notifications Enabled'
                    : 'Enable Browser Notifications', false);
                setStatus(browserNotificationsEnabled
                        ? 'Browser push notifications are active for this account.'
                        : 'Click the button to allow browser push notifications for this account.');
            };

            const syncNotificationsFeed = async function () {
                try {
                    const response = await window.axios.get('{{ route('notifications.feed') }}');
                    const payload = response.data || {};
                    const notifications = payload.notifications || [];

                    unreadBrowserNotifications = notifications.filter(function (notificationItem) {
                        return !notificationItem.read_at;
                    });

                    renderNotificationPanels(notifications);
                    renderUnreadCount(Number(payload.unread_count || 0));
                } catch (error) {
                    console.error('Unable to refresh notifications feed.', error);
                }
            };

            const urlBase64ToUint8Array = function (base64String) {
                const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
                const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
                const rawData = window.atob(base64);
                const outputArray = new Uint8Array(rawData.length);

                for (let i = 0; i < rawData.length; ++i) {
                    outputArray[i] = rawData.charCodeAt(i);
                }

                return outputArray;
            };

            const shouldShowPushPermissionPrompt = function () {
                if (!canPersistPushSubscription || !authUserId || !window.Swal || !webPushPublicKey) {
                    return false;
                }

                if (!('serviceWorker' in navigator) || !('PushManager' in window) || !('Notification' in window)) {
                    return false;
                }

                if (Notification.permission !== 'default' || browserNotificationsEnabled) {
                    return false;
                }
                
                return true;
            };

            const unsubscribeFromPush = async function (subscription) {
                if (subscription) {
                    await subscription.unsubscribe();

                    await window.axios.delete('{{ route('push-subscriptions.destroy') }}', {
                        data: {
                            endpoint: subscription.endpoint,
                        },
                    });
                } else {
                    await window.axios.post('{{ route('notifications.browser-preference') }}', {
                        enabled: false,
                    });
                }

                browserNotificationsEnabled = false;
                setPushToggleLabel();
            };

            const registerPushSubscriptionWithServer = async function (subscription) {
                if (!canPersistPushSubscription) {
                    return;
                }

                await window.axios.post('{{ route('push-subscriptions.store') }}', {
                    endpoint: subscription.endpoint,
                    publicKey: subscription.toJSON().keys ? subscription.toJSON().keys.p256dh : null,
                    authToken: subscription.toJSON().keys ? subscription.toJSON().keys.auth : null,
                    contentEncoding: subscription.options && subscription.options.applicationServerKey ? 'aes128gcm' : 'aesgcm',
                });

                browserNotificationsEnabled = true;
                setPushToggleLabel();
            };

            const ensureAutoPushSubscription = async function () {
                if (!webPushPublicKey || !('serviceWorker' in navigator) || !('PushManager' in window) || !('Notification' in window)) {
                    return;
                }

                if (Notification.permission !== 'granted') {
                    return;
                }

                try {
                    const registration = await (window.vgltuPushRegistration || navigator.serviceWorker.ready);
                    const existingSubscription = await registration.pushManager.getSubscription();
                    const subscription = existingSubscription || await registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: urlBase64ToUint8Array(webPushPublicKey),
                    });

                    if (canPersistPushSubscription && (!browserNotificationsEnabled || !existingSubscription)) {
                        await registerPushSubscriptionWithServer(subscription);
                    }
                } catch (error) {
                    console.error('Unable to auto-enable push subscription.', error);
                }
            };

            const promptForPushPermissionAfterRegistration = async function () {
                if (!webPushPublicKey || !('serviceWorker' in navigator) || !('PushManager' in window) || !('Notification' in window)) {
                    return;
                }

                if (Notification.permission !== 'default') {
                    await ensureAutoPushSubscription();
                    return;
                }

                try {
                    const permission = await Notification.requestPermission();

                    if (permission !== 'granted') {
                        return;
                    }

                    const registration = await (window.vgltuPushRegistration || navigator.serviceWorker.ready);
                    const existingSubscription = await registration.pushManager.getSubscription();

                    if (!existingSubscription) {
                        await registration.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: urlBase64ToUint8Array(webPushPublicKey),
                        });
                    }
                } catch (error) {
                    console.error('Unable to request push permission after registration.', error);
                }
            };

            const showPushPermissionPrompt = async function () {
                if (!shouldShowPushPermissionPrompt()) {
                    return;
                }

                const result = await window.Swal.fire({
                    title: 'Enable Browser Notifications?',
                    text: 'Get important updates, reminders, and alerts directly from the browser.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Enable Now',
                    cancelButtonText: 'Skip for This Time',
                    confirmButtonColor: '#bb3e71',
                    cancelButtonColor: '#6c757d',
                });

                if (!result.isConfirmed) {
                    return;
                }

                try {
                    const permission = Notification.permission === 'granted'
                        ? 'granted'
                        : await Notification.requestPermission();

                    if (permission !== 'granted') {
                        if (permission === 'denied') {
                            await window.Swal.fire({
                                title: 'Notifications Blocked',
                                text: 'Please allow notifications from your browser settings if you want to receive alerts later.',
                                icon: 'warning',
                                confirmButtonColor: '#bb3e71',
                            });
                        }
                        return;
                    }

                    await ensureAutoPushSubscription();
                } catch (error) {
                    console.error('Unable to show browser notification prompt.', error);
                }
            };

            const syncPushSubscriptionState = async function () {
                if (!webPushPublicKey || !('serviceWorker' in navigator) || !('PushManager' in window) || !('Notification' in window)) {
                    setPushToggleLabel();
                    return;
                }

                try {
                    const registration = await (window.vgltuPushRegistration || navigator.serviceWorker.ready);
                    const existingSubscription = await registration.pushManager.getSubscription();

                    if (!existingSubscription && browserNotificationsEnabled) {
                        await window.axios.post('{{ route('notifications.browser-preference') }}', {
                            enabled: false,
                        });
                        browserNotificationsEnabled = false;
                    } else if (existingSubscription && Notification.permission === 'granted' && !browserNotificationsEnabled) {
                        await registerPushSubscriptionWithServer(existingSubscription);
                    }

                    setPushToggleLabel();
                } catch (error) {
                    console.error('Unable to sync push subscription state.', error);
                    setPushToggleLabel();
                }
            };

            if (browserNotificationToggles.length) {
                setPushToggleLabel();

                browserNotificationToggles.forEach(function (toggleNode) {
                    toggleNode.addEventListener('click', async function () {
                        if (!webPushPublicKey) {
                            window.alert('Web push is not configured yet on the server.');
                            return;
                        }

                        if (!('serviceWorker' in navigator) || !('PushManager' in window) || !('Notification' in window)) {
                            window.alert('This browser does not support push notifications.');
                            return;
                        }

                        browserNotificationToggles.forEach(function (node) {
                            node.disabled = true;
                        });

                        try {
                            const registration = await (window.vgltuPushRegistration || navigator.serviceWorker.ready);
                            const existingSubscription = await registration.pushManager.getSubscription();

                            if (existingSubscription && browserNotificationsEnabled) {
                                await unsubscribeFromPush(existingSubscription);
                                return;
                            }

                            const permission = Notification.permission === 'granted'
                                ? 'granted'
                                : await Notification.requestPermission();

                            if (permission !== 'granted') {
                                setPushToggleLabel();
                                window.alert(permission === 'denied'
                                    ? 'Notification permission is blocked. Please enable it from your browser settings.'
                                    : 'Push notification permission was not granted.');
                                return;
                            }

                            const subscription = existingSubscription || await registration.pushManager.subscribe({
                                userVisibleOnly: true,
                                applicationServerKey: urlBase64ToUint8Array(webPushPublicKey),
                            });

                            await registerPushSubscriptionWithServer(subscription);
                        } catch (error) {
                            console.error('Unable to update push subscription.', error);
                            const responseMessage = error && error.response && error.response.data && error.response.data.message
                                ? error.response.data.message
                                : 'Unable to update push notifications right now. Check your VAPID keys, HTTPS, and service worker setup.';
                            window.alert(responseMessage);
                        } finally {
                            browserNotificationToggles.forEach(function (node) {
                                node.disabled = false;
                            });
                        }
                    });
                });
            }

            if (markAllNotificationsRead) {
                markAllNotificationsRead.addEventListener('click', async function () {
                    await window.axios.post('{{ route('notifications.read-all') }}');
                    await syncNotificationsFeed();
                });
            }

            if (mobileMarkAllNotificationsRead) {
                mobileMarkAllNotificationsRead.addEventListener('click', async function () {
                    await window.axios.post('{{ route('notifications.read-all') }}');
                    await syncNotificationsFeed();
                });
            }

            document.addEventListener('click', function (event) {
                const row = event.target.closest('.edu-notification-row');
                if (!row) {
                    return;
                }

                const openUrl = row.getAttribute('data-open-url');
                if (!openUrl) {
                    return;
                }

                window.location.href = openUrl;
            });

            mobileDrawerLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    const href = this.getAttribute('href');

                    if (!href || href === '#' || href.startsWith('javascript:') || event.defaultPrevented) {
                        return;
                    }

                    if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || this.hasAttribute('download')) {
                        return;
                    }

                    const target = this.getAttribute('target');
                    const offcanvasElement = this.closest('.offcanvas');
                    const offcanvasInstance = offcanvasElement && window.bootstrap
                        ? window.bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement)
                        : null;

                    if (target === '_blank') {
                        event.preventDefault();
                        window.open(this.href, '_blank', 'noopener');
                        if (offcanvasInstance) {
                            offcanvasInstance.hide();
                        }
                        return;
                    }

                    if (!offcanvasInstance) {
                        return;
                    }

                    event.preventDefault();

                    let hasNavigated = false;
                    const navigate = () => {
                        if (hasNavigated) {
                            return;
                        }

                        hasNavigated = true;
                        window.location.href = this.href;
                    };

                    offcanvasElement.addEventListener('hidden.bs.offcanvas', navigate, { once: true });
                    offcanvasInstance.hide();
                    window.setTimeout(navigate, 250);
                });
            });

            window.setInterval(syncNotificationsFeed, 15000);
            syncNotificationsFeed();
            ensureAutoPushSubscription();
            syncPushSubscriptionState();

            @if (old('login_modal'))
                const loginModalElement = document.getElementById('loginModal');
                if (loginModalElement && window.bootstrap) {
                    window.bootstrap.Modal.getOrCreateInstance(loginModalElement).show();
                }
            @endif

            @if (session('login_success'))
                if (window.Swal) {
                    window.Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: @json(session('login_success')),
                        confirmButtonColor: '#bb3e71'
                    }).then(function () {
                        return showPushPermissionPrompt();
                    });
                } else {
                    showPushPermissionPrompt();
                }
            @endif

            @if (session('registration_success'))
                if (window.Swal) {
                    window.Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful',
                        text: @json(session('registration_success')),
                        confirmButtonColor: '#bb3e71'
                    }).then(function () {
                        return promptForPushPermissionAfterRegistration();
                    });
                } else {
                    promptForPushPermissionAfterRegistration();
                }
            @endif

            @auth
                if (!@json((bool) session('login_success')) && !@json((bool) session('registration_success'))) {
                    window.setTimeout(function () {
                        showPushPermissionPrompt();
                    }, 900);
                }
            @endauth

            @if (old('login_modal') && ($errors->has('email') || $errors->has('password')))
                if (window.Swal) {
                    window.Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: @json($errors->first('email') ?: $errors->first('password')),
                        confirmButtonColor: '#bb3e71'
                    });
                }
            @endif
        });
    </script>
</body>
</html>
