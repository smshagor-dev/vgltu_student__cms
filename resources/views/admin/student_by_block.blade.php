@extends('layouts.admin_app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Students in Block: {{ $block }}</h2>

    <!-- Search Form -->
    <form method="GET" action="{{ route('students.by.block', ['block' => $block]) }}" class="mb-4 row justify-content-center">
        <!-- Religion Dropdown -->
        <div class="col-md-4 mb-3">
            <select name="religion" class="form-control">
                <option value="" selected disabled>Select Religion</option>
                @foreach($religions as $religion)
                    <option value="{{ $religion }}" {{ $religion == request('religion') ? 'selected' : '' }}>
                        {{ $religion }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Country Dropdown -->
        <div class="col-md-4 mb-3">
            <select name="country" class="form-control">
                <option value="" selected disabled>Select Country</option>
                @foreach($countries as $country)
                    <option value="{{ $country }}" {{ $country == request('country') ? 'selected' : '' }}>
                        {{ $country }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 mb-3">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fa fa-search"></i> Search
            </button>
        </div>
    </form>

    <!-- Block Cards -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        @foreach($floors as $floor => $rooms)
            <div class="col">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ $floor }}</h5>
                        <ul class="list-unstyled">
                            @foreach($rooms as $roomNumber => $students)
                                <li>
                                    Room Number: {{ $roomNumber }} ({{ count($students) }})
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#roomModal{{ $roomNumber }}">
                                        View
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="roomModal{{ $roomNumber }}" tabindex="-1" aria-labelledby="roomModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="roomModalLabel">Students in Room {{ $roomNumber }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <button class="btn btn-sm btn-primary mb-2" onclick="copyRoomData('roomData{{ $roomNumber }}')">Copy</button>
                                                    
                                                    <ul id="roomData{{ $roomNumber }}" style="text-align: left;">
                                                        @foreach($students as $student)
                                                            <li>Room number: {{ $student->room_number }} - {{ $student->full_name }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">View Details</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function copyRoomData(elementId) {
        const el = document.getElementById(elementId);
        if (!el) return;

        const range = document.createRange();
        range.selectNode(el);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);

        try {
            const successful = document.execCommand('copy');
            if (successful) {
                alert('Room data copied to clipboard!');
            } else {
                alert('Copy failed. Please try manually.');
            }
        } catch (err) {
            alert('Oops, copy not supported.');
        }

        window.getSelection().removeAllRanges();
    }
</script>

@endsection
