@extends('layouts.admin_app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">{{ $campaign->title }} Submissions</h2>
            <p class="text-muted mb-0">Submitted responses from students for this campaign.</p>
        </div>
        <a href="{{ route('admin.campaigns.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Room Number</th>
                        <th>Submitted At</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($submissions as $submission)
                        @php
                            $student = $submission->user;
                            $photo = $student && $student->photo ? asset('storage/' . $student->photo) : asset('default-avatar.png');
                        @endphp
                        <tr>
                            <td>
                                <img
                                    src="{{ $photo }}"
                                    alt="{{ $student?->full_name ?? 'Student' }}"
                                    class="rounded-circle border"
                                    width="52"
                                    height="52"
                                    style="width:52px;height:52px;object-fit:cover;"
                                    onerror="this.onerror=null;this.src='{{ asset('default-avatar.png') }}';"
                                >
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $student?->full_name ?? 'Unknown User' }}</div>
                                <div class="text-muted small">{{ $student?->email ?? 'No email available' }}</div>
                            </td>
                            <td>{{ $student?->room_number ?: 'N/A' }}</td>
                            <td>{{ $submission->created_at?->format('d M Y, h:i A') }}</td>
                            <td class="text-end">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#submissionModal{{ $submission->id }}"
                                >
                                    View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No submissions found for this campaign yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body border-top">
            <div class="d-flex justify-content-center">
                {{ $submissions->links() }}
            </div>
        </div>
    </div>
</div>

@foreach ($submissions as $submission)
    @php
        $student = $submission->user;
        $photo = $student && $student->photo ? asset('storage/' . $student->photo) : asset('default-avatar.png');
    @endphp

    <div class="modal fade" id="submissionModal{{ $submission->id }}" tabindex="-1" aria-labelledby="submissionModalLabel{{ $submission->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="submissionModalLabel{{ $submission->id }}">Submission Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-3 flex-wrap mb-4">
                        <img
                            src="{{ $photo }}"
                            alt="{{ $student?->full_name ?? 'Student' }}"
                            class="rounded-circle border"
                            width="64"
                            height="64"
                            style="width:64px;height:64px;object-fit:cover;"
                            onerror="this.onerror=null;this.src='{{ asset('default-avatar.png') }}';"
                        >
                        <div>
                            <div class="fw-bold fs-5">{{ $student?->full_name ?? 'Unknown User' }}</div>
                            <div class="text-muted">{{ $student?->email ?? 'No email available' }}</div>
                            <div class="text-muted small mt-1">Room Number: {{ $student?->room_number ?: 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        @foreach ($submission->submission ?? [] as $item)
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100 bg-light">
                                    <div class="fw-semibold">{{ $item['field_name'] ?? 'Field' }}</div>
                                    <div class="mt-2">
                                        @php($value = strtolower((string) ($item['value'] ?? '')))
                                        @if ($value === 'yes')
                                            <span class="badge bg-success">Yes</span>
                                        @elseif ($value === 'no')
                                            <span class="badge bg-danger">No</span>
                                        @else
                                            <span class="text-muted">{{ $item['value'] ?? '-' }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
