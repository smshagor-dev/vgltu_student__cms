@extends('layouts.admin_app')

@section('content')
<style>
    .user-card {
        display: flex;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background-color: #fff;
    }
    .user-photo {
        width: 150px;
        height: 150px;
        margin-right: 15px;
    }
    .user-photo img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }
    .user-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .user-info h5 {
        margin-bottom: 10px;
    }
    .user-actions {
        display: flex;
        gap: 10px;
    }
    .user-actions a {
        text-decoration: none;
        color: white;
        background-color: #007bff;
        padding: 8px 12px;
        border-radius: 4px;
    }
    .user-actions a:hover {
        background-color: #0056b3;
    }

    @media (max-width: 768px) {
        img.img-fluid {
            width: 50px;
            height: 50px;
        }
    }
</style>
<div class="container mt-5">
    <h2>Search Results</h2>

    @if($users->isEmpty())
        <p>No users found.</p>
    @else
        @foreach ($users as $user)
            <div class="user-card">
                <div class="user-photo">
                    @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="User Photo" class="img-fluid rounded">
                    @else
                        <img src="{{ asset('storage/default.png') }}" alt="Default Photo" class="img-fluid rounded">
                    @endif
                </div>
                <div class="user-info">
                    <h5>Room Number: {{ $user->room_number }}</h5>
                    <p><strong>Name:</strong> {{ $user->full_name }}</p>
                    <p><strong>Mobile:</strong> <a href="tel:{{ $user->mobile_number }}">{{ $user->mobile_number }}</a></p>
                    <div class="user-actions">
                        <a href="{{ route('admin.users.view', $user->id) }}">View</a>
                        <a href="{{ route('admin.users.edit', $user->id) }}">Edit</a>
                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete User</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="mt-4 d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    @endif
</div>

@endsection


