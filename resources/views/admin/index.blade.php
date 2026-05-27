@extends('layouts.admin_app')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 style="text-align: center;" class="mb-0">Admin List</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-hover mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Photo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>
                                @if($admin->photo)
                                    <img src="{{ asset('storage/' . $admin->photo) }}" alt="Admin Photo" width="50" height="50" class="rounded-circle">
                                @else
                                    <img src="{{ asset('default-avatar.png') }}" alt="Default Photo" width="50" height="50" class="rounded-circle">
                                @endif
                            </td>
                            <td class="text-center">
                            <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-2">
                                <form action="{{ route('admin.destroy', $admin->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this admin?')">
                                        Delete
                                    </button>
                                </form>
                        
                                <form action="{{ route('admin.profile.reset_password', $admin->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to reset the password?');">
                                        Reset Password
                                    </button>
                                </form>

                            </div>
                        </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No admins found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4 d-flex justify-content-center">
                {{ $admins->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
