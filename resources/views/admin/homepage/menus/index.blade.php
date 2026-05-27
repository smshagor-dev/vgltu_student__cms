@extends('layouts.admin_app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Header Menus</h2>
        <a href="{{ route('admin.homepage.menus.create') }}" class="btn btn-primary">Add Menu</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Parent</th>
                        <th>URL</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($menus as $menu)
                        <tr>
                            <td>{{ $menu->title }}</td>
                            <td>{{ $menu->parent?->title ?: 'Root' }}</td>
                            <td>{{ $menu->url }}</td>
                            <td>{{ $menu->sort_order }}</td>
                            <td>{{ $menu->is_active ? 'Active' : 'Inactive' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.homepage.menus.edit', $menu) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.homepage.menus.destroy', $menu) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this menu?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">No menu items found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body border-top">
            <div class="d-flex justify-content-center">
                {{ $menus->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
