@extends('layouts.admin_app')

@section('content')
<div class="container">
    <h2>All Complaints</h2>

    <!-- Buttons for In Progress and Solved Complaints -->
    <div class="mb-3">
        <a href="{{ route('admin.complaints.inProgress') }}" class="btn btn-primary">View In Progress Complaints</a>
        <a href="{{ route('admin.complaints.solved') }}" class="btn btn-success">View Solved Complaints</a>
    </div>


    <table class="table">
        <thead>
            <tr>
                <th>SL.N</th>  <!-- Serial Number Column -->
                <th>User</th>
                <th>Room Number</th>
                <th>Mobile Number</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Submitted At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($complaints as $index => $complaint)
                <tr>
                    <td>{{ $complaints->firstItem() + $index }}</td>
                    <td>{{ $complaint->user->full_name }}</td>
                    <td>{{ $complaint->user->room_number }}</td>
                    <td>{{ $complaint->user->mobile_number }}</td>
                    <td>{{ $complaint->subject }}</td>
                    <td>
                        <span class="badge bg-{{ $complaint->status == 'pending' ? 'warning' : ($complaint->status == 'in_progress' ? 'primary' : 'success') }}">
                            {{ ucfirst($complaint->status) }}
                        </span>
                    </td>
                    <td>{{ $complaint->created_at->format('d M Y, H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn btn-info btn-sm">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4 d-flex justify-content-center">
        {{ $complaints->links() }}
    </div>
</div>
@endsection
