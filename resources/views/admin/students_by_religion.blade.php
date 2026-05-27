@extends('layouts.admin_app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Students by Religion </h2>

    <!-- Search Form -->
    <form method="GET" action="{{ route('students.by.religion') }}" class="mb-4 row justify-content-center">
        <!-- Religion Dropdown -->
        <div class="col-md-4 mb-3">
            <select name="religion" class="form-control">
                <option value="" selected disabled>Select Religion</option>
                @foreach($religions as $religion)
                <option value="{{ $religion }}" {{ request('religion') == $religion ? 'selected' : '' }}>
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
                <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
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
        @foreach($structuredData as $block => $floors)
        <div class="col">
            <a href="{{ route('students.by.block', ['block' => $block]) }}"
                class="card h-100 text-center shadow-sm text-decoration-none">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $block }}</h5>
                    <p class="card-text text-secondary">Total Rooms: <strong>{{ count($floors) }}</strong></p>

                    @php
                    $totalStudents = 0;
                    foreach ($floors as $rooms) {
                    foreach ($rooms as $room) {
                    $totalStudents += count($room);
                    }
                    }
                    @endphp

                    <p class="card-text text-secondary">Total Students: <strong>{{ $totalStudents }}</strong></p>
                </div>

                <div class="card-footer">
                    <small class="text-muted">View Details</small>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection