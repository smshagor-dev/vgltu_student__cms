@extends('layouts.app')

@section('content')
@php
    $userHasRecord = \App\Models\StudentsData::where('user_id', auth()->id())->exists();
@endphp

<style>
    .student-upload-page {
        background:
            radial-gradient(circle at top left, rgba(14, 165, 233, 0.08), transparent 28%),
            linear-gradient(180deg, #f8fbff 0%, #f3f7fc 100%);
        padding: 28px 0 56px;
    }

    .student-upload-wrap {
        max-width: 1040px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .student-upload-wrap .alert {
        margin-bottom: 18px;
        border-radius: 16px;
    }

    .student-upload-hero {
        position: relative;
        overflow: hidden;
        padding: 28px 30px;
        margin-bottom: 24px;
        border-radius: 26px;
        color: #fff;
        background:
            radial-gradient(circle at top right, rgba(125, 211, 252, 0.22), transparent 24%),
            linear-gradient(135deg, #0f172a 0%, #1d4ed8 58%, #0f766e 100%);
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.14);
    }

    .student-upload-hero::after {
        content: "";
        position: absolute;
        right: -36px;
        bottom: -36px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
    }

    .student-upload-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        margin-bottom: 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .student-upload-hero h1 {
        margin: 0;
        color: #fff;
        font-size: clamp(1.9rem, 3vw, 2.5rem);
        font-weight: 800;
    }

    .student-upload-hero p {
        margin: 10px 0 0;
        max-width: 760px;
        color: rgba(255, 255, 255, 0.82);
        line-height: 1.7;
    }

    .student-upload-grid {
        display: grid;
        gap: 24px;
        grid-template-columns: 320px minmax(0, 1fr);
        align-items: start;
    }

    .student-upload-side,
    .student-upload-form,
    .student-upload-lock {
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.97);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.07);
    }

    .student-upload-side {
        padding: 22px;
        position: sticky;
        top: 110px;
    }

    .student-upload-side h2 {
        margin: 0 0 18px;
        color: #10213b;
        font-size: 1.1rem;
        font-weight: 800;
    }

    .student-upload-side-list {
        display: grid;
        gap: 14px;
    }

    .student-upload-side-item {
        padding: 14px 16px;
        border-radius: 16px;
        background: #f8fbff;
        border: 1px solid #e7eef7;
    }

    .student-upload-side-item span {
        display: block;
        margin-bottom: 5px;
        color: #64748b;
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .student-upload-side-item strong {
        color: #0f172a;
        font-size: 0.95rem;
        font-weight: 700;
    }

    .student-upload-form__header,
    .student-upload-lock__header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 24px 24px 20px;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(180deg, rgba(248, 250, 252, 0.92), rgba(255, 255, 255, 0.9));
    }

    .student-upload-form__header h2,
    .student-upload-lock__header h2 {
        margin: 0;
        color: #10213b;
        font-size: 1.3rem;
        font-weight: 800;
    }

    .student-upload-form__header p,
    .student-upload-lock__header p {
        margin: 6px 0 0;
        color: #64748b;
        line-height: 1.6;
    }

    .student-upload-badge {
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

    .student-upload-form__body,
    .student-upload-lock__body {
        padding: 24px;
    }

    .student-upload-form-grid {
        display: grid;
        gap: 18px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .student-upload-field {
        display: block;
    }

    .student-upload-field--full {
        grid-column: 1 / -1;
    }

    .student-upload-field label {
        display: block;
        margin-bottom: 8px;
        color: #374151;
        font-weight: 700;
    }

    .student-upload-field small {
        display: block;
        margin-top: 6px;
        color: #6b7280;
        line-height: 1.5;
    }

    .student-upload-input {
        min-height: 50px;
        border: 1px solid #d4dbe5;
        border-radius: 14px;
        background: #fff;
        box-shadow: none;
    }

    .student-upload-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.12);
    }

    .student-upload-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #eef2f7;
    }

    .student-upload-actions p {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
    }

    .student-upload-btn {
        min-width: 190px;
        border: 0;
        border-radius: 14px;
        font-weight: 700;
        padding: 0.85rem 1.35rem;
        background: linear-gradient(135deg, #2563eb, #0f766e);
        box-shadow: 0 14px 28px rgba(37, 99, 235, 0.16);
    }

    .student-upload-lock {
        overflow: hidden;
    }

    .student-upload-lock__body {
        text-align: center;
    }

    .student-upload-lock-icon {
        display: inline-grid;
        place-items: center;
        width: 72px;
        height: 72px;
        margin-bottom: 18px;
        border-radius: 22px;
        background: #fff7ed;
        color: #ea580c;
        font-size: 1.5rem;
    }

    .student-upload-lock__body h3 {
        margin-bottom: 10px;
        color: #0f172a;
        font-size: 1.2rem;
        font-weight: 800;
    }

    .student-upload-lock__body p {
        max-width: 560px;
        margin: 0 auto 22px;
        color: #64748b;
        line-height: 1.7;
    }

    .student-upload-link {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 0.85rem 1.2rem;
        border-radius: 14px;
        background: #0f172a;
        color: #fff;
        font-weight: 700;
        text-decoration: none;
    }

    @media (max-width: 991.98px) {
        .student-upload-grid {
            grid-template-columns: 1fr;
        }

        .student-upload-side {
            position: static;
        }
    }

    @media (max-width: 767.98px) {
        .student-upload-page {
            padding-top: 20px;
        }

        .student-upload-hero,
        .student-upload-form__header,
        .student-upload-form__body,
        .student-upload-lock__header,
        .student-upload-lock__body,
        .student-upload-side {
            padding: 20px;
        }

        .student-upload-form__header,
        .student-upload-lock__header {
            flex-direction: column;
        }

        .student-upload-hero::after {
            display: none;
        }

        .student-upload-hero h1 {
            font-size: 1.65rem;
        }

        .student-upload-hero p {
            font-size: 0.95rem;
        }

        .student-upload-form,
        .student-upload-side,
        .student-upload-lock {
            border-radius: 20px;
        }

        .student-upload-badge {
            width: 100%;
            justify-content: center;
        }

        .student-upload-form-grid {
            grid-template-columns: 1fr;
        }

        .student-upload-actions {
            align-items: stretch;
        }

        .student-upload-btn,
        .student-upload-link {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 479.98px) {
        .student-upload-wrap {
            padding: 0 12px;
        }

        .student-upload-hero,
        .student-upload-form__header,
        .student-upload-form__body,
        .student-upload-lock__header,
        .student-upload-lock__body,
        .student-upload-side {
            padding: 18px 16px;
        }

        .student-upload-kicker {
            font-size: 0.72rem;
            letter-spacing: 0.06em;
        }

        .student-upload-side-item {
            padding: 12px 14px;
        }

        .student-upload-lock-icon {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            font-size: 1.25rem;
        }
    }
</style>

<div class="student-upload-page">
    <div class="student-upload-wrap">
        <section class="student-upload-hero">
            <span class="student-upload-kicker"><i class="fas fa-file-upload"></i> Student Submission</span>
            <h1>Submit Student Documents</h1>
            <p>Upload your passport, visa, and green card details in one structured form. Please review the information carefully before submitting because this section is intended for one-time document collection.</p>
        </section>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>There were some problems with your input:</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        <div class="student-upload-grid">
            <aside class="student-upload-side">
                <h2>Before You Submit</h2>
                <div class="student-upload-side-list">
                    <div class="student-upload-side-item">
                        <span>Passport</span>
                        <strong>Clear passport number and readable passport image are required.</strong>
                    </div>
                    <div class="student-upload-side-item">
                        <span>Visa Dates</span>
                        <strong>Start and expiry dates must match your active visa document.</strong>
                    </div>
                    <div class="student-upload-side-item">
                        <span>Uploads</span>
                        <strong>Accepted format: JPG, JPEG, PNG with proper visibility.</strong>
                    </div>
                    <div class="student-upload-side-item">
                        <span>Submission Rule</span>
                        <strong>This form allows one initial submission per user account.</strong>
                    </div>
                </div>
            </aside>

            @if (! $userHasRecord)
                <section class="student-upload-form">
                    <div class="student-upload-form__header">
                        <div>
                            <h2>Document Information</h2>
                            <p>Complete each field carefully and upload the required document images before saving.</p>
                        </div>
                        <span class="student-upload-badge"><i class="fas fa-shield-check"></i> One-Time Submit</span>
                    </div>

                    <div class="student-upload-form__body">
                        <form action="{{ route('students_data.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="student-upload-form-grid">
                                <div class="student-upload-field">
                                    <label for="passport_number">Passport Number</label>
                                    <input type="text" id="passport_number" name="passport_number" class="form-control student-upload-input @error('passport_number') is-invalid @enderror" value="{{ old('passport_number') }}" required>
                                    <small>Enter the passport number exactly as shown on your passport.</small>
                                    @error('passport_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="student-upload-field">
                                    <label for="passport_photo">Passport Photo</label>
                                    <input type="file" id="passport_photo" name="passport_photo" class="form-control student-upload-input @error('passport_photo') is-invalid @enderror" required>
                                    <small>Upload a clear image of your passport document.</small>
                                    @error('passport_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="student-upload-field">
                                    <label for="visa_start_date">Visa Start Date</label>
                                    <input type="date" id="visa_start_date" name="visa_start_date" class="form-control student-upload-input @error('visa_start_date') is-invalid @enderror" value="{{ old('visa_start_date') }}" required>
                                    <small>Use the official starting date shown on your visa.</small>
                                    @error('visa_start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="student-upload-field">
                                    <label for="visa_expiry_date">Visa Expiry Date</label>
                                    <input type="date" id="visa_expiry_date" name="visa_expiry_date" class="form-control student-upload-input @error('visa_expiry_date') is-invalid @enderror" value="{{ old('visa_expiry_date') }}" required>
                                    <small>Make sure the expiry date matches the submitted visa image.</small>
                                    @error('visa_expiry_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="student-upload-field">
                                    <label for="visa_photo">Visa Photo</label>
                                    <input type="file" id="visa_photo" name="visa_photo" class="form-control student-upload-input @error('visa_photo') is-invalid @enderror" required>
                                    <small>Upload the current visa image with visible date and details.</small>
                                    @error('visa_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="student-upload-field">
                                    <label for="green_card_photo">Green Card Photo</label>
                                    <input type="file" id="green_card_photo" name="green_card_photo" class="form-control student-upload-input @error('green_card_photo') is-invalid @enderror" required>
                                    <small>Upload a readable image of your green card document.</small>
                                    @error('green_card_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="student-upload-actions">
                                <p>Please verify all details and uploads before final submission.</p>
                                <button type="submit" class="btn btn-primary student-upload-btn">Save Student Data</button>
                            </div>
                        </form>
                    </div>
                </section>
            @else
                <section class="student-upload-lock">
                    <div class="student-upload-lock__header">
                        <div>
                            <h2>Submission Locked</h2>
                            <p>Your account already has a student document record, so a second initial submission is not allowed.</p>
                        </div>
                        <span class="student-upload-badge"><i class="fas fa-lock"></i> Already Submitted</span>
                    </div>

                    <div class="student-upload-lock__body">
                        <div class="student-upload-lock-icon">
                            <i class="fas fa-file-circle-check"></i>
                        </div>
                        <h3>You have already submitted your student data</h3>
                        <p>Your passport, visa, and green card record is already stored. If you need to review or update the available document details, use your document history page instead of submitting again.</p>
                        <a href="{{ route('students_data.index') }}" class="student-upload-link">
                            <i class="fas fa-arrow-left"></i>
                            <span>Go to Document History</span>
                        </a>
                    </div>
                </section>
            @endif
        </div>
    </div>
</div>
@endsection
