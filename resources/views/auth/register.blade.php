@extends('layouts.app')

@section('content')
<style>
    .register-page {
        width: min(1280px, calc(100% - 32px));
        margin: 36px auto 52px;
    }

    .register-shell {
        display: grid;
        grid-template-columns: minmax(320px, 0.9fr) minmax(320px, 1.1fr);
        background: linear-gradient(180deg, #fffaf7 0%, #ffffff 100%);
        border: 1px solid rgba(35, 23, 38, 0.08);
        border-radius: 30px;
        overflow: hidden;
        box-shadow: 0 24px 48px rgba(76, 42, 65, 0.12);
    }

    .register-showcase {
        padding: 42px 34px;
        background:
            linear-gradient(145deg, rgba(36, 23, 38, 0.92), rgba(187, 62, 113, 0.9)),
            url('{{ asset('28020.png') }}') center/cover no-repeat;
        color: #fff;
    }

    .register-pill {
        display: inline-flex;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.18);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .register-showcase h1 {
        margin: 18px 0 14px;
        font-size: clamp(2rem, 4vw, 3.2rem);
        line-height: 1.08;
        font-weight: 800;
        text-transform: none;
        letter-spacing: 0;
    }

    .register-showcase p {
        margin: 0;
        color: rgba(255, 255, 255, 0.86);
        line-height: 1.85;
        font-size: 15px;
    }

    .register-points {
        display: grid;
        gap: 14px;
        margin-top: 28px;
    }

    .register-point {
        padding: 16px 18px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.14);
    }

    .register-point strong {
        display: block;
        font-size: 1rem;
        font-weight: 800;
    }

    .register-point span {
        display: block;
        margin-top: 5px;
        color: rgba(255, 255, 255, 0.82);
        font-size: 13px;
        line-height: 1.6;
    }

    .register-form-panel {
        padding: 36px;
        background: linear-gradient(180deg, #fffdfa 0%, #fff7f1 100%);
    }

    .register-form-head {
        margin-bottom: 22px;
    }

    .register-form-head__kicker {
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

    .register-form-head h2 {
        margin: 14px 0 10px;
        color: #241726;
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        font-weight: 800;
    }

    .register-form-head p {
        margin: 0;
        color: #6f6572;
        line-height: 1.75;
    }

    .register-alert {
        margin-bottom: 20px;
        padding: 16px 18px;
        border-radius: 18px;
        background: rgba(220, 53, 69, 0.08);
        border: 1px solid rgba(220, 53, 69, 0.18);
        color: #842029;
    }

    .register-alert strong {
        display: block;
        margin-bottom: 8px;
    }

    .register-alert ul {
        margin: 0;
        padding-left: 18px;
    }

    .register-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .register-field {
        margin-bottom: 18px;
    }

    .register-field--full {
        grid-column: 1 / -1;
    }

    .register-field label {
        display: block;
        margin-bottom: 8px;
        color: #241726;
        font-weight: 700;
    }

    .register-field .form-control {
        min-height: 52px;
        border-radius: 16px;
        border: 1px solid rgba(35, 23, 38, 0.12);
        padding: 0.85rem 1rem;
        box-shadow: none;
        background: #fff;
    }

    .register-field .form-control:focus {
        border-color: rgba(187, 62, 113, 0.5);
        box-shadow: 0 0 0 0.18rem rgba(215, 89, 139, 0.14);
    }

    .register-password-group {
        display: grid;
        grid-template-columns: 1fr 56px;
    }

    .register-password-group .form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .register-password-toggle {
        border: 1px solid rgba(35, 23, 38, 0.12);
        border-left: 0;
        border-top-right-radius: 16px;
        border-bottom-right-radius: 16px;
        background: #fff;
        color: #6f6572;
    }

    .register-password-toggle:hover {
        color: #bb3e71;
    }

    .register-error,
    #passwordMismatch {
        display: block;
        margin-top: 8px;
        color: #dc3545;
        font-size: 14px;
        font-weight: 600;
    }

    .register-submit {
        width: 100%;
        min-height: 54px;
        border: 0;
        border-radius: 999px;
        background: linear-gradient(135deg, #f173aa, #bb3e71);
        color: #fff;
        font-size: 15px;
        font-weight: 800;
        box-shadow: 0 16px 30px rgba(215, 89, 139, 0.26);
    }

    .register-submit:hover {
        color: #fff;
    }

    .register-extra {
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid rgba(35, 23, 38, 0.08);
        text-align: center;
        color: #6f6572;
    }

    .register-login-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-top: 12px;
        padding: 11px 20px;
        border-radius: 999px;
        background: #241726;
        color: #fff !important;
        font-weight: 700;
        text-decoration: none;
    }

    .register-login-btn:hover {
        background: #bb3e71;
    }

    @media (max-width: 1100px) {
        .register-shell {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .register-page {
            width: calc(100% - 16px);
            margin: 20px auto 34px;
        }

        .register-shell {
            border-radius: 22px;
        }

        .register-showcase {
            display: none;
        }

        .register-form-panel {
            padding: 24px 18px;
        }

        .register-grid {
            grid-template-columns: 1fr;
            gap: 0;
        }
    }
</style>

<section class="register-page">
    <div class="register-shell">
        <div class="register-showcase">
            <span class="register-pill">New Student Registration</span>
            <h1>Join the VGLTU Asian Student community.</h1>
            <p>Create your account to become part of the official student platform and keep your academic, contact, and community information connected in one place.</p>

            <div class="register-points">
                <div class="register-point">
                    <strong>Official onboarding</strong>
                    <span>Register with your core student information for community verification and approval.</span>
                </div>
                <div class="register-point">
                    <strong>Unified profile</strong>
                    <span>Keep your room, contact, department, and course details organized in one trusted account.</span>
                </div>
                <div class="register-point">
                    <strong>Community access</strong>
                    <span>Stay connected with announcements, student services, and the VGLTU forum ecosystem.</span>
                </div>
            </div>
        </div>

        <div class="register-form-panel">
            <div class="register-form-head">
                <span class="register-form-head__kicker">Registration Portal</span>
                <h2>{{ __('Registration to VGLTU Student Forum') }}</h2>
                <p>Complete the registration form carefully. Your account will use the same functionality as before, with a cleaner experience.</p>
            </div>

            @if ($errors->any())
                <div class="register-alert">
                    <strong>Whoops! Something went wrong.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf

                <div class="register-grid">
                    <div class="register-field">
                        <label for="room_number">Room Number</label>
                        <input type="text" class="form-control" id="room_number" name="room_number" value="{{ old('room_number') }}" required>
                        @error('room_number')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="register-field">
                        <label for="full_name">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                        @error('full_name')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="register-field">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="register-field">
                        <label for="country_name">Country</label>
                        <select class="form-control" id="country_name" name="country" required>
                            <option value="">Select Your Country</option>
                            <option value="Bangladesh" {{ old('country') == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                            <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                            <option value="Nepal" {{ old('country') == 'Nepal' ? 'selected' : '' }}>Nepal</option>
                        </select>
                        @error('country')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="register-field">
                        <label for="country">Select Country code</label>
                        <select class="form-control" id="country" name="phone_country_code" required>
                            <option value="RU" {{ old('phone_country_code') == 'RU' ? 'selected' : '' }}>Russia (+7)</option>
                            <option value="BD" {{ old('phone_country_code') == 'BD' ? 'selected' : '' }}>Bangladesh (+88)</option>
                        </select>
                    </div>

                    <div class="register-field">
                        <label for="mobile_number">Mobile Number</label>
                        <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="{{ old('mobile_number', '+7') }}" required>
                        @error('mobile_number')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="register-field">
                        <label for="password">Password</label>
                        <div class="register-password-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button type="button" id="togglePassword" class="register-password-toggle" aria-label="Show password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="register-field">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="register-password-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            <button type="button" id="toggleConfirmPassword" class="register-password-toggle" aria-label="Show confirm password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                        <div id="passwordMismatch" style="display:none;">
                            <strong>Passwords do not match</strong>
                        </div>
                    </div>

                    <div class="register-field register-field--full">
                        <label for="address">Address(City Only) (as per Passport)</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
                        @error('address')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="register-field">
                        <label for="religion">Religion</label>
                        <select class="form-control" id="religion" name="religion" required>
                            <option value="">Select Religion</option>
                            <option value="Muslim" {{ old('religion') == 'Muslim' ? 'selected' : '' }}>Muslim</option>
                            <option value="Hindu" {{ old('religion') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Boddho" {{ old('religion') == 'Boddho' ? 'selected' : '' }}>Boddho</option>
                            <option value="Cristan" {{ old('religion') == 'Cristan' ? 'selected' : '' }}>Cristan</option>
                        </select>
                        @error('religion')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="register-field">
                        <label for="gender">Gender</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="">Select Your Gender</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="register-field">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                        @error('date_of_birth')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="register-field">
                        <label for="course_type">Course Type</label>
                        <select class="form-control" id="course_type" name="course_type" required>
                            <option value="">Select Course Type</option>
                            <option value="Language" {{ old('course_type') == 'Language' ? 'selected' : '' }}>Language</option>
                            <option value="BSC" {{ old('course_type') == 'BSC' ? 'selected' : '' }}>BSC - Bachelor of Science</option>
                            <option value="MSC" {{ old('course_type') == 'MSC' ? 'selected' : '' }}>MSC - Master of Science</option>
                            <option value="PHD" {{ old('course_type') == 'PHD' ? 'selected' : '' }}>PHD</option>
                        </select>
                        @error('course_type')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="register-field register-field--full">
                        <label for="department">Department</label>
                        <select class="form-control" id="department" name="department" onchange="toggleOtherDepartment()">
                            <option value="">Select Your Department</option>
                            <option value="Prepetory Language Course" {{ old('department') == 'Prepetory Language Course' ? 'selected' : '' }}>Language Course</option>
                            <option value="Automobile" {{ old('department') == 'Automobile' ? 'selected' : '' }}>Automobile</option>
                            <option value="Forestry" {{ old('department') == 'Forestry' ? 'selected' : '' }}>Forestry</option>
                            <option value="Mechanical" {{ old('department') == 'Mechanical' ? 'selected' : '' }}>Mechanical</option>
                            <option value="Computer Science and Technology" {{ old('department') == 'Computer Science and Technology' ? 'selected' : '' }}>Information Technology / IT</option>
                            <option value="Economics" {{ old('department') == 'Economics' ? 'selected' : '' }}>Economics</option>
                            <option value="Landscape Architecture" {{ old('department') == 'Landscape Architecture' ? 'selected' : '' }}>Landscape Architecture</option>
                            <option value="Tourism" {{ old('department') == 'Tourism' ? 'selected' : '' }}>Tourism</option>
                            <option value="automation of production processes" {{ old('department') == 'automation of production processes' ? 'selected' : '' }}>automation of production processes</option>
                            <option value="Life Safety and Legal Relations" {{ old('department') == 'Life Safety and Legal Relations' ? 'selected' : '' }}>Life Safety and Legal Relations</option>
                            <option value="Botany and Plant Physiology" {{ old('department') == 'Botany and Plant Physiology' ? 'selected' : '' }}>Botany and Plant Physiology</option>
                            <option value="Hardware and Software" {{ old('department') == 'Hardware and Software' ? 'selected' : '' }}>Hardware and Software</option>
                            <!-- <option value="Other" {{ old('department') == 'Other' ? 'selected' : '' }}>Other</option> -->
                        </select>
                        @error('department')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="register-field register-field--full" id="other-department" style="display: none;">
                        <label for="other_department">Other Department</label>
                        <input type="text" class="form-control" id="other_department" name="other_department">
                        @error('other-department')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="course_year_group" class="register-field" style="display: none;">
                        <label for="course_year">Course Year</label>
                        <select class="form-control" id="course_year" name="course_year">
                            <option value="">Select Course Year</option>
                            <option value="1st Year" {{ old('course_year') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                            <option value="2nd Year" {{ old('course_year') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                            <option value="3rd Year" {{ old('course_year') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                            <option value="Final Year" {{ old('course_year') == 'Final Year' ? 'selected' : '' }}>Final Year</option>
                        </select>
                        @error('course_year')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="course_language_group" class="register-field" style="display: none;">
                        <label for="course_language">Course Language</label>
                        <select class="form-control" id="course_language" name="course_language">
                            <option value="">Select Course Language</option>
                            <option value="English" {{ old('course_language') == 'English' ? 'selected' : '' }}>English</option>
                            <option value="Russian" {{ old('course_language') == 'Russian' ? 'selected' : '' }}>Russian</option>
                        </select>
                        @error('course_language')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="register-field register-field--full">
                        <label for="photo">Upload Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" required>
                        <span class="register-error" style="color: #6f6572; font-weight: 500;">Allowed: JPG, JPEG, PNG, GIF. Max size: 2048 KB (2 MB).</span>
                        @error('photo')
                            <span class="register-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="register-submit">{{ __('Registration Now') }}</button>

                <div class="register-extra">
                    <div>{{ __("Have an account?") }}</div>
                    <a href="{{ route('login') }}" class="register-login-btn">Back to {{ __('Login') }}</a>
                </div>
            </form>
        </div>
    </div>
</section>

@if(session('message'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                title: "Registration Successful!",
                text: "{{ session('message') }}",
                icon: "success",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                }
            });
        });
    </script>
@endif

<script>
    function toggleOtherDepartment() {
        const departmentSelect = document.getElementById('department');
        const otherDepartmentInput = document.getElementById('other-department');
        if (departmentSelect.value === 'Other') {
            otherDepartmentInput.style.display = 'block';
        } else {
            otherDepartmentInput.style.display = 'none';
        }
    }
</script>

<script>
    function showErrorModal(message) {
        document.getElementById('errorMessage').innerText = message;
        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('password_confirmation');

        togglePassword.addEventListener('click', function () {
            const isPasswordHidden = passwordField.type === 'password';
            passwordField.type = isPasswordHidden ? 'text' : 'password';
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        toggleConfirmPassword.addEventListener('click', function () {
            const isConfirmPasswordHidden = confirmPasswordField.type === 'password';
            confirmPasswordField.type = isConfirmPasswordHidden ? 'text' : 'password';
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const courseTypeSelect = document.getElementById('course_type');
    const departmentSelect = document.getElementById('department');
    const courseLanguageGroup = document.getElementById('course_language_group');
    const courseYearGroup = document.getElementById('course_year_group');
    const form = document.querySelector('form');

    const toggleFields = () => {
        const selectedCourseType = courseTypeSelect.value;
        if (['BSC', 'MSC', 'PHD'].includes(selectedCourseType)) {
            courseLanguageGroup.style.display = 'block';
            courseYearGroup.style.display = 'block';
        } else {
            courseLanguageGroup.style.display = 'none';
            courseYearGroup.style.display = 'none';
        }
    };

    const handleCourseDepartmentDependency = () => {
        if (courseTypeSelect.value === 'Language') {
            departmentSelect.value = 'Prepetory Language Course';
            departmentSelect.setAttribute('disabled', 'disabled');
        } else {
            departmentSelect.removeAttribute('disabled');
        }
    };

    form.addEventListener('submit', function () {
        departmentSelect.removeAttribute('disabled');
    });

    courseTypeSelect.addEventListener('change', () => {
        toggleFields();
        handleCourseDepartmentDependency();
    });

    toggleFields();
    handleCourseDepartmentDependency();
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let countrySelect = document.getElementById("country");
    let mobileInput = document.getElementById("mobile_number");

    updateCountryCode();
    countrySelect.addEventListener("change", updateCountryCode);

    function updateCountryCode() {
        const countryCode = countrySelect.value === 'BD' ? '+88' : '+7';
        const currentValue = mobileInput.value;

        if (!currentValue || currentValue.startsWith('+7') || currentValue.startsWith('+88')) {
            mobileInput.value = countryCode;
        } else {
            mobileInput.value = countryCode + currentValue.substring(countryCode.length);
        }
    }

    mobileInput.addEventListener("input", function() {
        const countryCode = countrySelect.value === 'BD' ? '+88' : '+7';

        if (!mobileInput.value.startsWith(countryCode)) {
            const digits = mobileInput.value.replace(/\D/g, '').substring(countryCode.replace('+', '').length);
            mobileInput.value = countryCode + digits;
        }
    });

    mobileInput.addEventListener("keydown", function(e) {
        const countryCode = countrySelect.value === 'BD' ? '+88' : '+7';

        if ((e.key === "Backspace" || e.key === "Delete") &&
            mobileInput.selectionStart <= countryCode.length) {
            e.preventDefault();
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const photoInput = document.getElementById('photo');
    const form = photoInput ? photoInput.closest('form') : null;
    const maxPhotoSize = 2 * 1024 * 1024;

    if (!photoInput || !form) {
        return;
    }

    const validatePhotoSize = () => {
        const file = photoInput.files && photoInput.files[0];

        if (!file) {
            photoInput.setCustomValidity('');
            return true;
        }

        if (file.size > maxPhotoSize) {
            photoInput.setCustomValidity('Selected photo is larger than 2 MB.');
            alert('Selected photo is larger than 2 MB. Please choose a smaller file.');
            photoInput.value = '';
            return false;
        }

        photoInput.setCustomValidity('');
        return true;
    };

    photoInput.addEventListener('change', validatePhotoSize);

    form.addEventListener('submit', function (event) {
        if (!validatePhotoSize()) {
            event.preventDefault();
            photoInput.reportValidity();
        }
    });
});
</script>
@endsection
