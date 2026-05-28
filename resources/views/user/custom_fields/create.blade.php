@extends('layouts.app')

@section('content')
<style>
    .custom-fields-page {
        padding: 32px 0 72px;
    }

    .custom-fields-shell {
        max-width: 980px;
        margin: 0 auto;
    }

    .custom-fields-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 32px;
        background:
            radial-gradient(circle at top right, rgba(241, 115, 170, 0.22), transparent 32%),
            linear-gradient(135deg, #241726 0%, #4c2a41 55%, #bb3e71 100%);
        color: #fff;
        box-shadow: 0 26px 60px rgba(36, 23, 38, 0.18);
    }

    .custom-fields-hero::after {
        content: "";
        position: absolute;
        inset: auto -40px -60px auto;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }

    .custom-fields-hero__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .custom-fields-hero h1 {
        margin: 18px 0 12px;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        line-height: 1.08;
    }

    .custom-fields-hero p {
        max-width: 680px;
        margin: 0;
        color: rgba(255, 255, 255, 0.82);
        font-size: 1rem;
        line-height: 1.8;
    }

    .custom-fields-hero__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 24px;
    }

    .custom-fields-hero__meta-item {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        font-weight: 600;
    }

    .custom-fields-hero__meta-item i {
        color: #ffd8e8;
    }

    .custom-fields-alert {
        margin-top: 24px;
        border: 0;
        border-radius: 18px;
        padding: 16px 18px;
        box-shadow: 0 16px 40px rgba(36, 23, 38, 0.08);
    }

    .custom-fields-card {
        margin-top: 28px;
        background: #fff;
        border: 1px solid rgba(76, 42, 65, 0.08);
        border-radius: 28px;
        padding: 28px;
        box-shadow: 0 22px 60px rgba(76, 42, 65, 0.1);
    }

    .custom-fields-card__header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
    }

    .custom-fields-card__header h2 {
        margin: 0 0 8px;
        color: #241726;
        font-size: 1.5rem;
        font-weight: 800;
    }

    .custom-fields-card__header p {
        margin: 0;
        color: #6f6572;
        line-height: 1.7;
    }

    .custom-fields-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 58px;
        min-height: 58px;
        padding: 10px;
        border-radius: 18px;
        background: #fff5f8;
        color: #bb3e71;
        font-size: 1.1rem;
        font-weight: 800;
    }

    .custom-fields-stack {
        display: grid;
        gap: 20px;
    }

    .custom-field-block {
        padding: 22px;
        border: 1px solid rgba(76, 42, 65, 0.1);
        border-radius: 24px;
        background: linear-gradient(180deg, #ffffff 0%, #fff9fb 100%);
    }

    .custom-field-block__top {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 16px;
    }

    .custom-field-block__label {
        margin: 0;
        color: #241726;
        font-size: 1.15rem;
        font-weight: 800;
    }

    .custom-field-block__caption {
        margin-top: 6px;
        color: #7a6d79;
        line-height: 1.65;
    }

    .custom-field-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #f8e5ee;
        color: #9e2f5b;
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .custom-field-control,
    .custom-field-control.form-control {
        min-height: 54px;
        border-radius: 16px;
        border: 1px solid rgba(76, 42, 65, 0.14);
        box-shadow: none;
    }

    .custom-field-control:focus,
    .custom-field-control.form-control:focus {
        border-color: rgba(187, 62, 113, 0.4);
        box-shadow: 0 0 0 0.2rem rgba(187, 62, 113, 0.12);
    }

    .custom-field-file {
        padding: 14px 16px;
    }

    .custom-field-options {
        display: grid;
        gap: 14px;
    }

    .custom-field-option {
        padding: 16px 18px;
        border-radius: 18px;
        background: #fff;
        border: 1px solid rgba(76, 42, 65, 0.08);
    }

    .custom-field-option__check {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
    }

    .custom-field-option__check input[type="checkbox"] {
        width: 18px;
        height: 18px;
        margin-top: 0;
        accent-color: #bb3e71;
    }

    .custom-field-option__check label {
        color: #241726;
        font-weight: 600;
        cursor: pointer;
    }

    .custom-field-description {
        margin-top: 14px;
    }

    .custom-field-description textarea {
        min-height: 120px;
        border-radius: 16px;
        border: 1px solid rgba(76, 42, 65, 0.14);
        box-shadow: none;
    }

    .custom-field-description textarea:focus {
        border-color: rgba(187, 62, 113, 0.4);
        box-shadow: 0 0 0 0.2rem rgba(187, 62, 113, 0.12);
    }

    .custom-fields-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 26px;
    }

    .custom-fields-submit {
        border: 0;
        border-radius: 999px;
        padding: 14px 28px;
        background: linear-gradient(135deg, #241726, #bb3e71);
        color: #fff;
        font-weight: 700;
        box-shadow: 0 14px 30px rgba(187, 62, 113, 0.24);
    }

    .custom-fields-submit:hover,
    .custom-fields-submit:focus {
        color: #fff;
    }

    .custom-fields-empty {
        margin-top: 28px;
        padding: 34px 28px;
        border-radius: 28px;
        background: #fff;
        border: 1px solid rgba(76, 42, 65, 0.08);
        box-shadow: 0 22px 60px rgba(76, 42, 65, 0.1);
        text-align: center;
    }

    .custom-fields-empty__icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 18px;
        border-radius: 22px;
        display: grid;
        place-items: center;
        background: #fff3cd;
        color: #b78103;
        font-size: 1.8rem;
    }

    .custom-fields-empty h2 {
        margin-bottom: 10px;
        color: #241726;
        font-size: 1.5rem;
        font-weight: 800;
    }

    .custom-fields-empty p {
        margin: 0;
        color: #6f6572;
        line-height: 1.7;
    }

    @media (max-width: 767px) {
        .custom-fields-page {
            padding: 20px 0 56px;
        }

        .custom-fields-hero,
        .custom-fields-card,
        .custom-fields-empty {
            padding: 22px;
            border-radius: 22px;
        }

        .custom-fields-card__header,
        .custom-field-block__top {
            flex-direction: column;
        }

        .custom-fields-actions {
            justify-content: stretch;
        }

        .custom-fields-submit {
            width: 100%;
        }
    }
</style>

<div class="container custom-fields-page">
    <div class="custom-fields-shell">
        <section class="custom-fields-hero">
            <span class="custom-fields-hero__eyebrow">
                <i class="fas fa-file-signature"></i>
                Student Service Form
            </span>
            <h1>Submit Your Required Data</h1>
            <p>Fill out the available fields below and send your information without changing any of the existing submission flow.</p>
            <div class="custom-fields-hero__meta">
                <div class="custom-fields-hero__meta-item">
                    <i class="fas fa-layer-group"></i>
                    <span>{{ $unfilledFields->count() }} field{{ $unfilledFields->count() === 1 ? '' : 's' }} available</span>
                </div>
                <div class="custom-fields-hero__meta-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Your submission goes directly to the current workflow</span>
                </div>
            </div>
        </section>

        @if (session('success'))
            <div class="alert alert-success custom-fields-alert">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger custom-fields-alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($unfilledFields->isNotEmpty())
            <section class="custom-fields-card">
                <div class="custom-fields-card__header">
                    <div>
                        <h2>Complete The Form</h2>
                        <p>Each section can now be submitted individually. Submit one field at a time and continue with the next one.</p>
                    </div>
                    <div class="custom-fields-count">{{ $unfilledFields->count() }}</div>
                </div>

                <div class="custom-fields-stack">
                    @foreach ($unfilledFields as $field)
                        @php
                            $fieldOptions = $field->getRelation('options') ?? collect();
                        @endphp
                        <form method="POST" action="{{ route('user-fields.store') }}" enctype="multipart/form-data" class="custom-field-block">
                            @csrf
                            <input type="hidden" name="submitted_field_id" value="{{ $field->id }}">

                                <div class="custom-field-block__top">
                                    <div>
                                        <label for="field_{{ $field->id }}" class="custom-field-block__label">{{ e($field->field_label) }}</label>
                                        <div class="custom-field-block__caption">
                                            @if ($field->field_type === 'text')
                                                Enter the requested information in the text field below.
                                            @elseif ($field->field_type === 'image')
                                                Upload a clear image file in JPG or PNG format.
                                            @elseif ($field->field_type === 'multiple_choice')
                                                Select one or more matching options. A description box will appear for each checked item.
                                            @endif
                                        </div>
                                    </div>
                                    <span class="custom-field-badge">
                                        <i class="fas fa-tag"></i>
                                        {{ str_replace('_', ' ', $field->field_type) }}
                                    </span>
                                </div>

                                @if ($field->field_type === 'text')
                                    <input
                                        type="text"
                                        name="field_{{ $field->id }}"
                                        class="form-control custom-field-control"
                                        id="field_{{ $field->id }}"
                                        value="{{ old('field_' . $field->id) }}"
                                        placeholder="Enter {{ e($field->field_label) }}"
                                        required
                                    >
                                @elseif ($field->field_type === 'image')
                                    <input
                                        type="file"
                                        name="field_{{ $field->id }}"
                                        class="form-control custom-field-control custom-field-file"
                                        id="field_{{ $field->id }}"
                                        accept="image/jpeg, image/png, image/jpg"
                                        required
                                    >
                                @elseif ($field->field_type === 'multiple_choice')
                                    @if ($fieldOptions->isNotEmpty())
                                        <div class="custom-field-options">
                                            @foreach ($fieldOptions as $option)
                                                @php
                                                    $isChecked = in_array($option->option_value, old('field_' . $field->id, []), true);
                                                    $oldDescriptions = old('description_' . $field->id, []);
                                                @endphp
                                                <div class="custom-field-option">
                                                    <div class="custom-field-option__check">
                                                        <input
                                                            type="checkbox"
                                                            name="field_{{ $field->id }}[]"
                                                            value="{{ e($option->option_value) }}"
                                                            id="option_{{ $field->id }}_{{ $loop->index }}"
                                                            class="form-check-input"
                                                            {{ $isChecked ? 'checked' : '' }}
                                                        >
                                                        <label class="form-check-label" for="option_{{ $field->id }}_{{ $loop->index }}">
                                                            {{ e($option->option_value) }}
                                                        </label>
                                                    </div>

                                                    <div class="custom-field-description">
                                                        <textarea
                                                            name="description_{{ $field->id }}[{{ e($option->option_value) }}]"
                                                            class="form-control description-field"
                                                            placeholder="Add a description for {{ e($option->option_value) }}"
                                                            style="display: {{ $isChecked ? 'block' : 'none' }};"
                                                        >{{ $oldDescriptions[$option->option_value] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">No options available.</p>
                                    @endif
                                @endif
                                <div class="custom-fields-actions">
                                    <button type="submit" class="custom-fields-submit">Submit This Section</button>
                                </div>
                            </form>
                    @endforeach
                </div>
            </section>
        @else
            <section class="custom-fields-empty">
                <div class="custom-fields-empty__icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h2>No New Fields Available</h2>
                <p>
                    @if($submittedData->isNotEmpty())
                        The form has already been submitted for your room.
                    @else
                        All available fields have already been filled out.
                    @endif
                </p>
            </section>
        @endif
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("input[type='checkbox']").forEach(function (checkbox) {
        checkbox.addEventListener("change", function () {
            let fieldIdMatch = this.name.match(/field_(\d+)/);
            if (!fieldIdMatch) return;

            let fieldId = fieldIdMatch[1];
            let descriptionField = document.querySelector(`textarea[name="description_${fieldId}[${CSS.escape(this.value)}]"]`);

            if (descriptionField) {
                if (this.checked) {
                    descriptionField.style.display = "block";
                    descriptionField.setAttribute("required", "required");
                } else {
                    descriptionField.style.display = "none";
                    descriptionField.removeAttribute("required");
                }
            }
        });
    });

    document.querySelectorAll("input[type='checkbox']:checked").forEach(function (checkbox) {
        let fieldIdMatch = checkbox.name.match(/field_(\d+)/);
        if (!fieldIdMatch) return;

        let fieldId = fieldIdMatch[1];
        let descriptionField = document.querySelector(`textarea[name="description_${fieldId}[${CSS.escape(checkbox.value)}]"]`);

        if (descriptionField) {
            descriptionField.style.display = "block";
            descriptionField.setAttribute("required", "required");
        }
    });
});
</script>
@endsection
