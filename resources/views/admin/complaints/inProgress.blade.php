@extends('layouts.admin_app')

@section('content')
<div class="container">
    
     <button onclick="goBack()" style="margin-bottom: 15px; padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
        ← Go Back
    </button>
    
    <script>
        function goBack() {
            window.history.back();
        }
    </script>


    <h2>In Progress Complaints</h2>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
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
                        <span class="badge bg-primary">{{ ucfirst($complaint->status) }}</span>
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
