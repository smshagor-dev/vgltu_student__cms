@extends('layouts.admin_app')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">Admin Profile</h3>
        </div>
        <div class="card-body text-center">
            @if ($admin->photo)
                <img src="{{ asset('storage/' . $admin->photo) }}" alt="Admin Photo" 
                     style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
            @else
                <p>No photo uploaded.</p>
            @endif

            <h4 class="mt-3">{{ $admin->name }}</h4>
            <p class="text-muted">{{ $admin->email }}</p>
        </div>
    </div>
</div>
@endsection
