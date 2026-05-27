@extends('layouts.admin_app')

@section('content')
<style>
    .profile-view-grid {
        display: grid;
        gap: 24px;
        grid-template-columns: 1.1fr 1fr 1fr;
    }

    .document-preview {
        display: grid;
        gap: 18px;
    }

    .document-preview img {
        width: 100%;
        min-height: 220px;
        max-height: 260px;
        object-fit: cover;
        border-radius: 22px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
    }

    .profile-view-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .profile-main-card .admin-media {
        width: 160px;
        height: 160px;
        border-radius: 32px;
    }

    @media (max-width: 1199.98px) {
        .profile-view-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="admin-page">
    <section class="admin-hero-card">
        <h2>{{ $user->full_name }}</h2>
        <p>Complete student profile, passport and visa records, and quick action controls in one focused admin view.</p>
    </section>

    <div class="profile-view-grid">
        <section class="admin-panel profile-main-card">
            <div class="admin-toolbar">
                <div class="admin-toolbar__title">
                    <h3>Student Profile</h3>
                    <p>Core identity and academic information.</p>
                </div>
                <span class="admin-chip">
                    <i class="fas fa-door-open"></i>
                    <span>Room {{ $user->room_number ?: 'N/A' }}</span>
                </span>
            </div>

            <div class="text-center mb-4">
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
        </section>

        <section class="admin-panel">
            <div class="admin-toolbar">
                <div class="admin-toolbar__title">
                    <h3>Passport & Visa</h3>
                    <p>Document metadata and expiry details.</p>
                </div>
            </div>

            <div class="admin-kv">
                <div class="admin-kv-item"><span>Passport Number</span><strong>{{ $user->studentsData->passport_number ?? 'No Data Available' }}</strong></div>
                <div class="admin-kv-item"><span>Visa Start Date</span><strong>{{ $user->studentsData->visa_start_date ?? 'No Data Available' }}</strong></div>
                <div class="admin-kv-item"><span>Visa Expiry Date</span><strong>{{ $user->studentsData->visa_expiry_date ?? 'No Data Available' }}</strong></div>
            </div>

            <div class="document-preview mt-4">
                <div>
                    <h5>Passport Photo</h5>
                    @if($user->studentsData && $user->studentsData->passport_photo)
                        <a href="{{ asset('storage/' . $user->studentsData->passport_photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $user->studentsData->passport_photo) }}" alt="Passport Photo">
                        </a>
                    @else
                        <div class="admin-empty">No passport photo available</div>
                    @endif
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-toolbar">
                <div class="admin-toolbar__title">
                    <h3>Supporting Documents</h3>
                    <p>Visa and green card previews.</p>
                </div>
            </div>

            <div class="document-preview">
                <div>
                    <h5>Visa Photo</h5>
                    @if($user->studentsData && $user->studentsData->visa_photo)
                        <a href="{{ asset('storage/' . $user->studentsData->visa_photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $user->studentsData->visa_photo) }}" alt="Visa Photo">
                        </a>
                    @else
                        <div class="admin-empty">No visa photo available</div>
                    @endif
                </div>

                <div>
                    <h5>Green Card Photo</h5>
                    @if($user->studentsData && $user->studentsData->green_card_photo)
                        <a href="{{ asset('storage/' . $user->studentsData->green_card_photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $user->studentsData->green_card_photo) }}" alt="Green Card Photo">
                        </a>
                    @else
                        <div class="admin-empty">No green card photo available</div>
                    @endif
                </div>
            </div>
        </section>
    </div>

    <section class="admin-panel">
        <div class="admin-toolbar">
            <div class="admin-toolbar__title">
                <h3>Quick Actions</h3>
                <p>Common admin tasks for this student profile.</p>
            </div>
        </div>

        <div class="profile-view-actions">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('admin.notifications.create', ['user_id' => $user->id]) }}" class="btn btn-outline-primary">Send Notification</a>
            <button onclick="printContent()" class="btn btn-success">Print</button>
            <a href="{{ route('admin.users.pdf', $user->id) }}" class="btn btn-danger">Download PDF</a>
        </div>
    </section>
</div>

<script>
    function printContent() {
        window.print();
    }
</script>
@endsection
