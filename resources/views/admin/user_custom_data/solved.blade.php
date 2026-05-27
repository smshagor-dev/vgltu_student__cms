@extends('layouts.admin_app')

@section('content')
<div class="container">
    <h1 class="text-success">Solved Data</h1>

    <div class="table-responsive"> <!-- Makes table scrollable on small screens -->
        <table class="table table-bordered">
            <thead class="table-dark">
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
                        <ul class="p-0 m-0">
                            @if (is_array($submittedData))
                                @foreach ($submittedData as $key => $value)
                                    <li><strong class="text-primary">{{ $key }}:</strong> 
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
                        <ul class="p-0 m-0">
                            @if (is_array($descriptionData))
                                @foreach ($descriptionData as $key => $value)
                                    <li><strong class="text-info">{{ $key }}:</strong> 
                                        {{ is_array($value) ? implode(', ', $value) : ($value ?? 'No description available') }}
                                    </li>
                                @endforeach
                            @else
                                <li>{{ $data->description ?: 'No description available' }}</li>
                            @endif
                        </ul>
                    </td>
                    <td>
                        <span class="badge {{ $data->status === 'solved' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($data->status) }}
                        </span>
                    </td>
                    <td>
                        <!-- Toggle status back to 'not solved' -->
                        <form action="{{ route('admin.user-custom-data.update-status', ['userId' => $data->user_id, 'fieldId' => $data->field_id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Mark as Not Solved</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div> <!-- End of .table-responsive -->

    <div class="mt-4 d-flex justify-content-center">
        {{ $solvedData->links() }}
    </div>
</div>
@endsection
