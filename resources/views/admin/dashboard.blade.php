@extends('layouts.admin_app')

@section('content')
<style>
    .dashboard-page {
        display: grid;
        gap: 24px;
    }

    .dashboard-hero {
        position: relative;
        overflow: hidden;
        padding: 32px;
        border-radius: 28px;
        background:
            radial-gradient(circle at top right, rgba(56, 189, 248, 0.28), transparent 28%),
            linear-gradient(135deg, #0f172a 0%, #172554 48%, #0f766e 100%);
        color: #fff;
        box-shadow: 0 28px 60px rgba(15, 23, 42, 0.18);
    }

    .dashboard-hero::after {
        content: "";
        position: absolute;
        inset: auto -40px -80px auto;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }

    .dashboard-hero h2 {
        color: #fff;
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .dashboard-hero p {
        max-width: 720px;
        margin: 0;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.7;
    }

    .dashboard-grid {
        display: grid;
        gap: 18px;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    }

    .dashboard-stat {
        position: relative;
        overflow: hidden;
        display: block;
        padding: 22px;
        border-radius: 24px;
        color: #fff;
        min-height: 168px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .dashboard-stat:hover {
        transform: translateY(-4px);
        box-shadow: 0 26px 42px rgba(15, 23, 42, 0.18);
        color: #fff;
    }

    .dashboard-stat::before {
        content: "";
        position: absolute;
        inset: auto -28px -34px auto;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.12);
    }

    .dashboard-stat__icon {
        width: 52px;
        height: 52px;
        display: grid;
        place-items: center;
        margin-bottom: 18px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.16);
        font-size: 1.15rem;
    }

    .dashboard-stat__label {
        display: block;
        margin-bottom: 8px;
        font-size: 0.95rem;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.82);
    }

    .dashboard-stat__value {
        display: block;
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
    }

    .dashboard-stat__hint {
        display: block;
        margin-top: 12px;
        font-size: 0.83rem;
        color: rgba(255, 255, 255, 0.76);
    }

    .dashboard-panel {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 28px;
        padding: 26px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
    }

    .dashboard-panel__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
    }

    .dashboard-panel__header h3 {
        margin: 0;
        font-size: 1.2rem;
    }

    .dashboard-panel__header p {
        margin: 6px 0 0;
        color: #64748b;
    }

    .dashboard-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 0.82rem;
        font-weight: 800;
    }

    .dashboard-section-grid {
        display: grid;
        gap: 24px;
    }

    .dashboard-section-grid.two-col {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .tone-gold { background: linear-gradient(135deg, #f59e0b, #ea580c); }
    .tone-blue { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
    .tone-purple { background: linear-gradient(135deg, #7c3aed, #4f46e5); }
    .tone-green { background: linear-gradient(135deg, #059669, #0f766e); }
    .tone-red { background: linear-gradient(135deg, #dc2626, #b91c1c); }
    .tone-slate { background: linear-gradient(135deg, #334155, #0f172a); }
    .tone-sky { background: linear-gradient(135deg, #0284c7, #0369a1); }
    .tone-emerald { background: linear-gradient(135deg, #10b981, #047857); }
    .tone-amber { background: linear-gradient(135deg, #d97706, #b45309); }
    .tone-indigo { background: linear-gradient(135deg, #4f46e5, #312e81); }

    .dashboard-list {
        display: grid;
        gap: 14px;
    }

    .dashboard-list-item {
        display: grid;
        gap: 10px;
        padding: 18px;
        border-radius: 20px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    .dashboard-list-item__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .dashboard-list-item h4 {
        margin: 0 0 6px;
        font-size: 1rem;
    }

    .dashboard-list-item p {
        margin: 0;
        color: #64748b;
    }

    .dashboard-list-item__meta {
        display: grid;
        gap: 10px;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    }

    .dashboard-mini-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .dashboard-mini-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        background: #fff7ed;
        color: #c2410c;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .dashboard-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .dashboard-status-pill.is-approved {
        background: #dcfce7;
        color: #166534;
    }

    .dashboard-status-pill.is-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .dashboard-empty {
        padding: 18px;
        border-radius: 20px;
        background: #f8fafc;
        color: #64748b;
        border: 1px dashed rgba(148, 163, 184, 0.4);
    }

    @media (max-width: 991.98px) {
        .dashboard-section-grid.two-col {
            grid-template-columns: 1fr;
        }

        .dashboard-hero {
            padding: 24px;
        }
    }
</style>

<div class="dashboard-page">
    <section class="dashboard-hero">
        <h2>Admin operations at a glance</h2>
        <p>Track student records, pending approvals, medical completion, complaints, and academic distribution from one streamlined dashboard without changing any workflow.</p>
    </section>

    <section class="dashboard-panel">
        <div class="dashboard-panel__header">
            <div>
                <h3>Core Metrics</h3>
                <p>High-priority numbers your team checks most often.</p>
            </div>
            <span class="dashboard-badge"><i class="fas fa-chart-pie"></i> Live Overview</span>
        </div>

        <div class="dashboard-grid">
            <a href="{{ route('admin.users.list', ['category' => 'total']) }}" class="dashboard-stat tone-gold">
                <span class="dashboard-stat__icon"><i class="fas fa-users"></i></span>
                <span class="dashboard-stat__label">Total Students</span>
                <span class="dashboard-stat__value">{{ $totalStudents }}</span>
                <span class="dashboard-stat__hint">View full student dataset</span>
            </a>

            <a href="{{ route('admin.users.list', ['category' => 'gender', 'value' => 'Male']) }}" class="dashboard-stat tone-sky">
                <span class="dashboard-stat__icon"><i class="fas fa-person"></i></span>
                <span class="dashboard-stat__label">Male Students</span>
                <span class="dashboard-stat__value">{{ $maleStudents }}</span>
                <span class="dashboard-stat__hint">Open male student list</span>
            </a>

            <a href="{{ route('admin.users.list', ['category' => 'gender', 'value' => 'Female']) }}" class="dashboard-stat tone-emerald">
                <span class="dashboard-stat__icon"><i class="fas fa-person-dress"></i></span>
                <span class="dashboard-stat__label">Female Students</span>
                <span class="dashboard-stat__value">{{ $femaleStudents }}</span>
                <span class="dashboard-stat__hint">Open female student list</span>
            </a>

            <a href="{{ route('admin.studentlist') }}" class="dashboard-stat tone-blue">
                <span class="dashboard-stat__icon"><i class="fas fa-list-check"></i></span>
                <span class="dashboard-stat__label">Students List</span>
                <span class="dashboard-stat__value">{{ $totalStudentsList }}</span>
                <span class="dashboard-stat__hint">Detailed list view</span>
            </a>

            <a href="{{ route('admin.viewPendingUsers') }}" class="dashboard-stat tone-purple">
                <span class="dashboard-stat__icon"><i class="fas fa-user-clock"></i></span>
                <span class="dashboard-stat__label">Pending Students</span>
                <span class="dashboard-stat__value">{{ $totalpendingstudent }}</span>
                <span class="dashboard-stat__hint">Needs approval action</span>
            </a>

            <a href="{{ route('admin.studentlistmedical') }}" class="dashboard-stat tone-green">
                <span class="dashboard-stat__icon"><i class="fas fa-heart-pulse"></i></span>
                <span class="dashboard-stat__label">Medical Pending</span>
                <span class="dashboard-stat__value">{{ $notCompleteCount }}</span>
                <span class="dashboard-stat__hint">Medical records incomplete</span>
            </a>

            <a href="{{ url('admin/complaints') }}" class="dashboard-stat tone-red">
                <span class="dashboard-stat__icon"><i class="fas fa-triangle-exclamation"></i></span>
                <span class="dashboard-stat__label">Complaints</span>
                <span class="dashboard-stat__value">{{ $pendingComplaintsCount }}</span>
                <span class="dashboard-stat__hint">Open complaint queue</span>
            </a>

            <a href="{{ route('admin.contact-messages.index') }}" class="dashboard-stat tone-sky">
                <span class="dashboard-stat__icon"><i class="fas fa-envelope-open-text"></i></span>
                <span class="dashboard-stat__label">Contact Messages</span>
                <span class="dashboard-stat__value">{{ $unreadContactMessagesCount }}</span>
                <span class="dashboard-stat__hint">Unread website inbox</span>
            </a>

            <a href="{{ route('admin.audit.duplicate-users') }}" class="dashboard-stat tone-amber">
                <span class="dashboard-stat__icon"><i class="fas fa-copy"></i></span>
                <span class="dashboard-stat__label">Duplicate Users</span>
                <span class="dashboard-stat__value">{{ $duplicateUsersCount }}</span>
                <span class="dashboard-stat__hint">Matched by name or passport number</span>
            </a>

            <a href="{{ route('admin.audit.recent-users') }}" class="dashboard-stat tone-indigo">
                <span class="dashboard-stat__icon"><i class="fas fa-user-plus"></i></span>
                <span class="dashboard-stat__label">Last 7 Days</span>
                <span class="dashboard-stat__value">{{ $recentUsersCount }}</span>
                <span class="dashboard-stat__hint">New user registrations</span>
            </a>
        </div>
    </section>

    <div class="dashboard-section-grid two-col">
        <section class="dashboard-panel">
            <div class="dashboard-panel__header">
                <div>
                    <h3>Students by Country</h3>
                    <p>Country-level student distribution.</p>
                </div>
                <span class="dashboard-badge"><i class="fas fa-earth-asia"></i> Geography</span>
            </div>

            <div class="dashboard-grid">
                <a href="{{ route('admin.users.list', ['category' => 'country', 'value' => 'Bangladesh']) }}" class="dashboard-stat tone-red">
                    <span class="dashboard-stat__icon"><span class="flag-icon flag-icon-bd"></span></span>
                    <span class="dashboard-stat__label">Bangladesh</span>
                    <span class="dashboard-stat__value">{{ $totalBangladeshiStudents }}</span>
                    <span class="dashboard-stat__hint">Open country list</span>
                </a>

                <a href="{{ route('admin.users.list', ['category' => 'country', 'value' => 'India']) }}" class="dashboard-stat tone-purple">
                    <span class="dashboard-stat__icon"><span class="flag-icon flag-icon-in"></span></span>
                    <span class="dashboard-stat__label">India</span>
                    <span class="dashboard-stat__value">{{ $totalIndianStudents }}</span>
                    <span class="dashboard-stat__hint">Open country list</span>
                </a>

                <a href="{{ route('admin.users.list', ['category' => 'country', 'value' => 'Nepal']) }}" class="dashboard-stat tone-gold">
                    <span class="dashboard-stat__icon"><span class="flag-icon flag-icon-np"></span></span>
                    <span class="dashboard-stat__label">Nepal</span>
                    <span class="dashboard-stat__value">{{ $totalNepaliStudents }}</span>
                    <span class="dashboard-stat__hint">Open country list</span>
                </a>
            </div>
        </section>

        <section class="dashboard-panel">
            <div class="dashboard-panel__header">
                <div>
                    <h3>Students by Religion</h3>
                    <p>Quick access to faith-based distribution.</p>
                </div>
                <span class="dashboard-badge"><i class="fas fa-hands-praying"></i> Community</span>
            </div>

            <div class="dashboard-grid">
                <a href="{{ route('admin.users.list', ['category' => 'religion', 'value' => 'Muslim']) }}" class="dashboard-stat tone-green">
                    <span class="dashboard-stat__icon"><i class="fas fa-mosque"></i></span>
                    <span class="dashboard-stat__label">Muslim</span>
                    <span class="dashboard-stat__value">{{ $muslimStudents }}</span>
                    <span class="dashboard-stat__hint">View filtered records</span>
                </a>

                <a href="{{ route('admin.users.list', ['category' => 'religion', 'value' => 'Hindu']) }}" class="dashboard-stat tone-blue">
                    <span class="dashboard-stat__icon"><i class="fas fa-om"></i></span>
                    <span class="dashboard-stat__label">Hindu</span>
                    <span class="dashboard-stat__value">{{ $hinduStudents }}</span>
                    <span class="dashboard-stat__hint">View filtered records</span>
                </a>

                <a href="{{ route('admin.users.list', ['category' => 'religion', 'value' => 'Boddho']) }}" class="dashboard-stat tone-slate">
                    <span class="dashboard-stat__icon"><i class="fas fa-dharmachakra"></i></span>
                    <span class="dashboard-stat__label">Boddho</span>
                    <span class="dashboard-stat__value">{{ $boddhoStudents }}</span>
                    <span class="dashboard-stat__hint">View filtered records</span>
                </a>

                <a href="{{ route('admin.users.list', ['category' => 'religion', 'value' => 'Cristan']) }}" class="dashboard-stat tone-purple">
                    <span class="dashboard-stat__icon"><i class="fas fa-church"></i></span>
                    <span class="dashboard-stat__label">Christian</span>
                    <span class="dashboard-stat__value">{{ $cristanStudents }}</span>
                    <span class="dashboard-stat__hint">View filtered records</span>
                </a>
            </div>
        </section>
    </div>

    <section class="dashboard-panel">
        <div class="dashboard-panel__header">
            <div>
                <h3>Students by Department</h3>
                <p>Department-wise enrollment overview, including additional departments.</p>
            </div>
            <span class="dashboard-badge"><i class="fas fa-building"></i> Academics</span>
        </div>

        <div class="dashboard-grid">
            <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Prepetory Language Course']) }}" class="dashboard-stat tone-gold">
                <span class="dashboard-stat__icon"><i class="fas fa-language"></i></span>
                <span class="dashboard-stat__label">Language</span>
                <span class="dashboard-stat__value">{{ $language }}</span>
                <span class="dashboard-stat__hint">Preparatory language course</span>
            </a>

            <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Automobile']) }}" class="dashboard-stat tone-green">
                <span class="dashboard-stat__icon"><i class="fas fa-car"></i></span>
                <span class="dashboard-stat__label">Automobile</span>
                <span class="dashboard-stat__value">{{ $automobileStudents }}</span>
                <span class="dashboard-stat__hint">View department list</span>
            </a>

            <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Forestry']) }}" class="dashboard-stat tone-blue">
                <span class="dashboard-stat__icon"><i class="fas fa-tree"></i></span>
                <span class="dashboard-stat__label">Forestry</span>
                <span class="dashboard-stat__value">{{ $forestryStudents }}</span>
                <span class="dashboard-stat__hint">View department list</span>
            </a>

            <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Mechanical']) }}" class="dashboard-stat tone-red">
                <span class="dashboard-stat__icon"><i class="fas fa-gears"></i></span>
                <span class="dashboard-stat__label">Mechanical</span>
                <span class="dashboard-stat__value">{{ $mechanicalStudents }}</span>
                <span class="dashboard-stat__hint">View department list</span>
            </a>

            <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Computer Science and Technology']) }}" class="dashboard-stat tone-purple">
                <span class="dashboard-stat__icon"><i class="fas fa-laptop-code"></i></span>
                <span class="dashboard-stat__label">CSE - (IT)</span>
                <span class="dashboard-stat__value">{{ $cstStudents }}</span>
                <span class="dashboard-stat__hint">View department list</span>
            </a>

            <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => 'Economics']) }}" class="dashboard-stat tone-sky">
                <span class="dashboard-stat__icon"><i class="fas fa-chart-line"></i></span>
                <span class="dashboard-stat__label">Economics</span>
                <span class="dashboard-stat__value">{{ $economicsStudents }}</span>
                <span class="dashboard-stat__hint">View department list</span>
            </a>

            @foreach ($otherDepartments as $other)
                <a href="{{ route('admin.users.list', ['category' => 'department', 'value' => $other->department]) }}" class="dashboard-stat tone-emerald">
                    <span class="dashboard-stat__icon"><i class="fas fa-graduation-cap"></i></span>
                    <span class="dashboard-stat__label">{{ $other->department }}</span>
                    <span class="dashboard-stat__value">{{ $other->count }}</span>
                    <span class="dashboard-stat__hint">View department list</span>
                </a>
            @endforeach
        </div>
    </section>

    <div class="dashboard-section-grid two-col">
        <section class="dashboard-panel">
            <div class="dashboard-panel__header">
                <div>
                    <h3>Students by Course Language</h3>
                    <p>Language preference across active enrollments.</p>
                </div>
                <span class="dashboard-badge"><i class="fas fa-comments"></i> Language</span>
            </div>

            <div class="dashboard-grid">
                <a href="{{ route('admin.users.course_language', ['course_language' => 'English']) }}" class="dashboard-stat tone-blue">
                    <span class="dashboard-stat__icon"><span class="flag-icon flag-icon-us"></span></span>
                    <span class="dashboard-stat__label">English</span>
                    <span class="dashboard-stat__value">{{ $englishStudents }}</span>
                    <span class="dashboard-stat__hint">View language list</span>
                </a>

                <a href="{{ route('admin.users.course_language', ['course_language' => 'Russian']) }}" class="dashboard-stat tone-red">
                    <span class="dashboard-stat__icon"><span class="flag-icon flag-icon-ru"></span></span>
                    <span class="dashboard-stat__label">Russian</span>
                    <span class="dashboard-stat__value">{{ $russianStudents }}</span>
                    <span class="dashboard-stat__hint">View language list</span>
                </a>
            </div>
        </section>

        <section class="dashboard-panel">
            <div class="dashboard-panel__header">
                <div>
                    <h3>Students by Course Type</h3>
                    <p>Track distribution across degree pathways.</p>
                </div>
                <span class="dashboard-badge"><i class="fas fa-book-open-reader"></i> Programs</span>
            </div>

            <div class="dashboard-grid">
                <a href="{{ route('admin.users.crouse_type_list', ['course_type' => 'Language']) }}" class="dashboard-stat tone-green">
                    <span class="dashboard-stat__icon"><i class="fas fa-book"></i></span>
                    <span class="dashboard-stat__label">Language</span>
                    <span class="dashboard-stat__value">{{ $languageStudents }}</span>
                    <span class="dashboard-stat__hint">View course type list</span>
                </a>

                <a href="{{ route('admin.users.crouse_type_list', ['course_type' => 'BSC']) }}" class="dashboard-stat tone-blue">
                    <span class="dashboard-stat__icon"><i class="fas fa-graduation-cap"></i></span>
                    <span class="dashboard-stat__label">BSC</span>
                    <span class="dashboard-stat__value">{{ $bscStudents }}</span>
                    <span class="dashboard-stat__hint">View course type list</span>
                </a>

                <a href="{{ route('admin.users.crouse_type_list', ['course_type' => 'MSC']) }}" class="dashboard-stat tone-purple">
                    <span class="dashboard-stat__icon"><i class="fas fa-user-graduate"></i></span>
                    <span class="dashboard-stat__label">MSC</span>
                    <span class="dashboard-stat__value">{{ $mscStudents }}</span>
                    <span class="dashboard-stat__hint">View course type list</span>
                </a>

                <a href="{{ route('admin.users.crouse_type_list', ['course_type' => 'PHD']) }}" class="dashboard-stat tone-slate">
                    <span class="dashboard-stat__icon"><i class="fas fa-award"></i></span>
                    <span class="dashboard-stat__label">PHD</span>
                    <span class="dashboard-stat__value">{{ $phdStudents }}</span>
                    <span class="dashboard-stat__hint">View course type list</span>
                </a>
            </div>
        </section>
    </div>
</div>
@endsection
