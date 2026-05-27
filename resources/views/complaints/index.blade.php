@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Your Complaints</h2>
    <a href="{{ route('complaints.create') }}" class="btn btn-success mb-3">New Complaint</a>
    <table class="table">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Status</th>
                <th>Submitted At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($complaints as $complaint)
                <tr>
                    <td>{{ $complaint->subject }}</td>
                    <td>
                        <span class="badge bg-{{ $complaint->status == 'pending' ? 'warning' : ($complaint->status == 'in_progress' ? 'primary' : 'success') }}">
                            {{ ucfirst($complaint->status) }}
                        </span>
                    </td>
                    <td>{{ $complaint->created_at->format('d M Y, H:i') }}</td>
                    <td><a href="{{ route('complaints.show', $complaint->id) }}" class="btn btn-info btn-sm">View</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
