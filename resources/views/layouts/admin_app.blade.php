<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VGLTU - Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icon-css/css/flag-icon.min.css">
    <link rel="icon" href="https://vgltu.ru/templates/default/images/logo_en.png" type="image/x-icon">
    <style>
        :root {
            --admin-bg: #f3f7fb;
            --admin-surface: rgba(255, 255, 255, 0.82);
            --admin-surface-strong: #ffffff;
            --admin-sidebar: linear-gradient(180deg, #0f172a 0%, #172554 50%, #1d4ed8 100%);
            --admin-text: #122033;
            --admin-muted: #66758a;
            --admin-border: rgba(148, 163, 184, 0.24);
            --admin-primary: #2563eb;
            --admin-primary-soft: rgba(37, 99, 235, 0.14);
            --admin-success: #059669;
            --admin-warning: #d97706;
            --admin-danger: #dc2626;
            --admin-shadow: 0 24px 60px rgba(15, 23, 42, 0.10);
            --admin-radius: 24px;
            --sidebar-width: 310px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: var(--admin-text);
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.18), transparent 30%),
                radial-gradient(circle at right center, rgba(16, 185, 129, 0.12), transparent 24%),
                linear-gradient(180deg, #eef4fb 0%, #f8fbff 100%);
        }

        a {
            text-decoration: none;
        }

        .admin-shell {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        .admin-sidebar {
            width: var(--sidebar-width);
            background: var(--admin-sidebar);
            color: #e2e8f0;
            padding: 24px 18px;
            position: fixed;
            inset: 0 auto 0 0;
            overflow-y: auto;
            z-index: 1040;
            box-shadow: 24px 0 60px rgba(15, 23, 42, 0.18);
        }

        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.18);
            border-radius: 999px;
        }

        .brand-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 22px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.16), rgba(255, 255, 255, 0.06));
            backdrop-filter: blur(14px);
        }

        .brand-mark {
            width: 50px;
            height: 50px;
            display: grid;
            place-items: center;
            border-radius: 16px;
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            color: #fff;
            font-size: 1.1rem;
            box-shadow: 0 10px 26px rgba(37, 99, 235, 0.34);
        }

        .brand-copy small {
            display: block;
            color: rgba(226, 232, 240, 0.72);
            font-size: 0.72rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .brand-copy strong {
            display: block;
            color: #fff;
            font-size: 1.08rem;
            font-weight: 800;
        }

        .sidebar-search {
            position: relative;
            margin-bottom: 22px;
        }

        .sidebar-search i {
            position: absolute;
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            color: rgba(226, 232, 240, 0.6);
            font-size: 0.9rem;
        }

        .sidebar-search input {
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 14px;
            background: rgba(15, 23, 42, 0.24);
            color: #fff;
            padding: 12px 14px 12px 38px;
            outline: none;
        }

        .sidebar-search input::placeholder {
            color: rgba(226, 232, 240, 0.55);
        }

        .sidebar-section {
            margin-bottom: 18px;
        }

        .sidebar-label {
            display: block;
            margin: 0 10px 8px;
            color: rgba(226, 232, 240, 0.62);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .nav-link-item,
        .nav-group-toggle {
            width: 100%;
            border: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 14px;
            margin: 4px 0;
            color: #e2e8f0;
            background: transparent;
            border-radius: 16px;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .nav-link-item:hover,
        .nav-group-toggle:hover,
        .nav-link-item.is-active,
        .nav-group.is-open .nav-group-toggle {
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            transform: translateX(3px);
        }

        .nav-link-content,
        .nav-group-title {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .nav-icon {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .nav-group-toggle .chevron {
            transition: transform 0.2s ease;
            font-size: 0.78rem;
            color: rgba(226, 232, 240, 0.72);
        }

        .nav-group.is-open .chevron {
            transform: rotate(180deg);
        }

        .nav-submenu {
            display: none;
            padding: 4px 0 8px 16px;
        }

        .nav-group.is-open .nav-submenu {
            display: block;
        }

        .nav-submenu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            margin: 2px 0;
            border-radius: 12px;
            color: rgba(226, 232, 240, 0.82);
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .nav-submenu a:hover,
        .nav-submenu a.is-active {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-footer {
            margin-top: 24px;
            padding: 16px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.14);
        }

        .sidebar-footer p {
            margin: 0;
            color: rgba(226, 232, 240, 0.78);
            font-size: 0.86rem;
            line-height: 1.6;
        }

        .admin-main {
            flex: 1;
            min-width: 0;
            margin-left: var(--sidebar-width);
            padding: 24px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 20px;
            margin-bottom: 22px;
            border: 1px solid rgba(255, 255, 255, 0.55);
            border-radius: var(--admin-radius);
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(20px);
            box-shadow: var(--admin-shadow);
        }

        .topbar-start {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }

        .sidebar-toggle {
            width: 46px;
            height: 46px;
            border: 0;
            display: none;
            place-items: center;
            border-radius: 14px;
            background: linear-gradient(135deg, #1d4ed8, #0f766e);
            color: #fff;
            box-shadow: 0 14px 28px rgba(37, 99, 235, 0.22);
        }

        .topbar-title h1 {
            margin: 0;
            font-size: 1.45rem;
            font-weight: 800;
            color: #10213b;
        }

        .topbar-title p {
            margin: 4px 0 0;
            color: var(--admin-muted);
            font-size: 0.95rem;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 0.88rem;
            font-weight: 700;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 6px rgba(34, 197, 94, 0.16);
        }

        .time-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.04);
            color: #334155;
            font-size: 0.88rem;
            font-weight: 700;
        }

        .profile-trigger {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 8px 10px 8px 8px;
            border: 0;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        .profile-avatar {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border-radius: 14px;
            border: 2px solid rgba(37, 99, 235, 0.12);
        }

        .profile-text {
            text-align: left;
            line-height: 1.2;
        }

        .profile-text strong {
            display: block;
            color: #10213b;
            font-size: 0.92rem;
        }

        .profile-text span {
            color: var(--admin-muted);
            font-size: 0.78rem;
            font-weight: 600;
        }

        .dropdown-menu {
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 18px;
            padding: 10px;
            box-shadow: 0 22px 50px rgba(15, 23, 42, 0.16);
        }

        .dropdown-item {
            border-radius: 12px;
            padding: 10px 12px;
            font-weight: 600;
        }

        .dropdown-item i {
            width: 18px;
        }

        .content-frame {
            padding: 6px 2px 24px;
        }

        .admin-page {
            display: grid;
            gap: 24px;
        }

        .admin-hero-card,
        .admin-panel,
        .admin-data-card,
        .admin-form-shell {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(148, 163, 184, 0.16);
            border-radius: 28px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        .admin-hero-card {
            position: relative;
            overflow: hidden;
            padding: 28px 30px;
            color: #fff;
            background:
                radial-gradient(circle at top right, rgba(56, 189, 248, 0.24), transparent 28%),
                linear-gradient(135deg, #0f172a 0%, #1e3a8a 54%, #0f766e 100%);
        }

        .admin-hero-card h2,
        .admin-hero-card h3,
        .admin-hero-card p,
        .admin-hero-card .text-muted {
            color: #fff !important;
        }

        .admin-hero-card p {
            margin: 8px 0 0;
            color: rgba(255, 255, 255, 0.82) !important;
            max-width: 780px;
        }

        .admin-panel,
        .admin-data-card,
        .admin-form-shell {
            padding: 24px;
        }

        .admin-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .admin-toolbar__title h1,
        .admin-toolbar__title h2,
        .admin-toolbar__title h3,
        .admin-toolbar__title h4 {
            margin: 0;
        }

        .admin-toolbar__title p {
            margin: 6px 0 0;
            color: var(--admin-muted);
        }

        .admin-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 0.86rem;
            font-weight: 800;
        }

        .admin-stat-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        .admin-stat-card {
            padding: 22px;
            border-radius: 24px;
            background: linear-gradient(180deg, #fff, #f8fbff);
            border: 1px solid rgba(148, 163, 184, 0.18);
        }

        .admin-stat-card strong {
            display: block;
            font-size: 1.8rem;
            color: #0f172a;
        }

        .admin-stat-card span {
            color: var(--admin-muted);
            font-weight: 700;
        }

        .admin-grid-2 {
            display: grid;
            gap: 24px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .admin-grid-3 {
            display: grid;
            gap: 24px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .admin-table-wrap {
            overflow-x: auto;
        }

        .admin-media {
            width: 96px;
            height: 96px;
            object-fit: cover;
            border-radius: 22px;
            border: 3px solid rgba(37, 99, 235, 0.08);
            box-shadow: 0 16px 28px rgba(15, 23, 42, 0.10);
        }

        .admin-avatar {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 18px;
        }

        .admin-empty {
            padding: 30px;
            border-radius: 22px;
            text-align: center;
            color: var(--admin-muted);
            background: rgba(248, 250, 252, 0.9);
            border: 1px dashed rgba(148, 163, 184, 0.34);
        }

        .admin-pagination {
            display: flex;
            justify-content: center;
            margin-top: 24px;
        }

        .admin-actions-inline {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .admin-kv {
            display: grid;
            gap: 14px;
        }

        .admin-kv-item {
            padding: 14px 16px;
            border-radius: 18px;
            background: #f8fbff;
            border: 1px solid rgba(148, 163, 184, 0.16);
        }

        .admin-kv-item span {
            display: block;
            margin-bottom: 6px;
            color: var(--admin-muted);
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .admin-kv-item strong {
            color: #0f172a;
            font-size: 0.98rem;
        }

        .content-frame .container,
        .content-frame .container-fluid {
            max-width: 100%;
            padding: 0;
        }

        .card,
        .alert,
        .table-responsive,
        .list-group,
        .modal-content {
            border-radius: 22px;
            border: 1px solid var(--admin-border);
            box-shadow: var(--admin-shadow);
        }

        .card {
            overflow: hidden;
            background: var(--admin-surface-strong);
        }

        .card-header {
            background: linear-gradient(180deg, rgba(248, 250, 252, 0.95), rgba(241, 245, 249, 0.92));
            border-bottom: 1px solid rgba(148, 163, 184, 0.16);
            font-weight: 800;
            color: #10213b;
            padding: 1rem 1.25rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table > :not(caption) > * > * {
            padding: 0.95rem 1rem;
            border-bottom-color: rgba(226, 232, 240, 0.85);
        }

        .table thead th {
            background: #f8fbff;
            color: #475569;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .table tbody tr:hover {
            background: rgba(37, 99, 235, 0.035);
        }

        .btn {
            border-radius: 12px;
            font-weight: 700;
            padding: 0.7rem 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #0f766e);
            border-color: transparent;
            box-shadow: 0 14px 28px rgba(37, 99, 235, 0.18);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: linear-gradient(135deg, #1d4ed8, #0d9488);
            border-color: transparent;
        }

        .btn-outline-primary {
            border-color: rgba(37, 99, 235, 0.3);
            color: #1d4ed8;
        }

        .btn-warning,
        .btn-danger,
        .btn-success,
        .btn-info,
        .btn-secondary {
            border-color: transparent;
        }

        .form-control,
        .form-select {
            min-height: 48px;
            border-radius: 14px;
            border-color: rgba(148, 163, 184, 0.34);
            box-shadow: none;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(37, 99, 235, 0.5);
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.12);
        }

        .input-group > .btn {
            border-radius: 0 14px 14px 0;
        }

        .form-label {
            font-weight: 700;
            color: #334155;
        }

        h1, h2, h3, h4, h5, h6 {
            color: #10213b;
            font-weight: 800;
        }

        .alert {
            padding: 1rem 1.2rem;
        }

        .admin-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            opacity: 0;
            visibility: hidden;
            transition: 0.2s ease;
            z-index: 1030;
        }

        .admin-overlay.is-visible {
            opacity: 1;
            visibility: visible;
        }

        .flag-icon {
            border-radius: 3px;
            box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.12);
        }

        @media (max-width: 1199.98px) {
            :root {
                --sidebar-width: 290px;
            }
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.24s ease;
            }

            .admin-sidebar.is-open {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
                padding: 16px;
            }

            .sidebar-toggle {
                display: inline-grid;
            }

            .topbar {
                padding: 16px;
                border-radius: 22px;
            }
        }

        @media (max-width: 767.98px) {
            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .topbar-actions {
                width: 100%;
                justify-content: space-between;
            }

            .profile-trigger .profile-text {
                display: none;
            }

            .admin-grid-2,
            .admin-grid-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
@php
    use Carbon\Carbon;

    $admin = Auth::guard('admin')->user();
@endphp

<div class="admin-shell">
    <aside class="admin-sidebar" id="adminSidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand-card">
            <div class="brand-mark">
                <i class="fas fa-shield-halved"></i>
            </div>
            <div class="brand-copy">
                <small>VGLTU Portal</small>
                <strong>Admin Control Hub</strong>
            </div>
        </a>

        <div class="sidebar-search">
            <i class="fas fa-search"></i>
            <input type="text" id="sidebarFilter" placeholder="Filter menu items">
        </div>

        <div class="sidebar-section" data-menu-section>
            <span class="sidebar-label">Overview</span>
            <a href="{{ route('admin.dashboard') }}" class="nav-link-item {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" data-menu-item>
                <span class="nav-link-content">
                    <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
                    <span>Dashboard</span>
                </span>
            </a>
            <a href="{{ url('admin/categories') }}" class="nav-link-item {{ request()->is('admin/categories*') ? 'is-active' : '' }}" data-menu-item>
                <span class="nav-link-content">
                    <span class="nav-icon"><i class="fas fa-layer-group"></i></span>
                    <span>Categories</span>
                </span>
            </a>
            <a href="{{ route('admin.smtp.edit') }}" class="nav-link-item {{ request()->routeIs('admin.smtp.*') ? 'is-active' : '' }}" data-menu-item>
                <span class="nav-link-content">
                    <span class="nav-icon"><i class="fas fa-envelope-open-text"></i></span>
                    <span>SMTP Settings</span>
                </span>
            </a>
        </div>

        <div class="sidebar-section" data-menu-section>
            <span class="sidebar-label">Operations</span>

            <div class="nav-group {{ request()->is('admin/users/*') || request()->routeIs('search.*') || request()->routeIs('admin.viewPendingUsers') || request()->routeIs('admin.studentlist') || request()->routeIs('admin.studentlistmedical') || request()->routeIs('admin.studentsdata.*') || request()->is('admin/dashboard/students-by-*') ? 'is-open' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" data-nav-toggle>
                    <span class="nav-group-title">
                        <span class="nav-icon"><i class="fas fa-users-gear"></i></span>
                        <span>User Management</span>
                    </span>
                    <i class="fas fa-chevron-down chevron"></i>
                </button>
                <div class="nav-submenu">
                    <a href="{{ route('search.form') }}" class="{{ request()->routeIs('search.form') || request()->routeIs('search') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-search"></i> Search User</a>
                    <a href="{{ route('admin.users.by-room') }}" class="{{ request()->routeIs('admin.users.by-room') || request()->routeIs('admin.users.by-room.show') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-door-open"></i> User by Room</a>
                    <a href="{{ route('admin.viewPendingUsers') }}" class="{{ request()->routeIs('admin.viewPendingUsers') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-user-clock"></i> Pending Students</a>
                    <a href="{{ route('admin.users.list', ['category' => 'total']) }}" class="{{ request()->is('admin/users/total*') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-users"></i> Total Students</a>
                    <a href="{{ route('admin.studentlist') }}" class="{{ request()->routeIs('admin.studentlist') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-list"></i> Total Students List</a>
                    <a href="{{ route('admin.studentlistmedical') }}" class="{{ request()->routeIs('admin.studentlistmedical') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-heart-pulse"></i> Medical Not Complete</a>
                    <a href="{{ route('admin.studentsdata.index') }}" class="{{ request()->routeIs('admin.studentsdata.*') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-passport"></i> Passport & Visa Details</a>
                    <a href="{{ url('/admin/dashboard/students-by-floor') }}" class="{{ request()->is('admin/dashboard/students-by-*') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-building-user"></i> View Student By Block</a>
                </div>
            </div>

            <div class="nav-group {{ request()->is('admin/sliders*') || request()->is('admin/upload*') || request()->is('admin/students*') || request()->routeIs('admin.videos.*') ? 'is-open' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" data-nav-toggle>
                    <span class="nav-group-title">
                        <span class="nav-icon"><i class="fas fa-photo-film"></i></span>
                        <span>Media & Archive</span>
                    </span>
                    <i class="fas fa-chevron-down chevron"></i>
                </button>
                <div class="nav-submenu">
                    <a href="{{ url('admin/sliders') }}" class="{{ request()->is('admin/sliders*') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-images"></i> Upload Slider</a>
                    <a href="{{ url('admin/upload') }}" class="{{ request()->is('admin/upload') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-upload"></i> Upload Media</a>
                    <a href="{{ url('admin/upload/view') }}" class="{{ request()->is('admin/upload/view*') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-eye"></i> View Upload</a>
                    <a href="{{ url('admin/students') }}" class="{{ request()->is('admin/students*') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-box-archive"></i> Old Student</a>
                </div>
            </div>

            <div class="nav-group {{ request()->is('admin/homepage*') ? 'is-open' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" data-nav-toggle>
                    <span class="nav-group-title">
                        <span class="nav-icon"><i class="fas fa-globe"></i></span>
                        <span>Homepage CMS</span>
                    </span>
                    <i class="fas fa-chevron-down chevron"></i>
                </button>
                <div class="nav-submenu">
                    <a href="{{ route('admin.homepage.settings.edit') }}" class="{{ request()->routeIs('admin.homepage.settings.edit') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-sliders"></i> Website Settings</a>
                    <a href="{{ route('admin.homepage.pages.about-university.edit') }}" class="{{ request()->routeIs('admin.homepage.pages.about-university.*') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-university"></i> Universities</a>
                    <a href="{{ route('admin.homepage.pages.courses.edit') }}" class="{{ request()->routeIs('admin.homepage.pages.courses.*') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-book-open"></i> Course Page</a>
                    <a href="{{ route('admin.homepage.hero.edit') }}" class="{{ request()->routeIs('admin.homepage.hero.edit') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-star"></i> Hero Section</a>
                    {{-- <a href="{{ route('admin.homepage.menus.index') }}" class="{{ request()->routeIs('admin.homepage.menus.*') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-bars"></i> Header Menus</a> --}}
                    {{-- <a href="{{ route('admin.homepage.destinations.index') }}" class="{{ request()->routeIs('admin.homepage.destinations.*') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-location-dot"></i> Study Destinations</a> --}}
                </div>
            </div>

            <div class="nav-group {{ request()->routeIs('admin.user-edit-permissions.*') ? 'is-open' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" data-nav-toggle>
                    <span class="nav-group-title">
                        <span class="nav-icon"><i class="fas fa-user-lock"></i></span>
                        <span>User Edit Access</span>
                    </span>
                    <i class="fas fa-chevron-down chevron"></i>
                </button>
                <div class="nav-submenu">
                    <a href="{{ route('admin.user-edit-permissions.edit') }}" class="{{ request()->routeIs('admin.user-edit-permissions.*') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-sliders"></i> Edit Permissions</a>
                </div>
            </div>

            <div class="nav-group {{ request()->is('admin/custom-fields*') || request()->is('admin/form-submissions*') || request()->is('admin/user-custom-data*') ? 'is-open' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" data-nav-toggle>
                    <span class="nav-group-title">
                        <span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>
                        <span>Custom Fields</span>
                    </span>
                    <i class="fas fa-chevron-down chevron"></i>
                </button>
                <div class="nav-submenu">
                    <a href="{{ url('admin/custom-fields') }}" class="{{ request()->is('admin/custom-fields*') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-screwdriver-wrench"></i> Create Fields</a>
                    <a href="{{ url('/admin/form-submissions') }}" class="{{ request()->is('admin/form-submissions*') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-folder-open"></i> View From User</a>
                    <a href="{{ url('admin/user-custom-data') }}" class="{{ request()->is('admin/user-custom-data') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-database"></i> View Submitted Data</a>
                    <a href="{{ url('admin/user-custom-data/solved') }}" class="{{ request()->is('admin/user-custom-data/solved') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-circle-check"></i> View Solved Data</a>
                </div>
            </div>

            <div class="nav-group {{ request()->routeIs('admin.campaigns.*') ? 'is-open' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" data-nav-toggle>
                    <span class="nav-group-title">
                        <span class="nav-icon"><i class="fas fa-bullhorn"></i></span>
                        <span>Campaigns</span>
                    </span>
                    <i class="fas fa-chevron-down chevron"></i>
                </button>
                <div class="nav-submenu">
                    <a href="{{ route('admin.campaigns.create') }}" class="{{ request()->routeIs('admin.campaigns.create') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-plus"></i> Create Campaign</a>
                    <a href="{{ route('admin.campaigns.index') }}" class="{{ request()->routeIs('admin.campaigns.index') || request()->routeIs('admin.campaigns.edit') || request()->routeIs('admin.campaigns.submissions') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-list"></i> View Campaigns</a>
                </div>
            </div>

            <div class="nav-group {{ request()->routeIs('admin.notifications.*') ? 'is-open' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" data-nav-toggle>
                    <span class="nav-group-title">
                        <span class="nav-icon"><i class="fas fa-bell"></i></span>
                        <span>Notifications</span>
                    </span>
                    <i class="fas fa-chevron-down chevron"></i>
                </button>
                <div class="nav-submenu">
                    <a href="{{ route('admin.notifications.create') }}" class="{{ request()->routeIs('admin.notifications.create') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-paper-plane"></i> Send Notification</a>
                    <a href="{{ route('admin.notifications.index') }}" class="{{ request()->routeIs('admin.notifications.index') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-list-check"></i> Notification List</a>
                </div>
            </div>

            <div class="nav-group {{ request()->routeIs('admin.contact-messages.*') ? 'is-open' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" data-nav-toggle>
                    <span class="nav-group-title">
                        <span class="nav-icon"><i class="fas fa-envelope-open-text"></i></span>
                        <span>Contact Inbox</span>
                    </span>
                    <i class="fas fa-chevron-down chevron"></i>
                </button>
                <div class="nav-submenu">
                    <a href="{{ route('admin.contact-messages.index') }}" class="{{ request()->routeIs('admin.contact-messages.*') ? 'is-active' : '' }}" data-menu-item><i class="fas fa-inbox"></i> View Messages</a>
                </div>
            </div>
        </div>

        <div class="sidebar-section" data-menu-section>
            <span class="sidebar-label">Insights</span>

            <div class="nav-group {{ request()->is('admin/users/department/*') ? 'is-open' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" data-nav-toggle>
                    <span class="nav-group-title">
                        <span class="nav-icon"><i class="fas fa-building"></i></span>
                        <span>Department</span>
                    </span>
                    <i class="fas fa-chevron-down chevron"></i>
                </button>
                <div class="nav-submenu">
                    <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Prepetory Language Course']) }}" data-menu-item><i class="fas fa-language"></i> Language</a>
                    <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Automobile']) }}" data-menu-item><i class="fas fa-car"></i> Automobile</a>
                    <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Forestry']) }}" data-menu-item><i class="fas fa-tree"></i> Forestry</a>
                    <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Mechanical']) }}" data-menu-item><i class="fas fa-gears"></i> Mechanical</a>
                    <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Computer Science and Technology']) }}" data-menu-item><i class="fas fa-laptop-code"></i> CSE - (IT)</a>
                    <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Economics']) }}" data-menu-item><i class="fas fa-chart-simple"></i> Economics</a>
                    <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Landscape Architecture']) }}" data-menu-item><i class="fas fa-leaf"></i> Landscape Architecture</a>
                    <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Tourism']) }}" data-menu-item><i class="fas fa-suitcase-rolling"></i> Tourism</a>
                    <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'automation of production processes']) }}" data-menu-item><i class="fas fa-industry"></i> Automation of Production Processes</a>
                    <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Life Safety and Legal Relations']) }}" data-menu-item><i class="fas fa-scale-balanced"></i> Life Safety and Legal Relations</a>
                    <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Botany and Plant Physiology']) }}" data-menu-item><i class="fas fa-seedling"></i> Botany and Plant Physiology</a>
                    <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Hardware and Software']) }}" data-menu-item><i class="fas fa-microchip"></i> Hardware and Software</a>
                </div>
            </div>

            <div class="nav-group {{ request()->is('admin/users/country/*') ? 'is-open' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" data-nav-toggle>
                    <span class="nav-group-title">
                        <span class="nav-icon"><i class="fas fa-earth-asia"></i></span>
                        <span>Students by Country</span>
                    </span>
                    <i class="fas fa-chevron-down chevron"></i>
                </button>
                <div class="nav-submenu">
                    <a href="{{ route('admin.users.list', ['category' => 'country', 'value' => 'Bangladesh']) }}" data-menu-item><span class="flag-icon flag-icon-bd"></span> Bangladesh</a>
                    <a href="{{ route('admin.users.list', ['category' => 'country', 'value' => 'India']) }}" data-menu-item><span class="flag-icon flag-icon-in"></span> India</a>
                    <a href="{{ route('admin.users.list', ['category' => 'country', 'value' => 'Nepal']) }}" data-menu-item><span class="flag-icon flag-icon-np"></span> Nepal</a>
                </div>
            </div>

            <div class="nav-group {{ request()->is('admin/users/religion/*') ? 'is-open' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" data-nav-toggle>
                    <span class="nav-group-title">
                        <span class="nav-icon"><i class="fas fa-hands-praying"></i></span>
                        <span>Students by Religion</span>
                    </span>
                    <i class="fas fa-chevron-down chevron"></i>
                </button>
                <div class="nav-submenu">
                    <a href="{{ route('admin.users.list', ['category' => 'religion', 'value' => 'Muslim']) }}" data-menu-item><i class="fas fa-mosque"></i> Muslim</a>
                    <a href="{{ route('admin.users.list', ['category' => 'religion', 'value' => 'Hindu']) }}" data-menu-item><i class="fas fa-om"></i> Hindu</a>
                    <a href="{{ route('admin.users.list', ['category' => 'religion', 'value' => 'Boddho']) }}" data-menu-item><i class="fas fa-dharmachakra"></i> Boddho</a>
                    <a href="{{ route('admin.users.list', ['category' => 'religion', 'value' => 'Cristan']) }}" data-menu-item><i class="fas fa-church"></i> Christian</a>
                </div>
            </div>

            <div class="nav-group {{ request()->routeIs('admin.users.course_language') ? 'is-open' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" data-nav-toggle>
                    <span class="nav-group-title">
                        <span class="nav-icon"><i class="fas fa-comments"></i></span>
                        <span>Course Language</span>
                    </span>
                    <i class="fas fa-chevron-down chevron"></i>
                </button>
                <div class="nav-submenu">
                    <a href="{{ route('admin.users.course_language', ['course_language' => 'English']) }}" data-menu-item><span class="flag-icon flag-icon-us"></span> English</a>
                    <a href="{{ route('admin.users.course_language', ['course_language' => 'Russian']) }}" data-menu-item><span class="flag-icon flag-icon-ru"></span> Russian</a>
                </div>
            </div>

            <div class="nav-group {{ request()->routeIs('admin.users.crouse_type_list') ? 'is-open' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" data-nav-toggle>
                    <span class="nav-group-title">
                        <span class="nav-icon"><i class="fas fa-book-open-reader"></i></span>
                        <span>Course Type</span>
                    </span>
                    <i class="fas fa-chevron-down chevron"></i>
                </button>
                <div class="nav-submenu">
                    <a href="{{ route('admin.users.crouse_type_list', ['course_type' => 'Language']) }}" data-menu-item><i class="fas fa-book"></i> Language</a>
                    <a href="{{ route('admin.users.crouse_type_list', ['course_type' => 'BSC']) }}" data-menu-item><i class="fas fa-graduation-cap"></i> BSC</a>
                    <a href="{{ route('admin.users.crouse_type_list', ['course_type' => 'MSC']) }}" data-menu-item><i class="fas fa-user-graduate"></i> MSC</a>
                    <a href="{{ route('admin.users.crouse_type_list', ['course_type' => 'PHD']) }}" data-menu-item><i class="fas fa-award"></i> PHD</a>
                </div>
            </div>
        </div>

        <div class="sidebar-footer">
            <p>Student data, homepage content, campaigns and notifications are now grouped in one clean control panel for faster admin workflows.</p>
        </div>
    </aside>

    <div class="admin-overlay" id="adminOverlay"></div>

    <main class="admin-main">
        <div class="topbar">
            <div class="topbar-start">
                <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-title">
                    <h1>Admin Workspace</h1>
                    <p>Welcome back, {{ $admin->name }}. Manage students, content, and operations from one place.</p>
                </div>
            </div>

            <div class="topbar-actions">
                <div class="status-pill">
                    <span class="status-dot"></span>
                    <span>System Active</span>
                </div>
                <div class="time-chip">
                    <i class="fas fa-clock"></i>
                    <span id="live-time">{{ Carbon::now()->timezone('Europe/Moscow')->format('h:i:s A') }}</span>
                </div>

                <div class="dropdown">
                    <button class="profile-trigger" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img
                            class="profile-avatar"
                            src="{{ $admin && $admin->photo ? asset('storage/' . $admin->photo) : asset('default-avatar.png') }}"
                            alt="{{ $admin->name }} profile photo"
                        >
                        <span class="profile-text">
                            <strong>{{ $admin->name }}</strong>
                            <span>Administrator</span>
                        </span>
                        <i class="fas fa-chevron-down text-muted"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}"><i class="fas fa-user-pen"></i> Edit Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.create') }}"><i class="fas fa-user-plus"></i> Create Admin</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.index') }}"><i class="fas fa-users"></i> Admin List</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-right-from-bracket"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>

        <div class="content-frame">
            <div class="container">
                @yield('content')
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('adminOverlay');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const filterInput = document.getElementById('sidebarFilter');

    function closeSidebar() {
        sidebar.classList.remove('is-open');
        overlay.classList.remove('is-visible');
    }

    function openSidebar() {
        sidebar.classList.add('is-open');
        overlay.classList.add('is-visible');
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            if (sidebar.classList.contains('is-open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    document.querySelectorAll('[data-nav-toggle]').forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            const group = this.closest('[data-nav-group]');
            group.classList.toggle('is-open');
        });
    });

    if (filterInput) {
        filterInput.addEventListener('input', function () {
            const keyword = this.value.trim().toLowerCase();
            document.querySelectorAll('[data-menu-section]').forEach(function (section) {
                let sectionHasMatch = false;

                section.querySelectorAll('[data-nav-group]').forEach(function (group) {
                    const groupText = group.innerText.toLowerCase();
                    const matched = groupText.includes(keyword);
                    group.style.display = matched ? '' : 'none';
                    if (matched && keyword) {
                        group.classList.add('is-open');
                    }
                    sectionHasMatch = sectionHasMatch || matched;
                });

                section.querySelectorAll('[data-menu-item]').forEach(function (item) {
                    if (item.closest('[data-nav-group]')) {
                        return;
                    }

                    const matched = item.innerText.toLowerCase().includes(keyword);
                    item.style.display = matched ? '' : 'none';
                    sectionHasMatch = sectionHasMatch || matched;
                });

                section.style.display = sectionHasMatch || !keyword ? '' : 'none';
            });
        });
    }

    function updateTime() {
        const timeElement = document.getElementById('live-time');
        if (!timeElement) {
            return;
        }

        const currentTime = new Date().toLocaleString('en-US', { timeZone: 'Europe/Moscow' });
        timeElement.textContent = new Date(currentTime).toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
    }

    updateTime();
    setInterval(updateTime, 1000);
</script>
</body>
</html>
