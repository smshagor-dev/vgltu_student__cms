@extends('layouts.admin_app')

@section('content')
<style>
    .custom-fields-admin {
        max-width: 1180px;
        margin: 0 auto;
    }

    .custom-fields-admin__hero {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        padding: 30px;
        background:
            radial-gradient(circle at top right, rgba(255, 255, 255, 0.2), transparent 28%),
            linear-gradient(135deg, #1e3c72 0%, #2a5298 58%, #4a7bd1 100%);
        color: #fff;
        box-shadow: 0 24px 56px rgba(30, 60, 114, 0.18);
    }

    .custom-fields-admin__hero::after {
        content: "";
        position: absolute;
        right: -42px;
        bottom: -72px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }

    .custom-fields-admin__kicker {
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

    .custom-fields-admin__hero h1 {
        margin: 18px 0 10px;
        color: #fff;
        font-size: clamp(2rem, 4vw, 2.8rem);
        font-weight: 800;
    }

    .custom-fields-admin__hero p {
        max-width: 720px;
        margin: 0;
        color: rgba(255, 255, 255, 0.84);
        line-height: 1.75;
    }

    .custom-fields-admin__actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 24px;
    }

    .custom-fields-admin__create {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: 0;
        border-radius: 999px;
        padding: 12px 20px;
        background: #fff;
        color: #1e3c72;
        text-decoration: none;
        font-weight: 700;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.12);
    }

    .custom-fields-admin__alert {
        margin-top: 20px;
        border: 0;
        border-radius: 18px;
        padding: 16px 18px;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.08);
    }

    .custom-fields-admin__panel {
        margin-top: 24px;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
    }

    .custom-fields-admin__panel-head {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .custom-fields-admin__panel-head h2 {
        margin: 0 0 8px;
        color: #1f2937;
        font-size: 1.45rem;
        font-weight: 800;
    }

    .custom-fields-admin__panel-head p {
        margin: 0;
        color: #667085;
        line-height: 1.7;
    }

    .custom-fields-admin__badge {
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

    .custom-fields-admin__stack {
        display: grid;
        gap: 18px;
    }

    .custom-field-card {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 22px;
        background: linear-gradient(180deg, #fff 0%, #f8fbff 100%);
        overflow: hidden;
    }

    .custom-field-card__head {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) repeat(2, minmax(140px, 0.6fr)) minmax(190px, 0.9fr);
        gap: 14px;
        padding: 20px;
        align-items: center;
        border-bottom: 1px solid rgba(15, 23, 42, 0.08);
    }

    .custom-field-card__title strong,
    .custom-field-card__title span,
    .custom-field-card__meta strong,
    .custom-field-card__meta span {
        display: block;
    }

    .custom-field-card__title strong {
        color: #111827;
        font-size: 1.04rem;
        font-weight: 800;
    }

    .custom-field-card__title span,
    .custom-field-card__meta span {
        margin-top: 4px;
        color: #6b7280;
        font-size: 0.88rem;
    }

    .custom-field-card__meta strong {
        color: #1f2937;
        font-size: 0.95rem;
        font-weight: 700;
    }

    .custom-field-card__chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        border-radius: 999px;
        background: #eef2ff;
        color: #4338ca;
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: capitalize;
    }

    .custom-field-card__actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 10px;
    }

    .custom-field-card__btn {
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

    .custom-field-card__btn--edit {
        background: #fef3c7;
        color: #92400e;
    }

    .custom-field-card__btn--delete {
        background: #fee2e2;
        color: #b91c1c;
    }

    .custom-field-card__body {
        padding: 20px;
    }

    .custom-field-card__options-title {
        margin: 0 0 14px;
        color: #1f2937;
        font-size: 0.98rem;
        font-weight: 800;
    }

    .custom-field-card__empty {
        padding: 16px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px dashed rgba(15, 23, 42, 0.14);
        color: #64748b;
    }

    .custom-field-options-table {
        overflow-x: auto;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 18px;
        background: #fff;
    }

    .custom-field-options-table table {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .custom-field-options-table th,
    .custom-field-options-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    }

    .custom-field-options-table th {
        background: #f8fafc;
        color: #334155;
        font-size: 0.84rem;
        font-weight: 800;
    }

    .custom-field-options-table tr:last-child td {
        border-bottom: 0;
    }

    .custom-field-options-table__actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
    }

    .custom-fields-admin__pagination {
        margin-top: 22px;
        display: flex;
        justify-content: center;
    }

    .custom-fields-admin__empty {
        margin-top: 24px;
        padding: 30px;
        border-radius: 24px;
        background: #fff;
        border: 1px dashed rgba(15, 23, 42, 0.18);
        text-align: center;
        color: #667085;
    }

    @media (max-width: 991px) {
        .custom-field-card__head {
            grid-template-columns: 1fr;
        }

        .custom-field-card__actions {
            justify-content: flex-start;
        }

        .custom-fields-admin__panel-head {
            flex-direction: column;
        }
    }

    @media (max-width: 767px) {
        .custom-fields-admin__hero,
        .custom-fields-admin__panel,
        .custom-fields-admin__empty {
            padding: 20px;
            border-radius: 22px;
        }

        .custom-fields-admin__actions {
            justify-content: stretch;
        }

        .custom-fields-admin__create {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="custom-fields-admin">
    <section class="custom-fields-admin__hero">
        <span class="custom-fields-admin__kicker"><i class="fas fa-sliders-h"></i> Form Builder</span>
        <h1>Custom Fields</h1>
        <p>Create, review, and manage custom student input fields from one place. Existing edit, option, and delete actions stay unchanged.</p>

        <div class="custom-fields-admin__actions">
            <a href="{{ route('admin.custom-fields.create') }}" class="custom-fields-admin__create">
                <i class="fas fa-plus-circle"></i>
                Create New Field
            </a>
        </div>
    </section>

    @if(session('success'))
        <div class="alert alert-success custom-fields-admin__alert">{{ session('success') }}</div>
    @endif

    @if($fields->count())
        <section class="custom-fields-admin__panel">
            <div class="custom-fields-admin__panel-head">
                <div>
                    <h2>Field List</h2>
                    <p>Each card shows the field label, type, target audience, configured options, and the same action buttons you already use.</p>
                </div>
                <div class="custom-fields-admin__badge">{{ $fields->total() }}</div>
            </div>

            <div class="custom-fields-admin__stack">
                @foreach($fields as $field)
                    @php
                        $options = $field->getRelation('options') ?? collect();
                    @endphp

                    <article class="custom-field-card">
                        <div class="custom-field-card__head">
                            <div class="custom-field-card__title">
                                <strong>{{ $field->field_label }}</strong>
                                <span>Field ID: {{ $field->id }}</span>
                            </div>

                            <div class="custom-field-card__meta">
                                <span>Field Type</span>
                                <strong>{{ ucfirst(str_replace('_', ' ', $field->field_type)) }}</strong>
                            </div>

                            <div class="custom-field-card__meta">
                                <span>Target Audience</span>
                                <strong>{{ ucfirst($field->target_audience) }}</strong>
                            </div>

                            <div class="custom-field-card__actions">
                                <a href="{{ route('admin.custom-fields.edit', $field->id) }}" class="custom-field-card__btn custom-field-card__btn--edit">
                                    <i class="fas fa-pen"></i>
                                    Add new Field
                                </a>

                                <form action="{{ route('admin.custom-fields.destroy', $field->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="custom-field-card__btn custom-field-card__btn--delete" onclick="return confirm('Are you sure you want to delete this field?')">
                                        <i class="fas fa-trash-alt"></i>
                                        Delete Field
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="custom-field-card__body">
                            <div class="custom-field-card__options-title">Options</div>

                            @if($options->count())
                                <div class="custom-field-options-table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Option Name</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($options as $option)
                                                <tr>
                                                    <td>{{ $option->option_value }}</td>
                                                    <td>
                                                        <div class="custom-field-options-table__actions">
                                                            <a href="{{ route('admin.custom-fields.options.edit', $option->id) }}" class="custom-field-card__btn custom-field-card__btn--edit">
                                                                <i class="fas fa-pen"></i>
                                                                Edit
                                                            </a>

                                                            <form action="{{ route('admin.custom-fields.options.destroy', $option->id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="custom-field-card__btn custom-field-card__btn--delete" onclick="return confirm('Are you sure?')">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="custom-field-card__empty">No options available for this field.</div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="custom-fields-admin__pagination">
                {{ $fields->links() }}
            </div>
        </section>
    @else
        <div class="custom-fields-admin__empty">
            No custom fields have been created yet.
        </div>
    @endif
</div>
@endsection
