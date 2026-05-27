@extends('layouts.app')

@section('content')
<style>
    .student-edit-page {
        background:
            radial-gradient(circle at top left, rgba(14, 165, 233, 0.08), transparent 28%),
            linear-gradient(180deg, #f8fbff 0%, #f3f7fc 100%);
        padding: 28px 0 56px;
    }

    .student-edit-wrap {
        max-width: 1040px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .student-edit-wrap .alert {
        margin-bottom: 18px;
        border-radius: 16px;
    }

    .student-edit-hero {
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

    .student-edit-hero::after {
        content: "";
        position: absolute;
        right: -36px;
        bottom: -36px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
    }

    .student-edit-kicker {
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

    .student-edit-hero h1 {
        margin: 0;
        color: #fff;
        font-size: clamp(1.9rem, 3vw, 2.5rem);
        font-weight: 800;
    }

    .student-edit-hero p {
        margin: 10px 0 0;
        max-width: 760px;
        color: rgba(255, 255, 255, 0.82);
        line-height: 1.7;
    }

    .student-edit-grid {
        display: grid;
        gap: 24px;
        grid-template-columns: 320px minmax(0, 1fr);
        align-items: start;
    }

    .student-edit-side,
    .student-edit-form {
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.97);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.07);
    }

    .student-edit-side {
        padding: 22px;
        position: sticky;
        top: 110px;
    }

    .student-edit-side h2 {
        margin: 0 0 18px;
        color: #10213b;
        font-size: 1.1rem;
        font-weight: 800;
    }

    .student-edit-side-list {
        display: grid;
        gap: 14px;
    }

    .student-edit-side-item {
        padding: 14px 16px;
        border-radius: 16px;
        background: #f8fbff;
        border: 1px solid #e7eef7;
    }

    .student-edit-side-item span {
        display: block;
        margin-bottom: 5px;
        color: #64748b;
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .student-edit-side-item strong {
        color: #0f172a;
        font-size: 0.95rem;
        font-weight: 700;
    }

    .student-edit-form__header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 24px 24px 20px;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(180deg, rgba(248, 250, 252, 0.92), rgba(255, 255, 255, 0.9));
    }

    .student-edit-form__header h2 {
        margin: 0;
        color: #10213b;
        font-size: 1.3rem;
        font-weight: 800;
    }

    .student-edit-form__header p {
        margin: 6px 0 0;
        color: #64748b;
        line-height: 1.6;
    }

    .student-edit-badge {
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

    .student-edit-form__body {
        padding: 24px;
    }

    .student-edit-fields {
        display: grid;
        gap: 18px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .student-edit-field {
        display: block;
    }

    .student-edit-field--full {
        grid-column: 1 / -1;
    }

    .student-edit-field label {
        display: block;
        margin-bottom: 8px;
        color: #374151;
        font-weight: 700;
    }

    .student-edit-field small {
        display: block;
        margin-top: 6px;
        color: #6b7280;
        line-height: 1.5;
    }

    .student-edit-input {
        min-height: 50px;
        border: 1px solid #d4dbe5;
        border-radius: 14px;
        background: #fff;
        box-shadow: none;
    }

    .student-edit-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.12);
    }

    .student-edit-preview-grid {
        display: grid;
        gap: 18px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .student-edit-date-grid {
        display: grid;
        gap: 18px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        margin-top: 24px;
    }

    .student-edit-date-card {
        padding: 16px;
        border: 1px solid #e7eef7;
        border-radius: 18px;
        background: #f8fbff;
    }

    .student-edit-date-card span {
        display: block;
        margin-bottom: 8px;
        color: #475569;
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .student-edit-date-card strong {
        color: #0f172a;
        font-size: 1rem;
        font-weight: 800;
    }

    .student-edit-preview {
        padding: 16px;
        border: 1px solid #e7eef7;
        border-radius: 18px;
        background: #f8fbff;
    }

    .student-edit-preview span {
        display: block;
        margin-bottom: 10px;
        color: #475569;
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .student-edit-preview img {
        width: 100%;
        height: 190px;
        object-fit: cover;
        border-radius: 16px;
        border: 1px solid #dbe4ee;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08);
    }

    .student-edit-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #eef2f7;
    }

    .student-edit-actions p {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
    }

    .student-edit-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .student-edit-btn,
    .student-edit-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-width: 180px;
        border-radius: 14px;
        font-weight: 700;
        padding: 0.85rem 1.3rem;
        text-decoration: none;
    }

    .student-edit-btn {
        border: 0;
        background: linear-gradient(135deg, #2563eb, #0f766e);
        box-shadow: 0 14px 28px rgba(37, 99, 235, 0.16);
    }

    .student-edit-link {
        background: #e2e8f0;
        color: #0f172a;
    }

    @media (max-width: 991.98px) {
        .student-edit-grid {
            grid-template-columns: 1fr;
        }

        .student-edit-side {
            position: static;
        }
    }

    @media (max-width: 767.98px) {
        .student-edit-page {
            padding-top: 20px;
        }

        .student-edit-hero,
        .student-edit-side,
        .student-edit-form__header,
        .student-edit-form__body {
            padding: 20px;
        }

        .student-edit-form__header {
            flex-direction: column;
        }

        .student-edit-hero::after {
            display: none;
        }

        .student-edit-hero h1 {
            font-size: 1.65rem;
        }

        .student-edit-hero p {
            font-size: 0.95rem;
        }

        .student-edit-side,
        .student-edit-form {
            border-radius: 20px;
        }

        .student-edit-badge {
            width: 100%;
            justify-content: center;
        }

        .student-edit-fields,
        .student-edit-date-grid,
        .student-edit-preview-grid {
            grid-template-columns: 1fr;
        }

        .student-edit-preview img {
            height: 220px;
        }

        .student-edit-actions,
        .student-edit-buttons {
            align-items: stretch;
        }

        .student-edit-btn,
        .student-edit-link {
            width: 100%;
        }
    }

    @media (max-width: 479.98px) {
        .student-edit-wrap {
            padding: 0 12px;
        }

        .student-edit-hero,
        .student-edit-side,
        .student-edit-form__header,
        .student-edit-form__body {
            padding: 18px 16px;
        }

        .student-edit-kicker {
            font-size: 0.72rem;
            letter-spacing: 0.06em;
        }

        .student-edit-side-item,
        .student-edit-date-card,
        .student-edit-preview {
            padding: 12px 14px;
        }

        .student-edit-preview img {
            height: 180px;
        }
    }
</style>

<div class="student-edit-page">
    <div class="student-edit-wrap">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <section class="student-edit-hero">
            <span class="student-edit-kicker"><i class="fas fa-pen-to-square"></i> Document Update</span>
            <h1>Edit Student Documents</h1>
            <p>Update your passport and visa information from one clean workspace. The layout is optimized for both desktop and mobile so your document details remain easy to review and edit.</p>
        </section>

        <div class="student-edit-grid">
            <aside class="student-edit-side">
                <h2>Editable Details</h2>
                <div class="student-edit-side-list">
                    <div class="student-edit-side-item">
                        <span>Passport</span>
                        <strong>You can now update passport number and passport photo here.</strong>
                    </div>
                    <div class="student-edit-side-item">
                        <span>Visa Dates</span>
                        <strong>Keep start and expiry dates aligned with your current visa.</strong>
                    </div>
                    <div class="student-edit-side-item">
                        <span>Visa Photo</span>
                        <strong>Replace the visa image only if you have a newer valid file.</strong>
                    </div>
                </div>
            </aside>

            <section class="student-edit-form">
                <div class="student-edit-form__header">
                    <div>
                        <h2>Update Record</h2>
                        <p>Review the current files below and replace only the fields that need changes.</p>
                    </div>
                    <span class="student-edit-badge"><i class="fas fa-file-pen"></i> Edit Mode</span>
                </div>

                <div class="student-edit-form__body">
                    <form action="{{ route('students_data.update', $studentData->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="student-edit-fields">
                            <div class="student-edit-field">
                                <label for="passport_number">Passport Number</label>
                                <input type="text" id="passport_number" name="passport_number" class="form-control student-edit-input @error('passport_number') is-invalid @enderror" value="{{ old('passport_number', $studentData->passport_number) }}" required>
                                <small>Use the exact passport number from your current document.</small>
                                @error('passport_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="student-edit-field">
                                <label for="passport_photo">Passport Photo</label>
                                <input type="file" id="passport_photo" name="passport_photo" class="form-control student-edit-input @error('passport_photo') is-invalid @enderror">
                                <small>Leave empty if you want to keep the existing passport image.</small>
                                @error('passport_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="student-edit-field">
                                <label for="visa_start_date">Visa Start Date</label>
                                <input type="date" id="visa_start_date" name="visa_start_date" class="form-control student-edit-input @error('visa_start_date') is-invalid @enderror" value="{{ old('visa_start_date', $studentData->visa_start_date) }}" required>
                                @error('visa_start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="student-edit-field">
                                <label for="visa_expiry_date">Visa Expiry Date</label>
                                <input type="date" id="visa_expiry_date" name="visa_expiry_date" class="form-control student-edit-input @error('visa_expiry_date') is-invalid @enderror" value="{{ old('visa_expiry_date', $studentData->visa_expiry_date) }}" required>
                                @error('visa_expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="student-edit-field student-edit-field--full">
                                <label for="visa_photo">Visa Photo</label>
                                <input type="file" id="visa_photo" name="visa_photo" class="form-control student-edit-input @error('visa_photo') is-invalid @enderror">
                                <small>Leave empty if the existing visa image is still valid.</small>
                                @error('visa_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="student-edit-date-grid">
                            <div class="student-edit-date-card">
                                <span>Current Visa Start Date</span>
                                <strong>{{ \Carbon\Carbon::parse($studentData->visa_start_date)->format('d M Y') }}</strong>
                            </div>
                            <div class="student-edit-date-card">
                                <span>Current Visa Expiry Date</span>
                                <strong>{{ \Carbon\Carbon::parse($studentData->visa_expiry_date)->format('d M Y') }}</strong>
                            </div>
                        </div>

                        <div class="student-edit-preview-grid mt-4">
                            <div class="student-edit-preview">
                                <span>Current Passport Photo</span>
                                <img src="{{ asset('storage/' . $studentData->passport_photo) }}" alt="Current Passport Photo">
                            </div>
                            <div class="student-edit-preview">
                                <span>Current Visa Photo</span>
                                <img src="{{ asset('storage/' . $studentData->visa_photo) }}" alt="Current Visa Photo">
                            </div>
                        </div>

                        <div class="student-edit-actions">
                            <p>Update only the fields that need correction, then save your document record.</p>
                            <div class="student-edit-buttons">
                                <a href="{{ route('students_data.index') }}" class="student-edit-link">
                                    <i class="fas fa-arrow-left"></i>
                                    <span>Back to List</span>
                                </a>
                                <button type="submit" class="btn btn-primary student-edit-btn">
                                    <i class="fas fa-save"></i>
                                    <span>Update Details</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
