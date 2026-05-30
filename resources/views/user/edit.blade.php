@extends('layouts.app')

@section('content')
<style>
    .user-edit-page {
        background:
            radial-gradient(circle at top left, rgba(37, 99, 235, 0.08), transparent 30%),
            linear-gradient(180deg, #f8fbff 0%, #f3f6fb 100%);
        padding: 28px 0 56px;
    }

    .user-edit-wrap {
        max-width: 1040px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .user-edit-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        padding: 28px 30px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 24px;
        background:
            radial-gradient(circle at top right, rgba(125, 211, 252, 0.22), transparent 24%),
            linear-gradient(135deg, #0f172a 0%, #1e3a8a 58%, #0f766e 100%);
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.14);
        color: #fff;
    }

    .user-edit-hero::after {
        content: "";
        position: absolute;
        inset: auto -40px -40px auto;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
    }

    .user-edit-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        margin-bottom: 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.92);
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .user-edit-hero h1 {
        margin: 0;
        color: #fff;
        font-size: clamp(1.9rem, 3vw, 2.5rem);
        font-weight: 800;
    }

    .user-edit-hero p {
        margin: 10px 0 0;
        max-width: 720px;
        color: rgba(255, 255, 255, 0.82);
        line-height: 1.7;
        font-size: 1rem;
    }

    .user-edit-card {
        overflow: hidden;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.97);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.07);
    }

    .user-edit-card + .user-edit-card {
        margin-top: 24px;
    }

    .user-edit-alert {
        margin-bottom: 18px;
        border-radius: 16px;
    }

    .user-edit-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 24px 24px 20px;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(180deg, rgba(248, 250, 252, 0.92), rgba(255, 255, 255, 0.9));
    }

    .user-edit-header h1,
    .user-edit-header h2 {
        margin: 0;
        color: #1f2937;
        font-weight: 700;
    }

    .user-edit-header h1 {
        font-size: 1.75rem;
    }

    .user-edit-header h2 {
        font-size: 1.2rem;
    }

    .user-edit-header p {
        margin: 8px 0 0;
        color: #6b7280;
        line-height: 1.6;
    }

    .user-edit-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 13px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 0.82rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .user-edit-body {
        padding: 24px;
    }

    .user-edit-meta {
        display: grid;
        gap: 14px;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        margin-bottom: 24px;
    }

    .user-edit-meta-item {
        padding: 16px 18px;
        border: 1px solid #e7eef7;
        border-radius: 16px;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
    }

    .user-edit-meta-item span {
        display: block;
        margin-bottom: 4px;
        color: #6b7280;
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .user-edit-meta-item strong {
        color: #111827;
        font-size: 0.96rem;
        font-weight: 700;
    }

    .user-edit-grid {
        display: grid;
        gap: 18px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .user-edit-photo-panel {
        display: grid;
        gap: 18px;
        grid-template-columns: 180px minmax(0, 1fr);
        align-items: center;
        margin-bottom: 24px;
        padding: 18px;
        border: 1px solid #e7eef7;
        border-radius: 20px;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
    }

    .user-edit-photo-frame {
        position: relative;
        width: 180px;
        height: 180px;
        border-radius: 26px;
        overflow: hidden;
        border: 1px solid #d7e2f1;
        background: linear-gradient(135deg, #dbeafe, #eff6ff);
        box-shadow: 0 18px 34px rgba(37, 99, 235, 0.12);
    }

    .user-edit-photo-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .user-edit-photo-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1d4ed8;
        font-size: 3rem;
        background: linear-gradient(135deg, #e0ecff, #f8fbff);
    }

    .user-edit-photo-upload {
        position: absolute;
        right: 12px;
        bottom: 12px;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        border: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #2563eb, #0f766e);
        color: #fff;
        box-shadow: 0 12px 24px rgba(37, 99, 235, 0.24);
        cursor: pointer;
    }

    .user-edit-photo-upload:hover {
        color: #fff;
    }

    .user-edit-photo-input {
        display: none;
    }

    .user-edit-photo-copy h3 {
        margin: 0 0 8px;
        color: #111827;
        font-size: 1.15rem;
        font-weight: 700;
    }

    .user-edit-photo-copy p {
        margin: 0 0 12px;
        color: #64748b;
        line-height: 1.7;
    }

    .user-edit-photo-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #ecfeff;
        color: #0f766e;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .user-edit-photo-name {
        margin-top: 12px;
        color: #1d4ed8;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .user-edit-field {
        display: block;
    }

    .user-edit-field--full {
        grid-column: 1 / -1;
    }

    .user-edit-field label {
        display: block;
        margin-bottom: 8px;
        color: #374151;
        font-weight: 600;
    }

    .user-edit-field small {
        display: block;
        margin-top: 6px;
        color: #6b7280;
        line-height: 1.5;
    }

    .user-edit-input {
        min-height: 50px;
        border: 1px solid #d4dbe5;
        border-radius: 14px;
        background: #fff;
        box-shadow: none;
    }

    .user-edit-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.12);
    }

    .user-edit-note {
        padding: 14px 16px;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        background: #f8fafc;
        color: #475569;
    }

    .user-edit-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #eef2f7;
    }

    .user-edit-actions p {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
    }

    .user-edit-btn {
        min-width: 170px;
        border: 0;
        border-radius: 14px;
        font-weight: 700;
        padding: 0.82rem 1.3rem;
        background: linear-gradient(135deg, #2563eb, #0f766e);
        box-shadow: 0 14px 28px rgba(37, 99, 235, 0.16);
    }

    .user-edit-password-wrap {
        position: relative;
    }

    .user-edit-contact-list {
        display: grid;
        gap: 14px;
    }

    .user-edit-contact-row {
        display: grid;
        gap: 14px;
        grid-template-columns: minmax(0, 220px) minmax(0, 1fr) auto;
        padding: 14px;
        border: 1px solid #e7eef7;
        border-radius: 16px;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
    }

    .user-edit-contact-actions {
        display: flex;
        align-items: end;
    }

    .user-edit-password-wrap .user-edit-input {
        padding-right: 52px;
    }

    .user-edit-eye {
        position: absolute;
        top: 50%;
        right: 14px;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: #6b7280;
        padding: 0;
        line-height: 1;
    }

    .user-edit-eye:focus {
        outline: none;
        color: #2563eb;
    }

    @media (max-width: 991.98px) {
        .user-edit-meta,
        .user-edit-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .user-edit-header,
        .user-edit-body {
            padding: 18px;
        }

        .user-edit-hero {
            padding: 22px 20px;
        }

        .user-edit-header {
            flex-direction: column;
        }

        .user-edit-meta,
        .user-edit-grid {
            grid-template-columns: 1fr;
        }

        .user-edit-photo-panel {
            grid-template-columns: 1fr;
        }

        .user-edit-actions {
            align-items: stretch;
        }

        .user-edit-contact-row {
            grid-template-columns: 1fr;
        }

        .user-edit-btn {
            width: 100%;
        }
    }
</style>

<div class="user-edit-page">
    <div class="user-edit-wrap">
        <section class="user-edit-hero">
            <span class="user-edit-kicker"><i class="fas fa-user-gear"></i> Account Settings</span>
            <h1>Edit Profile</h1>
            <p>Manage your personal information and account security from one organized page. Profile details and password update stay clearly separated for a safer workflow.</p>
        </section>

        @if (session('success'))
            <div class="alert alert-success user-edit-alert">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger user-edit-alert">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('user.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="form_action" value="profile">

        <section class="user-edit-card">
            <div class="user-edit-header">
                <div>
                    <h2>Profile Information</h2>
                    <p>Update the fields currently available to you. Changes here affect your main student profile details.</p>
                </div>
                <span class="user-edit-badge"><i class="fas fa-id-card"></i> Profile Update</span>
            </div>

            <div class="user-edit-body">
                @php
                    $profilePhoto = !empty($user->photo) ? asset('storage/' . $user->photo) : asset('default-avatar.png');
                @endphp

                @if ($photoEditable)
                    <div class="user-edit-photo-panel">
                        <div class="user-edit-photo-frame">
                            <img src="{{ $profilePhoto }}" alt="{{ $user->full_name }}" id="profilePhotoPreview" onerror="this.style.display='none'; document.getElementById('profilePhotoFallback').style.display='flex';">
                            <div class="user-edit-photo-fallback" id="profilePhotoFallback" style="display:none;">
                                <i class="fas fa-user"></i>
                            </div>
                            <label for="photo" class="user-edit-photo-upload" title="Upload new photo">
                                <i class="fas fa-camera"></i>
                            </label>
                        </div>

                        <div class="user-edit-photo-copy">
                            <h3>Profile Photo</h3>
                            <p>Your current photo is shown here. Choose a new image to instantly preview it before saving the profile.</p>
                            <span class="user-edit-photo-chip"><i class="fas fa-image"></i> JPG, PNG, or WEBP up to 2 MB</span>
                            <div class="user-edit-photo-name" id="profilePhotoFileName">Current photo ready</div>
                            <input type="file" name="photo" id="photo" class="user-edit-photo-input" accept="image/jpeg,image/png,image/webp">
                            @error('photo')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="user-edit-meta">
                    <div class="user-edit-meta-item">
                        <span>Name</span>
                        <strong>{{ $user->full_name ?: 'Not available' }}</strong>
                    </div>
                    <div class="user-edit-meta-item">
                        <span>Department</span>
                        <strong>{{ $user->department ?: 'Not set' }}</strong>
                    </div>
                    <div class="user-edit-meta-item">
                        <span>Course Type</span>
                        <strong>{{ $user->course_type ?: 'Not set' }}</strong>
                    </div>
                </div>

                @if (count($profileEditableFields) > 0)
                    <div class="user-edit-grid">
                        @foreach ($profileEditableFields as $field)
                            @php
                                $definition = $fieldDefinitions[$field];
                                $value = old($field, $definition['type'] === 'date' && $user->{$field}
                                    ? $user->{$field}->format('Y-m-d')
                                    : $user->{$field});
                            @endphp

                            <div class="user-edit-field {{ !empty($definition['full_width']) ? 'user-edit-field--full' : '' }}">
                                <label for="{{ $field }}">{{ $definition['label'] }}</label>

                                @if ($definition['type'] === 'select')
                                    <select name="{{ $field }}" id="{{ $field }}" class="form-control user-edit-input">
                                        @if (!empty($definition['placeholder']))
                                            <option value="">{{ $definition['placeholder'] }}</option>
                                        @endif
                                        @foreach ($definition['options'] as $optionValue => $optionLabel)
                                            <option value="{{ $optionValue }}" {{ (string) $value === (string) $optionValue ? 'selected' : '' }}>
                                                {{ $optionLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif ($definition['type'] === 'textarea')
                                    <textarea
                                        name="{{ $field }}"
                                        id="{{ $field }}"
                                        class="form-control user-edit-input"
                                        rows="{{ $definition['rows'] ?? 4 }}"
                                    >{{ $value }}</textarea>
                                @else
                                    <input
                                        type="{{ $definition['type'] }}"
                                        name="{{ $field }}"
                                        id="{{ $field }}"
                                        class="form-control user-edit-input"
                                        value="{{ $value }}"
                                    >
                                @endif

                                @if (!empty($definition['description']))
                                    <small>{{ $definition['description'] }}</small>
                                @endif

                                @error($field)
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="user-edit-note">
                        Admin has currently disabled editable profile fields for your account.
                    </div>
                @endif

                <div class="user-edit-field user-edit-field--full">
                    <label>Emergency Contact Details</label>
                    <small class="mb-3">Add multiple emergency contact channels like WhatsApp number, Telegram username, or any other urgent contact details.</small>

                    <div id="emergencyContactList" class="user-edit-contact-list">
                        @forelse (old('emergency_contacts', $user->emergencyContacts->map(fn ($contact) => ['platform' => $contact->platform, 'contact_value' => $contact->contact_value])->all()) as $index => $contact)
                            <div class="user-edit-contact-row">
                                <div>
                                    <label for="emergency_contacts_{{ $index }}_platform">Platform</label>
                                    <input
                                        type="text"
                                        name="emergency_contacts[{{ $index }}][platform]"
                                        id="emergency_contacts_{{ $index }}_platform"
                                        class="form-control user-edit-input"
                                        value="{{ $contact['platform'] ?? '' }}"
                                        placeholder="WhatsApp / Telegram / IMO"
                                    >
                                </div>
                                <div>
                                    <label for="emergency_contacts_{{ $index }}_contact_value">Number / Username</label>
                                    <input
                                        type="text"
                                        name="emergency_contacts[{{ $index }}][contact_value]"
                                        id="emergency_contacts_{{ $index }}_contact_value"
                                        class="form-control user-edit-input"
                                        value="{{ $contact['contact_value'] ?? '' }}"
                                        placeholder="Enter phone number or username"
                                    >
                                </div>
                                <div class="user-edit-contact-actions">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeEmergencyContact(this)">Remove</button>
                                </div>
                            </div>
                        @empty
                            <div class="user-edit-contact-row">
                                <div>
                                    <label for="emergency_contacts_0_platform">Platform</label>
                                    <input
                                        type="text"
                                        name="emergency_contacts[0][platform]"
                                        id="emergency_contacts_0_platform"
                                        class="form-control user-edit-input"
                                        placeholder="WhatsApp / Telegram / IMO"
                                    >
                                </div>
                                <div>
                                    <label for="emergency_contacts_0_contact_value">Number / Username</label>
                                    <input
                                        type="text"
                                        name="emergency_contacts[0][contact_value]"
                                        id="emergency_contacts_0_contact_value"
                                        class="form-control user-edit-input"
                                        placeholder="Enter phone number or username"
                                    >
                                </div>
                                <div class="user-edit-contact-actions">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeEmergencyContact(this)">Remove</button>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-primary" onclick="addEmergencyContact()">Add Emergency Contact</button>
                    </div>

                    @error('emergency_contacts')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                    @error('emergency_contacts.*.platform')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                    @error('emergency_contacts.*.contact_value')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="user-edit-actions">
                    <p>Review your information carefully before saving profile updates.</p>
                    <button type="submit" class="btn btn-primary user-edit-btn">Update Profile</button>
                </div>
            </div>
        </section>
        </form>

        @if ($passwordEditable)
            <form method="POST" action="{{ route('user.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_action" value="password">

            <section class="user-edit-card">
                <div class="user-edit-header">
                    <div>
                        <h2>Password & Security</h2>
                        <p>Use your current password to verify identity, then set and confirm a new password for your account.</p>
                    </div>
                    <span class="user-edit-badge"><i class="fas fa-shield-halved"></i> Secure Change</span>
                </div>

                <div class="user-edit-body">
                    <div class="user-edit-grid">
                        <div class="user-edit-field">
                            <label for="current_password">Old Password</label>
                            <div class="user-edit-password-wrap">
                                <input type="password" name="current_password" id="current_password" class="form-control user-edit-input" autocomplete="current-password">
                                <button type="button" class="user-edit-eye" data-toggle-password="current_password" aria-label="Show old password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="user-edit-field">
                            <label for="password">New Password</label>
                            <div class="user-edit-password-wrap">
                                <input type="password" name="password" id="password" class="form-control user-edit-input" autocomplete="new-password">
                                <button type="button" class="user-edit-eye" data-toggle-password="password" aria-label="Show new password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="user-edit-field user-edit-field--full">
                            <label for="password_confirmation">Confirm New Password</label>
                            <div class="user-edit-password-wrap">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control user-edit-input" autocomplete="new-password">
                                <button type="button" class="user-edit-eye" data-toggle-password="password_confirmation" aria-label="Show confirm password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="user-edit-actions">
                        <p>Choose a strong password with at least 8 characters before submitting.</p>
                        <button type="submit" class="btn btn-primary user-edit-btn">Change Password</button>
                    </div>
                </div>
            </section>
            </form>
        @endif
    </div>
</div>

<script>
    let emergencyContactIndex = document.querySelectorAll('#emergencyContactList .user-edit-contact-row').length;

    function addEmergencyContact() {
        const list = document.getElementById('emergencyContactList');
        const row = document.createElement('div');
        row.className = 'user-edit-contact-row';
        row.innerHTML = `
            <div>
                <label for="emergency_contacts_${emergencyContactIndex}_platform">Platform</label>
                <input
                    type="text"
                    name="emergency_contacts[${emergencyContactIndex}][platform]"
                    id="emergency_contacts_${emergencyContactIndex}_platform"
                    class="form-control user-edit-input"
                    placeholder="WhatsApp / Telegram / IMO"
                >
            </div>
            <div>
                <label for="emergency_contacts_${emergencyContactIndex}_contact_value">Number / Username</label>
                <input
                    type="text"
                    name="emergency_contacts[${emergencyContactIndex}][contact_value]"
                    id="emergency_contacts_${emergencyContactIndex}_contact_value"
                    class="form-control user-edit-input"
                    placeholder="Enter phone number or username"
                >
            </div>
            <div class="user-edit-contact-actions">
                <button type="button" class="btn btn-outline-danger" onclick="removeEmergencyContact(this)">Remove</button>
            </div>
        `;
        list.appendChild(row);
        emergencyContactIndex += 1;
    }

    function removeEmergencyContact(button) {
        const list = document.getElementById('emergencyContactList');
        const rows = list.querySelectorAll('.user-edit-contact-row');

        if (rows.length === 1) {
            rows[0].querySelectorAll('input').forEach(input => input.value = '');
            return;
        }

        button.closest('.user-edit-contact-row').remove();
    }

    document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(this.getAttribute('data-toggle-password'));
            const icon = this.querySelector('i');

            if (!input) {
                return;
            }

            const isPassword = input.getAttribute('type') === 'password';
            input.setAttribute('type', isPassword ? 'text' : 'password');

            if (icon) {
                icon.classList.toggle('fa-eye', !isPassword);
                icon.classList.toggle('fa-eye-slash', isPassword);
            }
        });
    });

    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('profilePhotoPreview');
    const photoFallback = document.getElementById('profilePhotoFallback');
    const photoFileName = document.getElementById('profilePhotoFileName');

    if (photoInput && photoPreview && photoFallback && photoFileName) {
        photoInput.addEventListener('change', function () {
            const [file] = this.files || [];

            if (!file) {
                photoFileName.textContent = 'Current photo ready';
                return;
            }

            photoFileName.textContent = file.name;

            const reader = new FileReader();
            reader.onload = function (event) {
                photoPreview.src = event.target.result;
                photoPreview.style.display = 'block';
                photoFallback.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });
    }
</script>
@endsection
