@extends('layouts.admin_app')

@section('content')
<style>
    .solved-custom-data-admin {
        max-width: 1260px;
        margin: 0 auto;
    }

    .solved-custom-data-admin__hero {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        padding: 30px;
        background:
            radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 28%),
            linear-gradient(135deg, #166534 0%, #16a34a 55%, #0f766e 100%);
        color: #fff;
        box-shadow: 0 24px 56px rgba(22, 101, 52, 0.18);
    }

    .solved-custom-data-admin__hero::after {
        content: "";
        position: absolute;
        right: -42px;
        bottom: -72px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }

    .solved-custom-data-admin__kicker {
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

    .solved-custom-data-admin__hero h1 {
        margin: 18px 0 10px;
        color: #fff;
        font-size: clamp(2rem, 4vw, 2.8rem);
        font-weight: 800;
    }

    .solved-custom-data-admin__hero p {
        max-width: 760px;
        margin: 0;
        color: rgba(255, 255, 255, 0.84);
        line-height: 1.75;
    }

    .solved-custom-data-admin__panel {
        margin-top: 24px;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
    }

    .solved-custom-data-admin__panel-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 20px;
    }

    .solved-custom-data-admin__panel-head h2 {
        margin: 0 0 8px;
        color: #1f2937;
        font-size: 1.45rem;
        font-weight: 800;
    }

    .solved-custom-data-admin__panel-head p {
        margin: 0;
        color: #667085;
        line-height: 1.7;
    }

    .solved-custom-data-admin__badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 58px;
        min-height: 58px;
        border-radius: 18px;
        background: #dcfce7;
        color: #166534;
        font-weight: 800;
    }

    .solved-custom-data-admin__table-wrap {
        overflow-x: auto;
        border-radius: 20px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background: #fff;
    }

    .solved-custom-data-table {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .solved-custom-data-table th,
    .solved-custom-data-table td {
        padding: 14px 16px;
        vertical-align: top;
        border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        border-right: 1px solid rgba(15, 23, 42, 0.06);
    }

    .solved-custom-data-table th:last-child,
    .solved-custom-data-table td:last-child {
        border-right: 0;
    }

    .solved-custom-data-table tr:last-child td {
        border-bottom: 0;
    }

    .solved-custom-data-table thead th {
        background: #dcfce7;
        color: #166534;
        font-size: 0.84rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .solved-custom-data-table__list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 8px;
    }

    .solved-custom-data-table__list li {
        color: #475569;
        line-height: 1.6;
    }

    .solved-custom-data-table__list strong {
        color: #0f4c81;
    }

    .solved-custom-data-table__status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        border-radius: 999px;
        background: #dcfce7;
        color: #166534;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .solved-custom-data-table__action .btn {
        border-radius: 999px;
        font-weight: 700;
        padding: 8px 14px;
    }

    .solved-custom-data-admin__pagination {
        margin-top: 22px;
        display: flex;
        justify-content: center;
    }

    .solved-custom-data-admin__empty {
        margin-top: 24px;
        padding: 30px;
        border-radius: 24px;
        background: #fff;
        border: 1px dashed rgba(15, 23, 42, 0.18);
        text-align: center;
        color: #667085;
    }

    @media (max-width: 991px) {
        .solved-custom-data-admin__panel-head {
            flex-direction: column;
        }
    }

    @media (max-width: 767px) {
        .solved-custom-data-admin__hero,
        .solved-custom-data-admin__panel,
        .solved-custom-data-admin__empty {
            padding: 20px;
            border-radius: 22px;
        }
    }
</style>

<div class="solved-custom-data-admin">
    <section class="solved-custom-data-admin__hero">
        <span class="solved-custom-data-admin__kicker"><i class="fas fa-check-circle"></i> Solved Archive</span>
        <h1>Solved Data</h1>
        <p>Review all solved custom field submissions, inspect submitted values and descriptions, and switch any solved item back to not solved without changing the original workflow.</p>
    </section>

    @if($solvedData->count())
        <section class="solved-custom-data-admin__panel">
            <div class="solved-custom-data-admin__panel-head">
                <div>
                    <h2>Solved Submission List</h2>
                    <p>The solved records below keep the same decoded data display, status badge, and “Mark as Not Solved” action you already use.</p>
                </div>
                <div class="solved-custom-data-admin__badge">{{ $solvedData->total() }}</div>
            </div>

            <div class="solved-custom-data-admin__table-wrap">
                <table class="solved-custom-data-table">
                    <thead>
                        <tr>
                            <th>Serial No</th>
                            <th>Room Number</th>
                            <th>Name</th>
                            <th>Submitted Data</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($solvedData as $index => $data)
                            <tr>
                                <td>{{ $solvedData->firstItem() + $index }}</td>
                                <td>{{ $data->user->room_number }}</td>
                                <td>{{ $data->user->full_name }}</td>
                                <td>
                                    @php
                                        $submittedData = json_decode($data->value, true);
                                    @endphp
                                    <ul class="solved-custom-data-table__list">
                                        @if (is_array($submittedData))
                                            @foreach ($submittedData as $key => $value)
                                                <li>
                                                    <strong>{{ $key }}:</strong>
                                                    {{ is_array($value) ? implode(', ', $value) : $value }}
                                                </li>
                                            @endforeach
                                        @else
                                            <li>{{ $data->value }}</li>
                                        @endif
                                    </ul>
                                </td>
                                <td>
                                    @php
                                        $descriptionData = json_decode($data->description, true);
                                    @endphp
                                    <ul class="solved-custom-data-table__list">
                                        @if (is_array($descriptionData))
                                            @foreach ($descriptionData as $key => $value)
                                                <li>
                                                    <strong>{{ $key }}:</strong>
                                                    {{ is_array($value) ? implode(', ', $value) : ($value ?? 'No description available') }}
                                                </li>
                                            @endforeach
                                        @else
                                            <li>{{ $data->description ?: 'No description available' }}</li>
                                        @endif
                                    </ul>
                                </td>
                                <td>
                                    <span class="solved-custom-data-table__status">{{ ucfirst($data->status) }}</span>
                                </td>
                                <td class="solved-custom-data-table__action">
                                    <form action="{{ route('admin.user-custom-data.update-status', ['userId' => $data->user_id, 'fieldId' => $data->field_id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Mark as Not Solved</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="solved-custom-data-admin__pagination">
                {{ $solvedData->links() }}
            </div>
        </section>
    @else
        <div class="solved-custom-data-admin__empty">
            No solved submission data is available right now.
        </div>
    @endif
</div>
@endsection
