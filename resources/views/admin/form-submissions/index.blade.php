@extends('layouts.admin_app')

@section('content')
<style>
    .form-submissions-admin {
        max-width: 1200px;
        margin: 0 auto;
    }

    .form-submissions-admin__hero {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        padding: 30px;
        background:
            radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 28%),
            linear-gradient(135deg, #0f4c81 0%, #2563eb 55%, #0f766e 100%);
        color: #fff;
        box-shadow: 0 24px 56px rgba(15, 76, 129, 0.18);
    }

    .form-submissions-admin__hero::after {
        content: "";
        position: absolute;
        right: -42px;
        bottom: -72px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }

    .form-submissions-admin__kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .form-submissions-admin__hero h1 {
        margin: 18px 0 10px;
        color: #fff;
        font-size: clamp(2rem, 4vw, 2.8rem);
        font-weight: 800;
    }

    .form-submissions-admin__hero p {
        max-width: 760px;
        margin: 0;
        color: rgba(255, 255, 255, 0.84);
        line-height: 1.75;
    }

    .form-submissions-admin__panel {
        margin-top: 24px;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
    }

    .form-submissions-admin__panel-head {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .form-submissions-admin__panel-head h2 {
        margin: 0 0 8px;
        color: #1f2937;
        font-size: 1.45rem;
        font-weight: 800;
    }

    .form-submissions-admin__panel-head p {
        margin: 0;
        color: #667085;
        line-height: 1.7;
    }

    .form-submissions-admin__badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 58px;
        min-height: 58px;
        border-radius: 18px;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 800;
    }

    .form-submissions-admin__stack {
        display: grid;
        gap: 18px;
    }

    .submission-card {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 22px;
        background: linear-gradient(180deg, #fff 0%, #f8fbff 100%);
        overflow: hidden;
    }

    .submission-card__head {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) repeat(2, minmax(150px, 0.5fr)) minmax(190px, 0.8fr);
        gap: 14px;
        padding: 20px;
        align-items: center;
        border-bottom: 1px solid rgba(15, 23, 42, 0.08);
    }

    .submission-card__title strong,
    .submission-card__title span,
    .submission-card__meta strong,
    .submission-card__meta span {
        display: block;
    }

    .submission-card__title strong {
        color: #111827;
        font-size: 1.04rem;
        font-weight: 800;
    }

    .submission-card__title span,
    .submission-card__meta span {
        margin-top: 4px;
        color: #6b7280;
        font-size: 0.88rem;
    }

    .submission-card__meta strong {
        color: #1f2937;
        font-size: 0.95rem;
        font-weight: 700;
    }

    .submission-card__actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 10px;
    }

    .submission-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 0;
        border-radius: 999px;
        padding: 10px 16px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .submission-btn--view {
        background: #dcfce7;
        color: #166534;
    }

    .submission-card__body {
        padding: 20px;
    }

    .submission-card__section-title {
        margin: 0 0 14px;
        color: #1f2937;
        font-size: 0.98rem;
        font-weight: 800;
    }

    .submission-card__empty {
        padding: 16px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px dashed rgba(15, 23, 42, 0.14);
        color: #64748b;
    }

    .submission-options-table {
        overflow-x: auto;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 18px;
        background: #fff;
    }

    .submission-options-table table {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .submission-options-table th,
    .submission-options-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    }

    .submission-options-table th {
        background: #f8fafc;
        color: #334155;
        font-size: 0.84rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .submission-options-table tr:last-child td {
        border-bottom: 0;
    }

    .submission-option-name strong,
    .submission-option-name span {
        display: block;
    }

    .submission-option-name strong {
        color: #111827;
        font-weight: 700;
    }

    .submission-option-name span {
        margin-top: 4px;
        color: #6b7280;
        font-size: 0.84rem;
    }

    .submission-count-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 0.82rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .submission-options-actions {
        display: flex;
        justify-content: center;
    }

    .form-submissions-admin__pagination {
        margin-top: 22px;
        display: flex;
        justify-content: center;
    }

    .form-submissions-admin__empty {
        margin-top: 24px;
        padding: 30px;
        border-radius: 24px;
        background: #fff;
        border: 1px dashed rgba(15, 23, 42, 0.18);
        text-align: center;
        color: #667085;
    }

    @media (max-width: 991px) {
        .submission-card__head {
            grid-template-columns: 1fr;
        }

        .submission-card__actions,
        .submission-options-actions {
            justify-content: flex-start;
        }

        .form-submissions-admin__panel-head {
            flex-direction: column;
        }
    }

    @media (max-width: 767px) {
        .form-submissions-admin__hero,
        .form-submissions-admin__panel,
        .form-submissions-admin__empty {
            padding: 20px;
            border-radius: 22px;
        }
    }
</style>

<div class="form-submissions-admin">
    <section class="form-submissions-admin__hero">
        <span class="form-submissions-admin__kicker"><i class="fas fa-folder-open"></i> Submission Overview</span>
        <h1>Form Submissions</h1>
        <p>Review each custom field, see option-level submission counts, inspect related user details inside the modal, and open full submission user lists without changing the existing workflow.</p>
    </section>

    @if($customFieldData->count())
        <section class="form-submissions-admin__panel">
            <div class="form-submissions-admin__panel-head">
                <div>
                    <h2>Submitted Fields</h2>
                    <p>Every field card below keeps the same submission counts, modal previews, and view actions that already exist in your current page.</p>
                </div>
                <div class="form-submissions-admin__badge">{{ $customFieldData->total() }}</div>
            </div>

            <div class="form-submissions-admin__stack">
                @foreach ($customFieldData as $index => $field)
                    <article class="submission-card">
                        <div class="submission-card__head">
                            <div class="submission-card__title">
                                <strong>{{ $customFieldData->firstItem() + $index }}. {{ $field->field_label }}</strong>
                                <span>Field ID: {{ $field->field_id }}</span>
                            </div>

                            <div class="submission-card__meta">
                                <span>Field Type</span>
                                <strong>{{ ucfirst(str_replace('_', ' ', $field->field_type)) }}</strong>
                            </div>

                            <div class="submission-card__meta">
                                <span>Total User Submissions</span>
                                <strong>{{ $field->submission_count }}</strong>
                            </div>

                            <div class="submission-card__actions">
                                <a href="{{ route('admin.form-submissions.view-users', $field->field_id) }}" class="submission-btn submission-btn--view">
                                    <i class="fas fa-users"></i>
                                    View Users
                                </a>
                            </div>
                        </div>

                        <div class="submission-card__body">
                            <div class="submission-card__section-title">Options And Submission Counts</div>

                            @if (!empty($field->options))
                                <div class="submission-options-table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Option</th>
                                                <th>Submission Count</th>
                                                <th class="text-center">Preview</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($field->options as $key => $option)
                                                <tr>
                                                    <td>
                                                        <div class="submission-option-name">
                                                            <strong>{{ $key + 1 }}. {{ $option }}</strong>
                                                            <span>Field Option Entry</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="submission-count-chip">{{ $field->option_counts[$option] ?? 0 }} submissions</span>
                                                    </td>
                                                    <td>
                                                        <div class="submission-options-actions">
                                                            <button
                                                                class="submission-btn submission-btn--view"
                                                                data-toggle="modal"
                                                                data-target="#viewOptionModal-{{ $field->field_id }}-{{ $key }}"
                                                            >
                                                                <i class="fas fa-eye"></i>
                                                                View
                                                            </button>
                                                        </div>

                                                        <div class="modal fade" id="viewOptionModal-{{ $field->field_id }}-{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="viewOptionModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="viewOptionModalLabel">Option Details</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <h6>Option: {{ $option }}</h6>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered table-sm">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Name</th>
                                                                                        <th>Room</th>
                                                                                        <th>Problem</th>
                                                                                        <th>Description</th>
                                                                                        <th>Status</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($userFieldData as $userField)
                                                                                        @php
                                                                                            $values = explode(',', $userField->value);
                                                                                        @endphp

                                                                                        @if (in_array($option, $values))
                                                                                            <tr>
                                                                                                <td>{{ $userField->full_name }}</td>
                                                                                                <td>{{ $userField->room_number }}</td>
                                                                                                <td>{{ $userField->value }}</td>
                                                                                                <td>{{ $userField->description }}</td>
                                                                                                <td>{{ $userField->status }}</td>
                                                                                            </tr>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="submission-card__empty">No options available for this field.</div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="form-submissions-admin__pagination">
                {{ $customFieldData->links() }}
            </div>
        </section>
    @else
        <div class="form-submissions-admin__empty">
            No custom field submissions were found yet.
        </div>
    @endif
</div>

<style>
@media (max-width: 576px) {
    .modal-dialog {
        max-width: 100%;
    }

    .modal-body {
        padding: 1rem;
    }

    .table-responsive {
        max-height: 300px;
        overflow-y: auto;
    }
}
</style>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
