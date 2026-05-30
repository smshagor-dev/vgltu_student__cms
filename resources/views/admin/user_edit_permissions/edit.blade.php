@extends('layouts.admin_app')

@section('content')
<style>
    .permission-grid {
        display: grid;
        gap: 18px;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    }

    .permission-option {
        position: relative;
    }

    .permission-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .permission-option label {
        display: block;
        height: 100%;
        padding: 18px;
        border-radius: 22px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        cursor: pointer;
        transition: 0.2s ease;
    }

    .permission-option label:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 30px rgba(15, 23, 42, 0.08);
    }

    .permission-option input:checked + label {
        border-color: rgba(37, 99, 235, 0.35);
        background: linear-gradient(180deg, rgba(219, 234, 254, 0.72), #ffffff);
        box-shadow: 0 18px 36px rgba(37, 99, 235, 0.12);
    }

    .permission-option strong {
        display: block;
        margin-bottom: 8px;
        color: #10213b;
        font-size: 1rem;
    }

    .permission-option p {
        margin: 0;
        color: #64748b;
        line-height: 1.6;
        font-size: 0.92rem;
    }

    .permission-check {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        margin-bottom: 14px;
        border-radius: 12px;
        background: rgba(37, 99, 235, 0.1);
        color: #1d4ed8;
    }

    .permission-note {
        padding: 18px 20px;
        border-radius: 20px;
        border: 1px dashed rgba(148, 163, 184, 0.35);
        background: #f8fbff;
        color: #475569;
    }
</style>

<div class="admin-page">
    <section class="admin-hero-card">
        <h2>User Edit Permissions</h2>
        <p>Choose exactly which profile fields students can update from `/user/edit`, including profile photo and password access.</p>
    </section>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.user-edit-permissions.update') }}" class="admin-form-shell">
        @csrf
        @method('PUT')

        <div class="admin-toolbar">
            <div class="admin-toolbar__title">
                <h3>Field Access Control</h3>
                <p>Enable only the profile inputs that students should be able to change on their own.</p>
            </div>
            <span class="admin-chip">
                <i class="fas fa-user-shield"></i>
                <span>{{ count($editableFields) }} enabled</span>
            </span>
        </div>

        <div class="permission-grid">
            @foreach ($fieldDefinitions as $fieldKey => $field)
                <div class="permission-option">
                    <input
                        type="checkbox"
                        name="user_editable_fields[]"
                        id="field_{{ $fieldKey }}"
                        value="{{ $fieldKey }}"
                        {{ in_array($fieldKey, old('user_editable_fields', $editableFields), true) ? 'checked' : '' }}
                    >
                    <label for="field_{{ $fieldKey }}">
                        <span class="permission-check"><i class="fas fa-check"></i></span>
                        <strong>{{ $field['label'] }}</strong>
                        <p>{{ $field['description'] }}</p>
                    </label>
                </div>
            @endforeach
        </div>

        <div class="permission-note mt-4">
            Enabled fields appear on the student profile edit page. If `Password` is disabled here, the password change section will be hidden from students.
        </div>

        <div class="admin-actions-inline mt-4">
            <button type="submit" class="btn btn-primary">Save Permissions</button>
        </div>
    </form>
</div>
@endsection
