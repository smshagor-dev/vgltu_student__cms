@extends('layouts.app')

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
            <p><strong>Status:</strong> {{ ucfirst($complaint->status) }}</p>
            <p>{{ $complaint->description }}</p>
            <p><strong>Submitted At:</strong> {{ $complaint->created_at->format('d M Y, H:i') }}</p>
        </div>
    </div>
</div>
@endsection
