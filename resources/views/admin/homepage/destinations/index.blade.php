@extends('layouts.admin_app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Study Destinations</h2>
        <a href="{{ route('admin.homepage.destinations.create') }}" class="btn btn-primary">Add Destination</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($destinations as $destination)
                        <tr>
                            <td>{{ $destination->name }}</td>
                            <td>{{ $destination->slug }}</td>
                            <td>{{ $destination->display_order }}</td>
                            <td>{{ $destination->is_active ? 'Active' : 'Inactive' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.homepage.destinations.edit', $destination) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.homepage.destinations.destroy', $destination) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this destination?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4">No destinations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body border-top">
            <div class="d-flex justify-content-center">
                {{ $destinations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
