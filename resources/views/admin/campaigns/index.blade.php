@extends('layouts.admin_app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Campaigns</h2>
        <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary">Create Campaign</a>
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
                        <th>Fields</th>
                        <th>Status</th>
                        <th>Submissions</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($campaigns as $campaign)
                        <tr>
                            <td>{{ $campaign->title }}</td>
                            <td>{{ implode(', ', $campaign->field_names ?? []) }}</td>
                            <td>{{ $campaign->is_active ? 'Active' : 'Inactive' }}</td>
                            <td>{{ $campaign->submissions_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.campaigns.submissions', $campaign) }}" class="btn btn-sm btn-outline-dark">Submissions</a>
                                <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this campaign?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No campaigns created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body border-top">
            <div class="d-flex justify-content-center">
                {{ $campaigns->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
