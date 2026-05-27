@extends('layouts.admin_app')

@section('content')
<style>
    .profile-page {
        display: grid;
        gap: 24px;
    }

    .profile-view-grid {
        display: grid;
        gap: 24px;
        grid-template-columns: minmax(0, 1.08fr) minmax(0, 0.92fr);
    }

    .profile-section-card {
        overflow: hidden;
    }

    .profile-overview-header {
        display: grid;
        gap: 16px;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: start;
        margin-bottom: 20px;
    }

    .profile-overview-header h2 {
        margin: 0 0 8px;
        color: #0f172a;
        font-size: 1.9rem;
    }

    .profile-overview-header p {
        margin: 0;
        color: #64748b;
        line-height: 1.7;
    }

    .profile-overview-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 14px;
    }

    .profile-overview-badge,
    .profile-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 800;
    }

    .profile-overview-badge {
        background: #f8fafc;
        color: #0f172a;
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    .profile-status-pill {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .profile-status-pill.is-approved {
        background: #dcfce7;
        color: #166534;
    }

    .profile-status-pill.is-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .profile-main-card .admin-toolbar,
    .profile-section-card .admin-toolbar {
        margin-bottom: 18px;
    }

    .profile-main-card .admin-media {
        width: 160px;
        height: 160px;
        border-radius: 32px;
        object-fit: cover;
        box-shadow: 0 18px 36px rgba(15, 23, 42, 0.14);
    }

    .profile-avatar-wrap {
        display: grid;
        justify-items: center;
        gap: 14px;
        margin-bottom: 26px;
    }

    .profile-avatar-wrap small {
        color: #64748b;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .profile-stats {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        margin-bottom: 18px;
    }

    .profile-stat-card {
        padding: 16px 18px;
        border-radius: 20px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    .profile-stat-card small {
        display: block;
        color: #64748b;
        margin-bottom: 6px;
    }

    .profile-stat-card strong {
        display: block;
        font-size: 1rem;
        color: #0f172a;
    }

    .document-preview {
        display: grid;
        gap: 18px;
    }

    .document-card {
        display: grid;
        gap: 14px;
        padding: 18px;
        border-radius: 22px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    .document-card__label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .document-card__label h5 {
        margin: 0;
    }

    .document-card img {
        width: 100%;
        min-height: 220px;
        max-height: 260px;
        object-fit: cover;
        border-radius: 22px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
    }

    .contact-list {
        display: grid;
        gap: 12px;
    }

    .contact-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 14px 16px;
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    .contact-item small {
        display: block;
        color: #64748b;
        margin-bottom: 4px;
    }

    .contact-item strong {
        color: #0f172a;
    }

    .profile-view-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .right-column-stack {
        display: grid;
        gap: 18px;
    }

    @media print {
        .profile-view-actions {
            box-shadow: none;
        }
    }

    @media (max-width: 1199.98px) {
        .profile-view-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767.98px) {
        .profile-overview-header {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="admin-page profile-page">
    <div class="profile-view-grid">
        <section class="admin-panel profile-main-card profile-section-card">
            <div class="profile-overview-header">
                <div>
                    <h2>{{ $user->full_name }}</h2>
                    <p>Complete student profile, academic information, and contact details in one focused admin workspace.</p>
                    <div class="profile-overview-badges">
                        <span class="profile-overview-badge"><i class="fas fa-door-open"></i> Room {{ $user->room_number ?: 'N/A' }}</span>
                        <span class="profile-overview-badge"><i class="fas fa-envelope"></i> {{ $user->email ?: 'No email' }}</span>
                        <span class="profile-overview-badge"><i class="fas fa-phone"></i> {{ $user->mobile_number ?: 'No number' }}</span>
                    </div>
                </div>
                <span class="profile-status-pill {{ $user->approved ? 'is-approved' : 'is-pending' }}">
                    <i class="fas {{ $user->approved ? 'fa-circle-check' : 'fa-clock' }}"></i>
                    {{ $user->approved ? 'Approved Profile' : 'Pending Approval' }}
                </span>
            </div>

            <div class="admin-toolbar">
                <div class="admin-toolbar__title">
                    <h3>Student Profile</h3>
                    <p>Core identity and academic information.</p>
                </div>
                <span class="admin-chip">
                    <i class="fas fa-user"></i>
                    <span>Main Record</span>
                </span>
            </div>

            <div class="profile-avatar-wrap">
                <small>Primary Photo</small>
                <a href="{{ $user->photo ? asset('storage/' . $user->photo) : asset('storage/default.png') }}" target="_blank">
                    <img class="admin-media" src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('storage/default.png') }}" alt="{{ $user->full_name }}">
                </a>
            </div>

            <div class="admin-kv">
                <div class="admin-kv-item"><span>Name</span><strong>{{ $user->full_name }}</strong></div>
                <div class="admin-kv-item"><span>Email</span><strong>{{ $user->email ?: 'N/A' }}</strong></div>
                <div class="admin-kv-item"><span>Mobile</span><strong>{{ $user->mobile_number ?: 'N/A' }}</strong></div>
                <div class="admin-kv-item"><span>Country</span><strong>{{ $user->country ?: 'N/A' }}</strong></div>
                <div class="admin-kv-item"><span>Address</span><strong>{{ $user->address ?: 'N/A' }}</strong></div>
                <div class="admin-kv-item"><span>Gender</span><strong>{{ $user->gender ?: 'N/A' }}</strong></div>
                <div class="admin-kv-item"><span>Religion</span><strong>{{ $user->religion ?: 'N/A' }}</strong></div>
                <div class="admin-kv-item"><span>Date of Birth</span><strong>{{ $user->date_of_birth ?: 'N/A' }}</strong></div>
                <div class="admin-kv-item"><span>Course Type</span><strong>{{ $user->course_type ?: 'N/A' }}</strong></div>
                <div class="admin-kv-item"><span>Department</span><strong>{{ $user->department ?: 'N/A' }}</strong></div>
                @if (!empty($user->course_year))
                    <div class="admin-kv-item"><span>Course Year</span><strong>{{ $user->course_year }}</strong></div>
                @endif
                @if (!empty($user->course_language))
                    <div class="admin-kv-item"><span>Course Language</span><strong>{{ $user->course_language }}</strong></div>
                @endif
            </div>

            <div class="profile-stats">
                <div class="profile-stat-card">
                    <small>Department</small>
                    <strong>{{ $user->department ?: 'N/A' }}</strong>
                </div>
                <div class="profile-stat-card">
                    <small>Course Type</small>
                    <strong>{{ $user->course_type ?: 'N/A' }}</strong>
                </div>
                <div class="profile-stat-card">
                    <small>Course Language</small>
                    <strong>{{ $user->course_language ?: 'N/A' }}</strong>
                </div>
                <div class="profile-stat-card">
                    <small>Passport</small>
                    <strong>{{ $user->studentsData->passport_number ?? 'No Data' }}</strong>
                </div>
            </div>
        </section>

        <section class="admin-panel profile-section-card">
            <div class="right-column-stack">
                <div>
                    <div class="admin-toolbar">
                        <div class="admin-toolbar__title">
                            <h3>Emergency Contacts</h3>
                            <p>User-provided backup contact channels for urgent communication.</p>
                        </div>
                    </div>

                    @if ($user->emergencyContacts->isEmpty())
                        <div class="admin-empty">No emergency contact details added yet.</div>
                    @else
                        <div class="contact-list">
                            @foreach ($user->emergencyContacts as $contact)
                                <div class="contact-item">
                                    <div>
                                        <small>{{ $contact->platform }}</small>
                                        <strong>{{ $contact->contact_value }}</strong>
                                    </div>
                                    <span class="admin-chip">
                                        <i class="fas fa-life-ring"></i>
                                        <span>Emergency Contact</span>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div>
                    <div class="admin-toolbar">
                        <div class="admin-toolbar__title">
                            <h3>Passport & Documents</h3>
                            <p>Document metadata and visual previews.</p>
                        </div>
                    </div>

                    <div class="admin-kv mb-4">
                        <div class="admin-kv-item"><span>Passport Number</span><strong>{{ $user->studentsData->passport_number ?? 'No Data Available' }}</strong></div>
                        <div class="admin-kv-item"><span>Visa Start Date</span><strong>{{ $user->studentsData->visa_start_date ?? 'No Data Available' }}</strong></div>
                        <div class="admin-kv-item"><span>Visa Expiry Date</span><strong>{{ $user->studentsData->visa_expiry_date ?? 'No Data Available' }}</strong></div>
                    </div>

                    <div class="document-preview">
                        <div class="document-card">
                            <div class="document-card__label">
                                <h5>Passport Photo</h5>
                                <span class="admin-chip"><i class="fas fa-passport"></i><span>Main ID</span></span>
                            </div>
                            @if($user->studentsData && $user->studentsData->passport_photo)
                                <a href="{{ asset('storage/' . $user->studentsData->passport_photo) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $user->studentsData->passport_photo) }}" alt="Passport Photo">
                                </a>
                            @else
                                <div class="admin-empty">No passport photo available</div>
                            @endif
                        </div>

                        <div class="document-card">
                            <div class="document-card__label">
                                <h5>Visa Photo</h5>
                                <span class="admin-chip"><i class="fas fa-id-card"></i><span>Travel</span></span>
                            </div>
                            @if($user->studentsData && $user->studentsData->visa_photo)
                                <a href="{{ asset('storage/' . $user->studentsData->visa_photo) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $user->studentsData->visa_photo) }}" alt="Visa Photo">
                                </a>
                            @else
                                <div class="admin-empty">No visa photo available</div>
                            @endif
                        </div>

                        <div class="document-card">
                            <div class="document-card__label">
                                <h5>Green Card Photo</h5>
                                <span class="admin-chip"><i class="fas fa-address-card"></i><span>Residency</span></span>
                            </div>
                            @if($user->studentsData && $user->studentsData->green_card_photo)
                                <a href="{{ asset('storage/' . $user->studentsData->green_card_photo) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $user->studentsData->green_card_photo) }}" alt="Green Card Photo">
                                </a>
                            @else
                                <div class="admin-empty">No green card photo available</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    <div class="admin-toolbar">
                        <div class="admin-toolbar__title">
                            <h3>Quick Actions</h3>
                            <p>Common admin tasks for this student profile, ready without leaving the page.</p>
                        </div>
                    </div>

                    <div class="profile-view-actions">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">Edit Profile</a>
                        <a href="{{ route('admin.notifications.create', ['user_id' => $user->id]) }}" class="btn btn-outline-primary">Send Notification</a>
                        <button onclick="printContent()" class="btn btn-success">Print Profile</button>
                        <a href="{{ route('admin.users.pdf', $user->id) }}" class="btn btn-danger">Download PDF</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    function printContent() {
        window.print();
    }
</script>
@endsection
