@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $userRoomNumber = $user->room_number ?? null;

    $allFields = \App\Models\UserCustomField::all();
    $roomFields = $allFields->where('target_audience', 'room');

    $submittedDataForRoom = collect();
    if ($userRoomNumber) {
        $submittedDataForRoom = \App\Models\UserFieldData::whereHas('user', function ($query) use ($userRoomNumber) {
            $query->where('room_number', $userRoomNumber);
        })->whereHas('customField', function ($query) {
            $query->where('target_audience', 'room');
        })->get();
    }

    $unfilledRoomFields = $roomFields->filter(function ($field) use ($submittedDataForRoom) {
        return $submittedDataForRoom->where('field_id', $field->id)->isEmpty();
    });

    $allRoomFieldsFilled = $unfilledRoomFields->isEmpty() && $roomFields->isNotEmpty();

    $roomNumber = $user->room_number;
    $ownSubmittedData = $submittedData->where('user_id', $user->id);
    $otherUsersSubmittedData = $submittedData->where('user_id', '!=', $user->id);

    $statusClassMap = [
        'approved' => 'custom-data-status custom-data-status--approved',
        'pending' => 'custom-data-status custom-data-status--pending',
        'rejected' => 'custom-data-status custom-data-status--rejected',
        'solved' => 'custom-data-status custom-data-status--solved',
    ];
@endphp

<style>
    .custom-data-page {
        padding: 32px 0 72px;
    }

    .custom-data-shell {
        max-width: 1120px;
        margin: 0 auto;
    }

    .custom-data-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: 34px;
        background:
            radial-gradient(circle at top right, rgba(241, 115, 170, 0.2), transparent 30%),
            linear-gradient(135deg, #241726 0%, #4c2a41 55%, #bb3e71 100%);
        color: #fff;
        box-shadow: 0 26px 60px rgba(36, 23, 38, 0.18);
    }

    .custom-data-hero::after {
        content: "";
        position: absolute;
        right: -50px;
        bottom: -70px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }

    .custom-data-hero__eyebrow {
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

    .custom-data-hero h1 {
        margin: 18px 0 12px;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        line-height: 1.08;
    }

    .custom-data-hero p {
        max-width: 720px;
        margin: 0;
        color: rgba(255, 255, 255, 0.82);
        line-height: 1.8;
    }

    .custom-data-metrics {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-top: 26px;
    }

    .custom-data-metric {
        padding: 18px 20px;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.12);
    }

    .custom-data-metric strong,
    .custom-data-metric span {
        display: block;
    }

    .custom-data-metric strong {
        font-size: 1.65rem;
        font-weight: 800;
    }

    .custom-data-metric span {
        margin-top: 6px;
        color: rgba(255, 255, 255, 0.8);
    }

    .custom-data-alert {
        margin-top: 22px;
        border: 0;
        border-radius: 18px;
        padding: 16px 18px;
        box-shadow: 0 16px 40px rgba(36, 23, 38, 0.08);
    }

    .custom-data-grid {
        display: grid;
        gap: 24px;
        margin-top: 28px;
    }

    .custom-data-card {
        background: #fff;
        border: 1px solid rgba(76, 42, 65, 0.08);
        border-radius: 28px;
        padding: 28px;
        box-shadow: 0 22px 60px rgba(76, 42, 65, 0.1);
    }

    .custom-data-card__head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 22px;
    }

    .custom-data-card__head h2,
    .custom-data-card__head h3 {
        margin: 0 0 8px;
        color: #241726;
        font-weight: 800;
    }

    .custom-data-card__head p {
        margin: 0;
        color: #6f6572;
        line-height: 1.7;
    }

    .custom-data-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 58px;
        min-height: 58px;
        padding: 10px;
        border-radius: 18px;
        background: #fff5f8;
        color: #bb3e71;
        font-size: 1.05rem;
        font-weight: 800;
    }

    .custom-data-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 18px;
    }

    .custom-data-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: 0;
        border-radius: 999px;
        padding: 12px 20px;
        text-decoration: none;
        font-weight: 700;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .custom-data-btn:hover,
    .custom-data-btn:focus {
        transform: translateY(-1px);
    }

    .custom-data-btn--primary {
        background: linear-gradient(135deg, #241726, #bb3e71);
        color: #fff;
        box-shadow: 0 14px 30px rgba(187, 62, 113, 0.24);
    }

    .custom-data-btn--secondary {
        background: #fff;
        border: 1px solid rgba(76, 42, 65, 0.14);
        color: #241726;
        box-shadow: 0 12px 24px rgba(76, 42, 65, 0.08);
    }

    .custom-data-btn--edit {
        padding: 9px 16px;
        background: #e6f4ff;
        color: #0b6aa8;
        font-size: 0.92rem;
    }

    .custom-data-table-wrap {
        overflow-x: auto;
        border-radius: 22px;
        border: 1px solid rgba(76, 42, 65, 0.08);
    }

    .custom-data-table {
        margin: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .custom-data-table thead th {
        padding: 16px 18px;
        background: #fff5f8;
        color: #241726;
        font-size: 0.88rem;
        font-weight: 800;
        border-bottom: 1px solid rgba(76, 42, 65, 0.08);
        white-space: nowrap;
    }

    .custom-data-table tbody td {
        padding: 16px 18px;
        color: #3d3340;
        vertical-align: top;
        border-bottom: 1px solid rgba(76, 42, 65, 0.06);
    }

    .custom-data-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .custom-data-image {
        max-width: 96px;
        border-radius: 16px;
        border: 1px solid rgba(76, 42, 65, 0.08);
    }

    .custom-data-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: capitalize;
    }

    .custom-data-status--approved {
        background: #e6f7ee;
        color: #1f8b4c;
    }

    .custom-data-status--pending {
        background: #fff4d8;
        color: #b57a00;
    }

    .custom-data-status--rejected {
        background: #ffe6e8;
        color: #c03347;
    }

    .custom-data-status--solved {
        background: #e3f3ff;
        color: #0f73aa;
    }

    .custom-data-status--default {
        background: #eef0f3;
        color: #58606b;
    }

    .custom-data-empty {
        padding: 30px 24px;
        border-radius: 24px;
        background: linear-gradient(180deg, #ffffff 0%, #fff9fb 100%);
        border: 1px dashed rgba(76, 42, 65, 0.16);
        text-align: center;
    }

    .custom-data-empty i {
        font-size: 1.8rem;
        color: #bb3e71;
        margin-bottom: 14px;
    }

    .custom-data-empty p {
        margin: 0;
        color: #6f6572;
        line-height: 1.7;
    }

    @media (max-width: 991px) {
        .custom-data-metrics {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .custom-data-page {
            padding: 20px 0 56px;
        }

        .custom-data-hero,
        .custom-data-card {
            padding: 22px;
            border-radius: 22px;
        }

        .custom-data-card__head {
            flex-direction: column;
        }

        .custom-data-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="container custom-data-page">
    <div class="custom-data-shell">
        <section class="custom-data-hero">
            <span class="custom-data-hero__eyebrow">
                <i class="fas fa-folder-open"></i>
                Submission Overview
            </span>
            <h1>Your Submitted Custom Field Data</h1>
            <p>Review your own entries, room-based submissions, and related updates from other users in the same room without changing any of the current page behavior.</p>

            <div class="custom-data-metrics">
                <div class="custom-data-metric">
                    <strong>{{ $submittedDataForRoom->count() }}</strong>
                    <span>Room submissions found</span>
                </div>
                <div class="custom-data-metric">
                    <strong>{{ $ownSubmittedData->count() }}</strong>
                    <span>Your submitted entries</span>
                </div>
                <div class="custom-data-metric">
                    <strong>{{ $otherUsersSubmittedData->count() }}</strong>
                    <span>Entries from other users in room {{ $roomNumber ?? 'N/A' }}</span>
                </div>
            </div>
        </section>

        @if(session('success'))
            <div class="alert alert-success custom-data-alert">{{ session('success') }}</div>
        @endif

        <div class="custom-data-grid">
            <section class="custom-data-card">
                <div class="custom-data-card__head">
                    <div>
                        <h2>Room Data</h2>
                        <p>Shared submissions linked to room {{ $userRoomNumber ?? 'N/A' }} appear here. You can still edit entries that are not solved.</p>
                    </div>
                    <div class="custom-data-pill">{{ $submittedDataForRoom->count() }}</div>
                </div>

                @if($submittedDataForRoom->isNotEmpty())
                    <div class="alert alert-info custom-data-alert mb-4">
                        <strong>Problem has been submitted for Room Number: {{ $userRoomNumber }}</strong>
                        <span class="d-block mt-1">You can edit room data unless the item is already solved.</span>
                    </div>

                    <div class="custom-data-table-wrap">
                        <table class="custom-data-table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Field Label</th>
                                    <th>Value</th>
                                    <th>Description</th>
                                    <th>Submitted By</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($submittedDataForRoom as $index => $data)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $data->customField->field_label }}</td>
                                        <td>
                                            @if ($data->customField->field_type === 'image')
                                                <img src="{{ asset('storage/' . $data->value) }}" alt="{{ $data->customField->field_label }}" class="img-fluid custom-data-image">
                                            @else
                                                {{ $data->value }}
                                            @endif
                                        </td>
                                        <td>{{ $data->description ?: 'No description available' }}</td>
                                        <td>{{ $data->user->full_name }}</td>
                                        <td>
                                            <span class="{{ $statusClassMap[$data->status] ?? 'custom-data-status custom-data-status--default' }}">
                                                {{ ucfirst($data->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($data->status !== 'solved')
                                                <a href="{{ route('user-field-data.edit', $data->id) }}" class="custom-data-btn custom-data-btn--edit">Edit</a>
                                            @else
                                                <span class="text-muted">Already Solved</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="custom-data-toolbar">
                        <a href="{{ url('user/custom-fields/existing') }}" class="custom-data-btn custom-data-btn--primary">
                            <i class="fas fa-plus-circle"></i>
                            Add New Data
                        </a>
                    </div>
                @else
                    <div class="custom-data-empty">
                        <i class="fas fa-inbox"></i>
                        <p><strong>No room data found</strong> for room number {{ $userRoomNumber ?? 'N/A' }}. Please submit new data if something is pending.</p>
                    </div>
                @endif
            </section>

            @if($submittedData->count())
                <section class="custom-data-card">
                    <div class="custom-data-card__head">
                        <div>
                            <h3>Your Submitted Data</h3>
                            <p>Room number {{ $roomNumber }}. This section shows only your own submissions and their current review status.</p>
                        </div>
                        <div class="custom-data-pill">{{ $ownSubmittedData->count() }}</div>
                    </div>

                    @if ($ownSubmittedData->count())
                        <div class="custom-data-table-wrap">
                            <table class="custom-data-table">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Field Label</th>
                                        <th>Value</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ownSubmittedData as $index => $data)
                                        @php
                                            $value = $data->value;
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $data->customField->field_label }}</td>
                                            <td>
                                                @if ($data->customField->field_type === 'image')
                                                    <img src="{{ asset('storage/' . $data->value) }}" alt="{{ $data->customField->field_label }}" class="img-fluid custom-data-image">
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </td>
                                            <td>{{ $data->description ?: 'No description available' }}</td>
                                            <td>
                                                <span class="{{ $statusClassMap[$data->status] ?? 'custom-data-status custom-data-status--default' }}">
                                                    {{ ucfirst($data->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($data->status !== 'solved')
                                                    <a href="{{ route('user-field-data.edit', $data->id) }}" class="custom-data-btn custom-data-btn--edit">Edit</a>
                                                @else
                                                    <span class="text-muted">Already Solved</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="custom-data-empty">
                            <i class="far fa-file-alt"></i>
                            <p>You haven't submitted any personal data yet.</p>
                        </div>
                    @endif
                </section>

                <section class="custom-data-card">
                    <div class="custom-data-card__head">
                        <div>
                            <h3>Other Users' Submitted Data</h3>
                            <p>Visible entries from other users in room {{ $roomNumber }} are listed here for shared visibility.</p>
                        </div>
                        <div class="custom-data-pill">{{ $otherUsersSubmittedData->count() }}</div>
                    </div>

                    @if ($otherUsersSubmittedData->count())
                        <div class="custom-data-table-wrap">
                            <table class="custom-data-table">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>User Name</th>
                                        <th>Field Label</th>
                                        <th>Value</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($otherUsersSubmittedData as $index => $data)
                                        @php
                                            $value = $data->value;
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $data->user->full_name }}</td>
                                            <td>{{ $data->customField->field_label }}</td>
                                            <td>
                                                @if ($data->customField->field_type === 'image')
                                                    <img src="{{ asset('storage/' . $data->value) }}" alt="{{ $data->customField->field_label }}" class="img-fluid custom-data-image">
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </td>
                                            <td>{{ $data->description ?: 'No description available' }}</td>
                                            <td>
                                                <span class="{{ $statusClassMap[$data->status] ?? 'custom-data-status custom-data-status--default' }}">
                                                    {{ ucfirst($data->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="custom-data-empty">
                            <i class="fas fa-users-slash"></i>
                            <p>No other users in your room have submitted data yet.</p>
                        </div>
                    @endif
                </section>
            @else
                <section class="custom-data-card">
                    <div class="custom-data-empty">
                        <i class="fas fa-info-circle"></i>
                        <p><strong>Room Number:</strong> {{ $roomNumber }}. You haven't submitted any data yet. If anything is available for you, please go to the submit page.</p>
                    </div>

                    <div class="custom-data-toolbar">
                        <a href="{{ route('user.custom-fields.create') }}" class="custom-data-btn custom-data-btn--primary">
                            <i class="fas fa-file-signature"></i>
                            Go To Submit
                        </a>
                    </div>

                    <form action="{{ route('user-field-data.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                    </form>
                </section>
            @endif
        </div>
    </div>
</div>
@endsection
