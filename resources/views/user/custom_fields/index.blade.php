@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $user = auth()->user();
        $userRoomNumber = $user->room_number ?? null;

        // Fetch all fields
        $allFields = \App\Models\UserCustomField::all();
        $roomFields = $allFields->where('target_audience', 'room');

        // Fetch submitted data for users in the same room
        $submittedDataForRoom = collect();
        if ($userRoomNumber) {
            $submittedDataForRoom = \App\Models\UserFieldData::whereHas('user', function ($query) use ($userRoomNumber) {
                $query->where('room_number', $userRoomNumber);
            })->whereHas('customField', function($query) {
                $query->where('target_audience', 'room');
            })->get();
        }

        // Identify unfilled room fields
        $unfilledRoomFields = $roomFields->filter(function ($field) use ($submittedDataForRoom) {
            return $submittedDataForRoom->where('field_id', $field->id)->isEmpty();
        });

        // Check if all room fields are filled
        $allRoomFieldsFilled = $unfilledRoomFields->isEmpty() && $roomFields->isNotEmpty();
    @endphp

    <h3 class="text-primary">Submitted Room Data</h3>

    @if($submittedDataForRoom->isNotEmpty())
        <div class="alert alert-info text-center">
            <strong>Problem has been Submitted for Room Number: {{ $userRoomNumber }}</strong>
            <strong>You can edit this data</strong>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
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
                                    <img src="{{ asset('storage/' . $data->value) }}" alt="{{ $data->customField->field_label }}" class="img-fluid" style="max-width: 100px;">
                                @else
                                    {{ $data->value }}
                                @endif
                            </td>
                            <td>{{ $data->description ?: 'No description available' }}</td>
                            <td>{{ $data->user->full_name }}</td>
                            <td>
                                <span class="badge 
                                    @if($data->status === 'approved') bg-success
                                    @elseif($data->status === 'pending') bg-warning
                                    @elseif($data->status === 'rejected') bg-danger
                                    @elseif($data->status === 'solved') bg-info
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst($data->status) }}
                                </span>
                            </td>
                            <td>
                                @if($data->status !== 'solved')
                                    <a href="{{ route('user-field-data.edit', $data->id) }}" class="btn btn-info btn-sm">Edit</a>
                                @else
                                    <span class="text-muted">Already Solved</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-right">
            <button type="button" class="btn btn-primary px-5">
                <a href="{{ url('user/custom-fields/existing') }}" style="color:white; text-decoration: none;">Add New Data</a>
            </button>
        </div>
    @else
        <div class="alert alert-warning text-center">
            <strong>No Room Data Found for Room Number: {{ $userRoomNumber ?? 'N/A' }}. Please submit new data.</strong>
        </div>
    @endif


    <h1>Your Submitted Data</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@php
    $user = Auth::user();
    $roomNumber = $user->room_number;

    // Get the user's own submitted data
    $ownSubmittedData = $submittedData->where('user_id', $user->id);

    // Get data submitted by other users in the same room
    $otherUsersSubmittedData = $submittedData->where('user_id', '!=', $user->id);
@endphp

@if($submittedData->count())
    <div class="alert alert-info">
        <strong>Your Room Number:</strong> {{ $roomNumber }} - Data Submission Details
    </div>

    {{-- Show Own Data --}}
    <h3 class="text-primary">Your Submitted Data</h3>
    @if ($ownSubmittedData->count())
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>SL</th>
                        <th>Field Label</th>
                        <th>Value</th>
                        <th>Descriptions</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ownSubmittedData as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->customField->field_label }}</td>
                            <td>
                                @php
                                    // Value is stored as normal text (not JSON)
                                    $value = $data->value;
                                @endphp
                                @if ($data->customField->field_type === 'image')
                                    <img src="{{ asset('storage/' . $data->value) }}" alt="{{ $data->customField->field_label }}" class="img-fluid" style="max-width: 100px;">
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                            <td>
                                {{-- Handle description as normal text --}}
                                {{ $data->description ?: 'No description available' }}
                            </td>
                            <td>
                                <span class="badge 
                                    @if($data->status === 'approved') bg-success
                                    @elseif($data->status === 'pending') bg-warning
                                    @elseif($data->status === 'rejected') bg-danger
                                    @elseif($data->status === 'solved') bg-info
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst($data->status) }}
                                </span>
                            </td>
                            <td>
                                @if($data->status !== 'solved')
                                    <a href="{{ route('user-field-data.edit', $data->id) }}" class="btn btn-info btn-sm">Edit</a>
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
        <p class="text-danger">You haven't submitted any data yet.</p>
    @endif

    {{-- Show Data Submitted by Other Users in the Same Room --}}
    @if ($otherUsersSubmittedData->count())
        <h3 class="text-success mt-4">Other Users' Submitted Data (Room {{ $roomNumber }})</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>SL</th>
                        <th>User Name</th>
                        <th>Field Label</th>
                        <th>Value</th>
                        <th>Descriptions</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($otherUsersSubmittedData as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->user->full_name }}</td>
                            <td>{{ $data->customField->field_label }}</td>
                            <td>
                                @php
                                    // Value is stored as normal text (not JSON)
                                    $value = $data->value;
                                @endphp
                                @if ($data->customField->field_type === 'image')
                                    <img src="{{ asset('storage/' . $data->value) }}" alt="{{ $data->customField->field_label }}" class="img-fluid" style="max-width: 100px;">
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                            <td>
                                {{-- Handle description as normal text --}}
                                {{ $data->description ?: 'No description available' }}
                            </td>
                            <td>
                                <span class="badge 
                                    @if($data->status === 'approved') bg-success
                                    @elseif($data->status === 'pending') bg-warning
                                    @elseif($data->status === 'rejected') bg-danger
                                    @elseif($data->status === 'solved') bg-info
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst($data->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted">No other users in your room have submitted data.</p>
    @endif

@else
    <div class="alert alert-info">
        <strong>Room Number:</strong> {{ $roomNumber }} you haven't submitted any data yet. If anything is available for you, please submit your data.
        <br>
        <a href="{{ route('user.custom-fields.create') }}" class="btn btn-primary btn-sm">Go to Submit</a>
    </div>

    <form action="{{ route('user-field-data.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
    </form>
@endif

</div>
@endsection
