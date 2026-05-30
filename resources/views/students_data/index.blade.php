@extends('layouts.app')

@section('content')
<style>
    .student-docs-page {
        background:
            radial-gradient(circle at top left, rgba(14, 165, 233, 0.08), transparent 28%),
            linear-gradient(180deg, #f8fbff 0%, #f3f7fc 100%);
        padding: 28px 0 56px;
    }

    .student-docs-wrap {
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .student-docs-wrap .alert {
        margin-bottom: 18px;
        border-radius: 16px;
    }

    .student-docs-hero {
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

    .student-docs-hero::after {
        content: "";
        position: absolute;
        right: -36px;
        bottom: -36px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
    }

    .student-docs-kicker {
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

    .student-docs-hero h1 {
        margin: 0;
        color: #fff;
        font-size: clamp(1.9rem, 3vw, 2.5rem);
        font-weight: 800;
    }

    .student-docs-hero p {
        margin: 10px 0 0;
        max-width: 760px;
        color: rgba(255, 255, 255, 0.82);
        line-height: 1.7;
    }

    .student-docs-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .student-docs-summary {
        display: grid;
        gap: 14px;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        flex: 1;
    }

    .student-docs-stat {
        padding: 16px 18px;
        border: 1px solid rgba(226, 232, 240, 0.95);
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
    }

    .student-docs-stat span {
        display: block;
        margin-bottom: 6px;
        color: #64748b;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .student-docs-stat strong {
        color: #0f172a;
        font-size: 1rem;
        font-weight: 800;
    }

    .student-docs-action {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 0.95rem 1.3rem;
        border-radius: 14px;
        background: linear-gradient(135deg, #2563eb, #0f766e);
        color: #fff;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 16px 28px rgba(37, 99, 235, 0.16);
    }

    .student-docs-card {
        overflow: hidden;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.97);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.07);
    }

    .student-docs-card__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        padding: 22px 24px;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(180deg, rgba(248, 250, 252, 0.92), rgba(255, 255, 255, 0.9));
    }

    .student-docs-card__header h2 {
        margin: 0;
        color: #10213b;
        font-size: 1.25rem;
        font-weight: 800;
    }

    .student-docs-card__header p {
        margin: 6px 0 0;
        color: #64748b;
    }

    .student-docs-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 13px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .student-docs-table-wrap {
        overflow-x: auto;
        padding: 0 8px 8px;
    }

    .student-docs-table {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0 14px;
    }

    .student-docs-table thead th {
        padding: 14px 16px 0;
        color: #64748b;
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border: 0;
        background: transparent;
        white-space: nowrap;
    }

    .student-docs-table tbody td {
        padding: 18px 16px;
        vertical-align: middle;
        border-top: 1px solid #eef2f7;
        border-bottom: 1px solid #eef2f7;
        background: #fff;
    }

    .student-docs-table tbody td:first-child {
        border-left: 1px solid #eef2f7;
        border-top-left-radius: 18px;
        border-bottom-left-radius: 18px;
    }

    .student-docs-table tbody td:last-child {
        border-right: 1px solid #eef2f7;
        border-top-right-radius: 18px;
        border-bottom-right-radius: 18px;
    }

    .student-docs-name {
        font-weight: 700;
        color: #0f172a;
    }

    .student-docs-date {
        display: inline-flex;
        padding: 8px 12px;
        border-radius: 999px;
        background: #f8fafc;
        color: #334155;
        font-size: 0.88rem;
        font-weight: 700;
    }

    .student-docs-thumb {
        width: 96px;
        height: 68px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid #dbe4ee;
        cursor: pointer;
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .student-docs-thumb:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 28px rgba(15, 23, 42, 0.12);
    }

    .student-docs-edit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.72rem 1rem;
        border-radius: 12px;
        background: #f59e0b;
        color: #fff;
        font-weight: 700;
        text-decoration: none;
    }

    .student-docs-empty {
        padding: 44px 24px;
        text-align: center;
        color: #64748b;
    }

    .student-docs-empty i {
        display: inline-grid;
        place-items: center;
        width: 64px;
        height: 64px;
        margin-bottom: 16px;
        border-radius: 20px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 1.35rem;
    }

    .student-docs-mobile {
        display: grid;
        gap: 18px;
    }

    .student-docs-mobile-card {
        border: 1px solid rgba(226, 232, 240, 0.95);
        border-radius: 22px;
        background: #fff;
        box-shadow: 0 16px 30px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .student-docs-mobile-card__head {
        padding: 18px 18px 14px;
        border-bottom: 1px solid #eef2f7;
        background: #f8fbff;
    }

    .student-docs-mobile-card__head h3 {
        margin: 0;
        color: #0f172a;
        font-size: 1.05rem;
        font-weight: 800;
    }

    .student-docs-mobile-card__body {
        padding: 18px;
    }

    .student-docs-mobile-list {
        display: grid;
        gap: 12px;
        margin-bottom: 18px;
    }

    .student-docs-mobile-item {
        padding: 12px 14px;
        border-radius: 14px;
        background: #f8fafc;
        border: 1px solid #edf2f7;
    }

    .student-docs-mobile-item span {
        display: block;
        margin-bottom: 4px;
        color: #64748b;
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .student-docs-mobile-gallery {
        display: grid;
        gap: 14px;
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .student-docs-mobile-gallery figure {
        margin: 0;
    }

    .student-docs-mobile-gallery figcaption {
        margin-top: 8px;
        color: #475569;
        font-size: 0.8rem;
        font-weight: 700;
        text-align: center;
    }

    .student-docs-modal-image {
        width: 100%;
        border-radius: 16px;
    }

    @media (max-width: 991.98px) {
        .student-docs-summary {
            grid-template-columns: 1fr;
        }

        .student-docs-toolbar {
            align-items: stretch;
        }

        .student-docs-action {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 767.98px) {
        .student-docs-page {
            padding-top: 20px;
        }

        .student-docs-hero,
        .student-docs-card__header {
            padding: 20px;
        }

        .student-docs-hero::after {
            display: none;
        }

        .student-docs-hero h1 {
            font-size: 1.65rem;
        }

        .student-docs-hero p {
            font-size: 0.95rem;
        }

        .student-docs-card {
            border-radius: 20px;
        }

        .student-docs-card__header {
            align-items: flex-start;
        }

        .student-docs-badge {
            width: 100%;
            justify-content: center;
        }

        .student-docs-mobile-card__body,
        .student-docs-mobile-card__head,
        .student-docs-empty {
            padding: 16px;
        }

        .student-docs-mobile-gallery {
            grid-template-columns: 1fr;
        }

        .student-docs-mobile-gallery figcaption {
            margin-top: 6px;
        }

        .student-docs-thumb,
        .student-docs-mobile-gallery .student-docs-thumb {
            height: 190px;
        }

        .student-docs-edit,
        .student-docs-empty .student-docs-action {
            width: 100%;
            justify-content: center;
        }

        .modal-dialog {
            margin: 0.75rem;
        }
    }

    @media (max-width: 479.98px) {
        .student-docs-wrap {
            padding: 0 12px;
        }

        .student-docs-hero,
        .student-docs-card__header {
            padding: 18px 16px;
        }

        .student-docs-kicker {
            font-size: 0.72rem;
            letter-spacing: 0.06em;
        }

        .student-docs-stat {
            padding: 14px;
        }

        .student-docs-mobile-item {
            padding: 11px 12px;
        }

        .student-docs-thumb,
        .student-docs-mobile-gallery .student-docs-thumb {
            height: 170px;
        }
    }
</style>

<div class="student-docs-page">
    <div class="student-docs-wrap">
        <section class="student-docs-hero">
            <span class="student-docs-kicker"><i class="far fa-id-card"></i> Student Records</span>
            <h1>Your Submitted Documents</h1>
            <p>Review your passport, visa, and green card records in one organized place. You can inspect submitted files and update your visa details whenever needed.</p>
        </section>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="student-docs-toolbar">
            <div class="student-docs-summary">
                <div class="student-docs-stat">
                    <span>Document Types</span>
                    <strong>Passport, Visa, Green Card</strong>
                </div>
                <div class="student-docs-stat">
                    <span>Student Name</span>
                    <strong>{{ optional($students->first())->full_name ?: auth()->user()->full_name }}</strong>
                </div>
            </div>

            <a href="{{ $students->isNotEmpty() ? route('students_data.edit', $students->first()->id) : route('students_data.create') }}" class="student-docs-action">
                <i class="fas {{ $students->isNotEmpty() ? 'fa-pen-to-square' : 'fa-cloud-upload-alt' }}"></i>
                <span>{{ $students->isNotEmpty() ? 'Update Your Documents' : 'Submit Your Documents' }}</span>
            </a>
        </div>

        <section class="student-docs-card">
            <div class="student-docs-card__header">
                <div>
                    <h2>Document History</h2>
                    <p>All files and date information submitted under your student account.</p>
                </div>
                <span class="student-docs-badge"><i class="fas fa-folder-open"></i> Secure Archive</span>
            </div>

            @if ($students->isNotEmpty())
                <div class="d-none d-md-block">
                    <div class="student-docs-table-wrap">
                        <table class="student-docs-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Passport Number</th>
                                    <th>Passport Photo</th>
                                    <th>Visa Start Date</th>
                                    <th>Visa Expiry Date</th>
                                    <th>Visa Photo</th>
                                    <th>Green Card Photo</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                    <tr>
                                        <td><span class="student-docs-name">{{ $student->full_name }}</span></td>
                                        <td>{{ $student->passport_number }}</td>
                                        <td>
                                            <img
                                                src="{{ asset('storage/' . $student->passport_photo) }}"
                                                alt="Passport Photo"
                                                class="student-docs-thumb clickable-image"
                                                data-image="{{ asset('storage/' . $student->passport_photo) }}"
                                            >
                                        </td>
                                        <td><span class="student-docs-date">{{ $student->visa_start_date }}</span></td>
                                        <td><span class="student-docs-date">{{ $student->visa_expiry_date }}</span></td>
                                        <td>
                                            <img
                                                src="{{ asset('storage/' . $student->visa_photo) }}"
                                                alt="Visa Photo"
                                                class="student-docs-thumb clickable-image"
                                                data-image="{{ asset('storage/' . $student->visa_photo) }}"
                                            >
                                        </td>
                                        <td>
                                            <img
                                                src="{{ asset('storage/' . $student->green_card_photo) }}"
                                                alt="Green Card Photo"
                                                class="student-docs-thumb clickable-image"
                                                data-image="{{ asset('storage/' . $student->green_card_photo) }}"
                                            >
                                        </td>
                                        <td>
                                            @if (auth()->id() == $student->user_id)
                                                <a href="{{ route('students_data.edit', $student->id) }}" class="student-docs-edit">
                                                    <i class="fas fa-pen"></i>
                                                    <span>Edit</span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-md-none">
                    <div class="student-docs-mobile">
                        @foreach ($students as $student)
                            <article class="student-docs-mobile-card">
                                <div class="student-docs-mobile-card__head">
                                    <h3>{{ $student->full_name }}</h3>
                                </div>

                                <div class="student-docs-mobile-card__body">
                                    <div class="student-docs-mobile-list">
                                        <div class="student-docs-mobile-item">
                                            <span>Passport Number</span>
                                            <strong>{{ $student->passport_number }}</strong>
                                        </div>
                                        <div class="student-docs-mobile-item">
                                            <span>Visa Start Date</span>
                                            <strong>{{ $student->visa_start_date }}</strong>
                                        </div>
                                        <div class="student-docs-mobile-item">
                                            <span>Visa Expiry Date</span>
                                            <strong>{{ $student->visa_expiry_date }}</strong>
                                        </div>
                                    </div>

                                    <div class="student-docs-mobile-gallery">
                                        <figure>
                                            <img
                                                src="{{ asset('storage/' . $student->passport_photo) }}"
                                                alt="Passport Photo"
                                                class="student-docs-thumb clickable-image w-100"
                                                data-image="{{ asset('storage/' . $student->passport_photo) }}"
                                            >
                                            <figcaption>Passport</figcaption>
                                        </figure>
                                        <figure>
                                            <img
                                                src="{{ asset('storage/' . $student->visa_photo) }}"
                                                alt="Visa Photo"
                                                class="student-docs-thumb clickable-image w-100"
                                                data-image="{{ asset('storage/' . $student->visa_photo) }}"
                                            >
                                            <figcaption>Visa</figcaption>
                                        </figure>
                                        <figure>
                                            <img
                                                src="{{ asset('storage/' . $student->green_card_photo) }}"
                                                alt="Green Card Photo"
                                                class="student-docs-thumb clickable-image w-100"
                                                data-image="{{ asset('storage/' . $student->green_card_photo) }}"
                                            >
                                            <figcaption>Green Card</figcaption>
                                        </figure>
                                    </div>

                                    @if (auth()->id() == $student->user_id)
                                        <div class="mt-4">
                                            <a href="{{ route('students_data.edit', $student->id) }}" class="student-docs-edit">
                                                <i class="fas fa-pen"></i>
                                                <span>Edit</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="student-docs-empty">
                    <i class="fas fa-folder-open"></i>
                    <h3>No Documents Found</h3>
                    <p class="mb-4">You have not submitted your student documents yet. Start by uploading your passport, visa, and green card information.</p>
                    <a href="{{ route('students_data.create') }}" class="student-docs-action">
                        <i class="fas fa-plus"></i>
                        <span>Submit Documents</span>
                    </a>
                </div>
            @endif
        </section>
    </div>
</div>

<div class="modal fade" id="documentPreviewModal" tabindex="-1" aria-labelledby="documentPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="documentPreviewModalLabel">Document Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <img src="" alt="Document Preview" id="documentPreviewImage" class="student-docs-modal-image">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalElement = document.getElementById('documentPreviewModal');
        const previewImage = document.getElementById('documentPreviewImage');

        if (!modalElement || !previewImage || typeof bootstrap === 'undefined') {
            return;
        }

        const previewModal = new bootstrap.Modal(modalElement);

        document.querySelectorAll('.clickable-image').forEach(function (image) {
            image.addEventListener('click', function () {
                previewImage.src = this.getAttribute('data-image');
                previewModal.show();
            });
        });
    });
</script>
@endsection
