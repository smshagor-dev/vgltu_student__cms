@extends('layouts.admin_app')

@section('content')
<style>
    .user-custom-data-admin {
        max-width: 1260px;
        margin: 0 auto;
    }

    .user-custom-data-admin__hero {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        padding: 30px;
        background:
            radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 28%),
            linear-gradient(135deg, #0f4c81 0%, #1d4ed8 55%, #0f766e 100%);
        color: #fff;
        box-shadow: 0 24px 56px rgba(15, 76, 129, 0.18);
    }

    .user-custom-data-admin__hero::after {
        content: "";
        position: absolute;
        right: -42px;
        bottom: -72px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }

    .user-custom-data-admin__kicker {
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

    .user-custom-data-admin__hero h1 {
        margin: 18px 0 10px;
        color: #fff;
        font-size: clamp(2rem, 4vw, 2.8rem);
        font-weight: 800;
    }

    .user-custom-data-admin__hero p {
        max-width: 760px;
        margin: 0;
        color: rgba(255, 255, 255, 0.84);
        line-height: 1.75;
    }

    .user-custom-data-admin__toolbar {
        margin-top: 24px;
        padding: 24px;
        border-radius: 24px;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
    }

    .user-custom-data-admin__toolbar-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 18px;
    }

    .user-custom-data-admin__toolbar-head h2 {
        margin: 0 0 8px;
        color: #1f2937;
        font-size: 1.45rem;
        font-weight: 800;
    }

    .user-custom-data-admin__toolbar-head p {
        margin: 0;
        color: #667085;
        line-height: 1.7;
    }

    .user-custom-data-admin__badge {
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

    .user-custom-data-admin__controls {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 14px;
        align-items: end;
    }

    .user-custom-data-admin__search .input-group {
        box-shadow: none;
    }

    .user-custom-data-admin__search .form-control {
        min-height: 48px;
        border-radius: 14px 0 0 14px;
    }

    .user-custom-data-admin__search .btn {
        min-width: 130px;
        border-radius: 0 14px 14px 0;
        font-weight: 700;
    }

    .user-custom-data-admin__back {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        min-height: 48px;
        padding: 12px 18px;
        border: 0;
        border-radius: 999px;
        background: #fff5f8;
        color: #bb3e71;
        font-weight: 700;
    }

    .user-custom-data-admin__panel {
        margin-top: 24px;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
    }

    .user-custom-data-admin__table-wrap {
        overflow-x: auto;
        border-radius: 20px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background: #fff;
    }

    .user-custom-data-table {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .user-custom-data-table th,
    .user-custom-data-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        border-right: 1px solid rgba(15, 23, 42, 0.06);
    }

    .user-custom-data-table th:last-child,
    .user-custom-data-table td:last-child {
        border-right: 0;
    }

    .user-custom-data-table tr:last-child td {
        border-bottom: 0;
    }

    .user-custom-data-table thead th {
        background: #eff6ff;
        color: #12305f;
        font-size: 0.84rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .user-custom-data-table__user {
        text-align: center;
        min-width: 160px;
    }

    .user-custom-data-table__field {
        color: #111827;
        font-weight: 700;
        min-width: 180px;
    }

    .user-custom-data-table__subtle {
        color: #667085;
        font-size: 0.88rem;
    }

    .user-custom-data-table__status .btn,
    .user-custom-data-table__action .btn {
        border-radius: 999px;
        font-weight: 700;
        padding: 8px 14px;
    }

    .user-custom-data-admin__pagination {
        margin-top: 22px;
        display: flex;
        justify-content: center;
    }

    .user-custom-data-admin__empty {
        margin-top: 24px;
        padding: 30px;
        border-radius: 24px;
        background: #fff;
        border: 1px dashed rgba(15, 23, 42, 0.18);
        text-align: center;
        color: #667085;
    }

    @media (max-width: 991px) {
        .user-custom-data-admin__toolbar-head,
        .user-custom-data-admin__controls {
            grid-template-columns: 1fr;
            flex-direction: column;
        }
    }

    @media (max-width: 767px) {
        .user-custom-data-admin__hero,
        .user-custom-data-admin__toolbar,
        .user-custom-data-admin__panel,
        .user-custom-data-admin__empty {
            padding: 20px;
            border-radius: 22px;
        }
    }
</style>

<div class="user-custom-data-admin">
    <section class="user-custom-data-admin__hero">
        <span class="user-custom-data-admin__kicker"><i class="fas fa-database"></i> Submitted Data Review</span>
        <h1>Users And Submitted Problems</h1>
        <p>Search submitted custom field data by room number, review the problem details, and keep using the same status toggle and delete actions already wired into this page.</p>
    </section>

    <section class="user-custom-data-admin__toolbar">
        <div class="user-custom-data-admin__toolbar-head">
            <div>
                <h2>Users Who Submitted Data</h2>
                <p>The table below keeps the exact same grouped user rows, field data, AJAX status toggle, AJAX delete, and pagination behavior.</p>
            </div>
            <div class="user-custom-data-admin__badge">{{ $users->total() }}</div>
        </div>

        <div class="user-custom-data-admin__controls">
            <form action="{{ route('admin.user-custom-data.index') }}" method="GET" class="user-custom-data-admin__search">
                <div class="input-group">
                    <input type="text" name="query" class="form-control" placeholder="Search by room number" value="{{ request('query') }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>

            <button onclick="goBack()" class="user-custom-data-admin__back">
                <i class="fas fa-arrow-left"></i>
                Go Back
            </button>
        </div>
    </section>

    @if($users->count())
        <section class="user-custom-data-admin__panel">
            <div class="user-custom-data-admin__table-wrap">
                <table class="user-custom-data-table">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Room Number</th>
                            <th>Field Label</th>
                            <th>Field Type</th>
                            <th>Submitted Data</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @foreach ($user->fieldData->groupBy('field_id') as $fieldId => $groupedFields)
                                <tr id="row-{{ $groupedFields->first()->id }}">
                                    @if ($loop->first)
                                        <td class="user-custom-data-table__user" rowspan="{{ $user->fieldData->count() }}">
                                            <div>{{ $user->full_name }}</div>
                                        </td>
                                        <td class="user-custom-data-table__user" rowspan="{{ $user->fieldData->count() }}">
                                            <div>{{ $user->room_number }}</div>
                                        </td>
                                    @endif

                                    <td class="user-custom-data-table__field" rowspan="{{ $groupedFields->count() }}">
                                        {{ $groupedFields->first()->customField->field_label }}
                                    </td>

                                    <td rowspan="{{ $groupedFields->count() }}">
                                        {{ ucfirst($groupedFields->first()->customField->field_type) }}
                                    </td>

                                    @foreach ($groupedFields as $index => $fieldData)
                                        @if ($index > 0)
                                            <tr>
                                        @endif

                                        <td>
                                            @if ($fieldData->customField->field_type == 'multiple_choice')
                                                {{ implode(', ', explode(',', $fieldData->value)) }}
                                            @else
                                                {{ $fieldData->value }}
                                            @endif
                                        </td>

                                        <td class="user-custom-data-table__subtle">
                                            {{ $fieldData->description ?? 'No Description Available' }}
                                        </td>

                                        <td class="user-custom-data-table__status">
                                            <button id="status-btn-{{ $fieldData->id }}" class="btn btn-{{ $fieldData->status === 'solved' ? 'success' : 'warning' }}"
                                                    data-userid="{{ $user->id }}" data-valueid="{{ $fieldData->id }}"
                                                    data-status="{{ $fieldData->status }}">
                                                {{ ucfirst($fieldData->status) }}
                                            </button>
                                        </td>

                                        <td class="user-custom-data-table__action">
                                            <button class="btn btn-danger delete-btn" data-userid="{{ $user->id }}"
                                                    data-valueid="{{ $fieldData->id }}">Delete</button>
                                        </td>

                                        @if ($index > 0)
                                            </tr>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="user-custom-data-admin__pagination">
                {{ $users->links() }}
            </div>
        </section>
    @else
        <div class="user-custom-data-admin__empty">
            No submitted user custom data found for the current search.
        </div>
    @endif
</div>

<script>
    function goBack() {
        window.history.back();
    }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('.btn').on('click', function(e) {
            e.preventDefault();
            var userId = $(this).data('userid');
            var valueId = $(this).data('valueid');
            var status = $(this).data('status');

            $.ajax({
                url: '{{ route("admin.user-from-submission-data.update-value-status", ["userId" => ":userId", "valueId" => ":valueId"]) }}'.replace(':userId', userId).replace(':valueId', valueId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status === 'solved' ? 'pending' : 'solved',
                },
                success: function(response) {
                    if (response.success) {
                        var newStatus = status === 'solved' ? 'pending' : 'solved';
                        var button = $('#status-btn-' + valueId);
                        button.text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                        button.removeClass('btn-' + (status === 'solved' ? 'success' : 'warning'));
                        button.addClass('btn-' + (newStatus === 'solved' ? 'success' : 'warning'));
                        button.data('status', newStatus);
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred. Please try again.');
                }
            });
        });

        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            var userId = $(this).data('userid');
            var valueId = $(this).data('valueid');

            if (confirm('Are you sure you want to delete this data?')) {
                $.ajax({
                    url: '{{ route("admin.user-from-submission-data.delete-value-data", ["userId" => ":userId", "valueId" => ":valueId"]) }}'.replace(':userId', userId).replace(':valueId', valueId),
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#row-' + valueId).remove();
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred. Please try again.');
                    }
                });
            }
        });
    });
</script>
@endsection
