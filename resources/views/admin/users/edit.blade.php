@extends('layouts.admin_app')

@section('content')
<style>
    .duplicate-alert {
        margin-bottom: 22px;
        padding: 16px 18px;
        border-radius: 18px;
        background: #fff7ed;
        border: 1px solid rgba(249, 115, 22, 0.2);
        color: #9a3412;
    }

    .duplicate-modal-list {
        display: grid;
        gap: 14px;
    }

    .duplicate-modal-item {
        display: grid;
        grid-template-columns: 72px minmax(0, 1fr);
        gap: 14px;
        padding: 14px;
        border-radius: 18px;
        background: #f8fafc;
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    .duplicate-modal-item img {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: 18px;
        display: block;
    }

    .duplicate-modal-reasons {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 10px 0;
    }

    .duplicate-modal-reason {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        background: #ffedd5;
        color: #c2410c;
        font-size: 0.78rem;
        font-weight: 700;
    }
</style>
<div class="admin-page">
    <section class="admin-hero-card">
        <h2>Edit User Information</h2>
        <p>Update student identity, academic profile, and account-related details with the same clean admin workspace style.</p>
    </section>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(($duplicateUsers ?? collect())->isNotEmpty())
        <div class="duplicate-alert">
            Duplicate match found for this user. Full name or passport number matches another record.
            <button type="button" class="btn btn-sm btn-outline-danger ms-2" data-bs-toggle="modal" data-bs-target="#duplicateUsersModal">
                View Duplicate Users
            </button>
        </div>
    @endif

    <section class="admin-form-shell">
        <div class="admin-toolbar">
            <div class="admin-toolbar__title">
                <h3>{{ $user->full_name }}</h3>
                <p>Carefully edit user records without leaving the admin dashboard.</p>
            </div>
            <span class="admin-chip">
                <i class="fas fa-user-pen"></i>
                <span>Editing Profile</span>
            </span>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" id="editUserForm">
            @csrf

            <div class="admin-grid-2">
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" name="full_name" id="full_name" class="form-control" value="{{ $user->full_name }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="mb-3">
                    <label for="mobile_number" class="form-label">Mobile Number</label>
                    <input type="text" name="mobile_number" id="mobile_number" class="form-control" value="{{ $user->mobile_number }}" required>
                </div>

                <div class="mb-3">
                    <label for="room_number" class="form-label">Room Number</label>
                    <input type="text" name="room_number" id="room_number" class="form-control" value="{{ $user->room_number }}" required>
                </div>

                <div class="mb-3">
                    <label for="country" class="form-label">Country</label>
                    <select class="form-select" id="country" name="country" required>
                        <option value="Bangladesh" {{ $user->country == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                        <option value="India" {{ $user->country == 'India' ? 'selected' : '' }}>India</option>
                        <option value="Nepal" {{ $user->country == 'Nepal' ? 'selected' : '' }}>Nepal</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select name="gender" id="gender" class="form-select" required>
                        <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="religion" class="form-label">Religion</label>
                    <select class="form-select" id="religion" name="religion" required>
                        <option value="Muslim" {{ $user->religion == 'Muslim' ? 'selected' : '' }}>Muslim</option>
                        <option value="Hindu" {{ $user->religion == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                        <option value="Boddho" {{ $user->religion == 'Boddho' ? 'selected' : '' }}>Boddho</option>
                        <option value="Cristan" {{ $user->religion == 'Cristan' ? 'selected' : '' }}>Cristan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ $user->date_of_birth }}" required>
                </div>

                <div class="mb-3">
                    <label for="course_type" class="form-label">Course Type</label>
                    <select class="form-select" id="course_type" name="course_type" required>
                        <option value="Language" {{ $user->course_type == 'Language' ? 'selected' : '' }}>Language</option>
                        <option value="BSC" {{ $user->course_type == 'BSC' ? 'selected' : '' }}>BSC</option>
                        <option value="MSC" {{ $user->course_type == 'MSC' ? 'selected' : '' }}>MSC</option>
                        <option value="PHD" {{ $user->course_type == 'PHD' ? 'selected' : '' }}>PHD</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="department" class="form-label">Department</label>
                    <select class="form-select" id="department" name="department" onchange="toggleOtherDepartment()" required>
                        <option value="Prepetory Language Course" {{ $user->department == 'Prepetory Language Course' ? 'selected' : '' }}>Prepetory Language Course</option>
                        <option value="Automobile" {{ $user->department == 'Automobile' ? 'selected' : '' }}>Automobile</option>
                        <option value="Forestry" {{ $user->department == 'Forestry' ? 'selected' : '' }}>Forestry</option>
                        <option value="Mechanical" {{ $user->department == 'Mechanical' ? 'selected' : '' }}>Mechanical</option>
                        <option value="Computer Science and Technology" {{ $user->department == 'Computer Science and Technology' ? 'selected' : '' }}>Computer Science and Technology</option>
                        <option value="Economics" {{ $user->department == 'Economics' ? 'selected' : '' }}>Economics</option>
                        <option value="Landscape Architecture" {{ $user->department == 'Landscape Architecture' ? 'selected' : '' }}>Landscape Architecture</option>
                        <option value="Tourism" {{ $user->department == 'Tourism' ? 'selected' : '' }}>Tourism</option>
                        <option value="automation of production processes" {{ $user->department == 'automation of production processes' ? 'selected' : '' }}>automation of production processes</option>
                        <option value="Life Safety and Legal Relations" {{ $user->department == 'Life Safety and Legal Relations' ? 'selected' : '' }}>Life Safety and Legal Relations</option>
                        <option value="Botany and Plant Physiology" {{ $user->department == 'Botany and Plant Physiology' ? 'selected' : '' }}>Botany and Plant Physiology</option>
                        <option value="Hardware and Software" {{ $user->department == 'Hardware and Software' ? 'selected' : '' }}>Hardware and Software</option>
                        <option value="Other" {{ old('department') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="mb-3" id="other-department" style="{{ old('department', $user->department) == 'Other' ? 'display: block;' : 'display: none;' }}">
                    <label for="other_department" class="form-label">Other Department</label>
                    <input type="text" class="form-control" id="other_department" name="other_department" value="{{ old('other_department', $user->department == 'Other' ? $user->other_department : '') }}">
                </div>

                <div class="mb-3">
                    <label for="course_year" class="form-label">Course Year</label>
                    <select class="form-select" id="course_year" name="course_year">
                        <option value="" {{ old('course_year', $user->course_year) == '' ? 'selected' : '' }}>-- Select Course Year --</option>
                        <option value="1st Year" {{ $user->course_year == '1st Year' ? 'selected' : '' }}>1st Year</option>
                        <option value="2nd Year" {{ $user->course_year == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                        <option value="3rd Year" {{ $user->course_year == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                        <option value="Final Year" {{ $user->course_year == 'Final Year' ? 'selected' : '' }}>Final Year</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="course_language" class="form-label">Course Language</label>
                    <select class="form-select" id="course_language" name="course_language">
                        <option value="" {{ old('course_language', $user->course_language) == '' ? 'selected' : '' }}>-- Select Course Language --</option>
                        <option value="English" {{ old('course_language', $user->course_language) == 'English' ? 'selected' : '' }}>English</option>
                        <option value="Russian" {{ old('course_language', $user->course_language) == 'Russian' ? 'selected' : '' }}>Russian</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" class="form-control" rows="4" required>{{ $user->address }}</textarea>
            </div>

            <div class="admin-panel mb-4">
                <div class="admin-toolbar">
                    <div class="admin-toolbar__title">
                        <h4>Profile Image</h4>
                        <p>Upload a new profile photo if needed.</p>
                    </div>
                </div>
                <div class="admin-grid-2">
                    <div>
                        <label for="photo" class="form-label">User Photo</label>
                        <input type="file" name="photo" id="photo" class="form-control">
                    </div>
                    <div class="text-center">
                        @if($user->photo)
                            <img class="admin-media" src="{{ asset('storage/' . $user->photo) }}" alt="User Photo">
                        @else
                            <div class="admin-empty">No photo available</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="admin-actions-inline">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('admin.users.view', $user->id) }}" class="btn btn-outline-primary">Back to Profile</a>
                <button type="submit" form="deleteUserForm" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete User</button>
            </div>
        </form>
    </section>
</div>

<form action="{{ route('admin.users.delete', $user->id) }}" method="POST" id="deleteUserForm" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@if(($duplicateUsers ?? collect())->isNotEmpty())
    <div class="modal fade" id="duplicateUsersModal" tabindex="-1" aria-labelledby="duplicateUsersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="duplicateUsersModalLabel">Duplicate Users Found</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="duplicate-modal-list">
                        @foreach ($duplicateUsers as $duplicateUser)
                            <article class="duplicate-modal-item">
                                <div>
                                    <img src="{{ $duplicateUser->photo ? asset('storage/' . $duplicateUser->photo) : asset('storage/default.png') }}" alt="{{ $duplicateUser->full_name }}">
                                </div>

                                <div>
                                    <h5 class="mb-1">{{ $duplicateUser->full_name }}</h5>
                                    <p class="text-muted mb-2">{{ $duplicateUser->email ?: 'No email' }}</p>

                                    <div class="duplicate-modal-reasons">
                                        @foreach ($duplicateUser->duplicate_match_reasons as $reason)
                                            <span class="duplicate-modal-reason"><i class="fas fa-link"></i> Match by {{ $reason }}</span>
                                        @endforeach
                                    </div>

                                    <div class="admin-grid-2 mb-3">
                                        <div class="admin-kv-item">
                                            <span>Passport Number</span>
                                            <strong>{{ $duplicateUser->studentsData->passport_number ?? 'No passport data' }}</strong>
                                        </div>
                                        <div class="admin-kv-item">
                                            <span>Room</span>
                                            <strong>{{ $duplicateUser->room_number ?: 'N/A' }}</strong>
                                        </div>
                                    </div>

                                    <div class="admin-actions-inline">
                                        <a href="{{ route('admin.users.view', $duplicateUser->id) }}" class="btn btn-sm btn-primary">Open User</a>
                                        <a href="{{ route('admin.users.edit', $duplicateUser->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.users.delete', $duplicateUser->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this duplicate user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete User</button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
function toggleOtherDepartment() {
    var departmentSelect = document.getElementById("department");
    var otherDepartmentInput = document.getElementById("other_department");
    var otherDepartmentDiv = document.getElementById("other-department");

    if (departmentSelect.value === "Other") {
        otherDepartmentDiv.style.display = "block";
        otherDepartmentInput.setAttribute("required", "required");
    } else {
        otherDepartmentDiv.style.display = "none";
        otherDepartmentInput.removeAttribute("required");
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const duplicateModalElement = document.getElementById('duplicateUsersModal');

    if (duplicateModalElement && window.bootstrap && window.bootstrap.Modal) {
        const duplicateModal = new window.bootstrap.Modal(duplicateModalElement);
        duplicateModal.show();
    }
});
</script>
@endsection
