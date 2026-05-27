@extends('layouts.app')

@section('content')
@php($user = $user ?? Auth::user())

<style>
    .dashboard-page {
        width: min(1280px, calc(100% - 32px));
        margin: 34px auto 52px;
    }

    .dashboard-section {
        margin-top: 8px;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 340px minmax(0, 1fr);
        gap: 24px;
    }

    .dashboard-panel {
        background: linear-gradient(180deg, #fffaf7 0%, #ffffff 100%);
        border: 1px solid rgba(35, 23, 38, 0.08);
        border-radius: 28px;
        padding: 28px;
        box-shadow: 0 18px 38px rgba(59, 33, 53, 0.08);
    }

    .dashboard-sidebar-card {
        background: linear-gradient(180deg, #fffaf7 0%, #ffffff 100%);
        border: 1px solid rgba(35, 23, 38, 0.08);
        border-radius: 28px;
        padding: 24px;
        box-shadow: 0 18px 38px rgba(59, 33, 53, 0.08);
    }

    .dashboard-sidebar-card__photo {
        width: 100%;
        max-width: 240px;
        aspect-ratio: 1 / 1;
        margin: 0 auto 18px;
        border-radius: 28px;
        overflow: hidden;
        background: #f6eef1;
        box-shadow: 0 18px 34px rgba(50, 32, 48, 0.12);
    }

    .dashboard-sidebar-card__photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .dashboard-sidebar-card__body {
        text-align: center;
    }

    .dashboard-sidebar-card__body h3 {
        margin: 0;
        color: #241726;
        font-size: 1.35rem;
        font-weight: 800;
        word-break: break-word;
    }

    .dashboard-sidebar-card__subtitle {
        margin-top: 8px;
        color: #6f6572;
        line-height: 1.7;
        font-size: 14px;
        word-break: break-word;
    }

    .dashboard-sidebar-card__list {
        display: grid;
        gap: 12px;
        margin-top: 20px;
        text-align: left;
    }

    .dashboard-sidebar-card__item {
        padding: 14px 16px;
        border-radius: 18px;
        background: #fff;
        border: 1px solid rgba(35, 23, 38, 0.07);
        box-shadow: 0 10px 22px rgba(50, 32, 48, 0.05);
    }

    .dashboard-sidebar-card__item small {
        display: block;
        margin-bottom: 6px;
        color: #bb3e71;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .dashboard-sidebar-card__item strong {
        display: block;
        color: #241726;
        font-size: 0.98rem;
        font-weight: 700;
        line-height: 1.6;
        word-break: break-word;
    }

    .dashboard-sidebar-card__cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 6px;
        padding: 11px 14px;
        border-radius: 14px;
        background: linear-gradient(135deg, #241726, #bb3e71);
        color: #fff;
        font-size: 0.88rem;
        font-weight: 800;
        text-decoration: none;
        box-shadow: 0 14px 26px rgba(76, 42, 65, 0.16);
    }

    .dashboard-sidebar-card__cta:hover {
        color: #fff;
    }

    .dashboard-panel__heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 20px;
    }

    .dashboard-panel__heading h3 {
        margin: 8px 0 0;
        color: #241726;
        font-size: 1.6rem;
        font-weight: 800;
    }

    .dashboard-panel__heading p {
        margin: 8px 0 0;
        color: #6f6572;
        line-height: 1.7;
        font-size: 14px;
    }

    .dashboard-panel__badge {
        display: inline-flex;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(215, 89, 139, 0.12);
        color: #bb3e71;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .dashboard-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .dashboard-info-card {
        padding: 18px;
        border-radius: 22px;
        background: #fff;
        border: 1px solid rgba(35, 23, 38, 0.07);
        box-shadow: 0 12px 26px rgba(50, 32, 48, 0.06);
    }

    .dashboard-info-card small {
        display: block;
        margin-bottom: 8px;
        color: #bb3e71;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .dashboard-info-card strong {
        display: block;
        color: #241726;
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.6;
        word-break: break-word;
    }

    .dashboard-status-panel {
        margin-top: 24px;
    }

    .dashboard-download-wrap {
        margin-top: 26px;
        text-align: center;
    }

    .dashboard-download-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 54px;
        padding: 14px 26px;
        border-radius: 999px;
        background: linear-gradient(135deg, #241726, #bb3e71);
        color: #fff;
        text-decoration: none;
        font-size: 15px;
        font-weight: 800;
        box-shadow: 0 18px 34px rgba(76, 42, 65, 0.18);
        transition: transform 0.22s ease, box-shadow 0.22s ease;
    }

    .dashboard-download-btn:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 22px 38px rgba(76, 42, 65, 0.24);
    }

    .dashboard-status-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .dashboard-status-card {
        padding: 18px;
        border-radius: 22px;
        background: #fff;
        border: 1px solid rgba(35, 23, 38, 0.07);
        box-shadow: 0 12px 26px rgba(50, 32, 48, 0.06);
    }

    .dashboard-status-card small {
        display: block;
        color: #bb3e71;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .dashboard-status-card strong {
        display: block;
        color: #241726;
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.6;
        word-break: break-word;
    }

    @media (max-width: 1199px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 991px) {
        .dashboard-page {
            width: calc(100% - 20px);
        }

        .dashboard-info-grid,
        .dashboard-status-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .dashboard-page {
            width: calc(100% - 16px);
            margin: 20px auto 34px;
        }

        .dashboard-info-grid,
        .dashboard-status-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-panel {
            padding: 20px 16px;
            border-radius: 22px;
        }

        .dashboard-sidebar-card {
            padding: 20px 16px;
            border-radius: 22px;
        }

        .dashboard-panel__heading {
            flex-direction: column;
        }

        .dashboard-panel__heading h3 {
            font-size: 1.4rem;
        }

        .dashboard-status-panel {
            display: none;
        }
    }
</style>

<section class="dashboard-page">
    <div class="dashboard-section dashboard-grid">
        <aside class="dashboard-sidebar-card">
            <div class="dashboard-sidebar-card__photo">
                <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('default-avatar.png') }}" alt="{{ $user->full_name }}">
            </div>
            <div class="dashboard-sidebar-card__body">
                <span class="dashboard-panel__badge">Student Card</span>
                <h3>{{ $user->full_name }}</h3>
                <div class="dashboard-sidebar-card__subtitle">{{ $user->department ?: 'Department not added yet' }}</div>
            </div>

            <div class="dashboard-sidebar-card__list">
                <div class="dashboard-sidebar-card__item">
                    <small>Room Number</small>
                    <strong>{{ $user->room_number ?: 'N/A' }}</strong>
                </div>
                <div class="dashboard-sidebar-card__item">
                    <small>Mobile Number</small>
                    <strong>{{ $user->mobile_number ?: 'N/A' }}</strong>
                </div>
                <div class="dashboard-sidebar-card__item">
                    <small>Email</small>
                    <strong>{{ $user->email ?: 'N/A' }}</strong>
                </div>
                <div class="dashboard-sidebar-card__item">
                    <small>Account Status</small>
                    <strong>{{ $user->approved ? 'Approved' : 'Pending Review' }}</strong>
                </div>
                @if($user->emergencyContacts->isNotEmpty())
                    <div class="dashboard-sidebar-card__item">
                        <small>Emergency Contact</small>
                        <strong>
                            @foreach($user->emergencyContacts as $contact)
                                <div>{{ $contact->platform }} - {{ $contact->contact_value }}</div>
                            @endforeach
                        </strong>
                    </div>
                @else
                    <div class="dashboard-sidebar-card__item">
                        <small>Emergency Contact</small>
                        <strong>No emergency contact added yet.</strong>
                        <a href="{{ route('user.edit') }}" class="dashboard-sidebar-card__cta">
                            <i class="fas fa-phone-volume"></i>
                            <span>Add Emergency Contact</span>
                        </a>
                    </div>
                @endif
            </div>
        </aside>

        <div class="dashboard-panel">
            <div class="dashboard-panel__heading">
                <div>
                    <span class="dashboard-panel__badge">Profile Overview</span>
                    <h3>Your Information</h3>
                    <p>A structured summary of the details currently saved in your student account.</p>
                </div>
            </div>

            <div class="dashboard-info-grid">
                <div class="dashboard-info-card">
                    <small>Email</small>
                    <strong>{{ $user->email ?: 'N/A' }}</strong>
                </div>
                <div class="dashboard-info-card">
                    <small>Country</small>
                    <strong>{{ $user->country ?: 'N/A' }}</strong>
                </div>
                <div class="dashboard-info-card">
                    <small>Address</small>
                    <strong>{{ $user->address ?: 'N/A' }}</strong>
                </div>
                <div class="dashboard-info-card">
                    <small>Religion</small>
                    <strong>{{ $user->religion ?: 'N/A' }}</strong>
                </div>
                <div class="dashboard-info-card">
                    <small>Date of Birth</small>
                    <strong>{{ $user->date_of_birth ?: 'N/A' }}</strong>
                </div>
                <div class="dashboard-info-card">
                    <small>Gender</small>
                    <strong>{{ $user->gender ?: 'N/A' }}</strong>
                </div>
                <div class="dashboard-info-card">
                    <small>Course Type</small>
                    <strong>{{ $user->course_type ?: 'N/A' }}</strong>
                </div>
                <div class="dashboard-info-card">
                    <small>Department</small>
                    <strong>{{ $user->department ?: 'N/A' }}</strong>
                </div>
                @if (!empty($user->course_year))
                    <div class="dashboard-info-card">
                        <small>Course Year</small>
                        <strong>{{ $user->course_year }}</strong>
                    </div>
                @endif
                @if (!empty($user->course_language))
                    <div class="dashboard-info-card">
                        <small>Course Language</small>
                        <strong>{{ $user->course_language }}</strong>
                    </div>
                @endif
            </div>

            <div class="dashboard-status-panel">
                <div class="dashboard-panel__heading">
                    <div>
                        <span class="dashboard-panel__badge">Student Status</span>
                        <h3>Important Details</h3>
                        <p>Quick reference information for your current account setup and academic record.</p>
                    </div>
                </div>

                <div class="dashboard-status-grid">
                    <div class="dashboard-status-card">
                        <small>Room Number</small>
                        <strong>{{ $user->room_number ?: 'N/A' }}</strong>
                    </div>
                    <div class="dashboard-status-card">
                        <small>Mobile Number</small>
                        <strong>{{ $user->mobile_number ?: 'N/A' }}</strong>
                    </div>
                    <div class="dashboard-status-card">
                        <small>Account Status</small>
                        <strong>{{ $user->approved ? 'Approved' : 'Pending Review' }}</strong>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
