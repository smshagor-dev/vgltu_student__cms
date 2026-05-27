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
    
    <h2>Complaint Details</h2>
    <div class="card">
        <div class="card-body">
            <h4>{{ $complaint->subject }}</h4>
            <p><strong>User:</strong> {{ $complaint->user->full_name }} (Room: {{ $complaint->user->room_number }})</p>
            <p><strong>Mobile:</strong> {{ $complaint->user->mobile_number }}</p>
            <p><strong>Description:</strong> {{ $complaint->description }}</p>
            <p><strong>Status:</strong> 
                <span class="badge bg-{{ $complaint->status == 'pending' ? 'warning' : ($complaint->status == 'in_progress' ? 'primary' : 'success') }}">
                    {{ ucfirst($complaint->status) }}
                </span>
            </p>
            <p><strong>Submitted At:</strong> {{ $complaint->created_at->format('d M Y, H:i') }}</p>

            <form method="POST" action="{{ route('admin.complaints.updateStatus', $complaint->id) }}">
                @csrf
                <div class="mb-3">
                    <label for="status">Update Status</label>
                    <select name="status" class="form-control">
                        <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Status</button>
            </form>
        </div>
    </div>
</div>
@endsection
