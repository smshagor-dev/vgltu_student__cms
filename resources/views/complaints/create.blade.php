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
    
    
    
    <h2>Submit a Complaint</h2>
    <form method="POST" action="{{ route('complaints.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" value="{{ Auth::user()->full_name }}" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Room Number</label>
            <input type="text" class="form-control" value="{{ Auth::user()->room_number }}" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Mobile Number</label>
            <input type="text" class="form-control" value="{{ Auth::user()->mobile_number }}" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Complaint</button>
    </form>
</div>
@endsection
