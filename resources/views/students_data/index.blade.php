@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Ditels</h2>
        <a href="{{ route('students_data.create') }}" class="btn btn-primary">Submit Your Documents</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Desktop View -->
    <div class="d-none d-md-block">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Passport Number</th>
                    <th>Passport Photo</th>
                    <th>Visa Start Date</th>
                    <th>Visa Expiry Date</th>
                    <th>Visa Photo</th>
                    <th>Green Card Photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @if ($students->isNotEmpty())
                @foreach ($students as $student)
                <tr>
                    <td>{{ $student->full_name }}</td>
                    <td>{{ $student->passport_number }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $student->passport_photo) }}" width="80" height="50" class="clickable-image" data-image="{{ asset('storage/' . $student->passport_photo) }}">
                    </td>
                    <td>{{ $student->visa_start_date }}</td>
                    <td>{{ $student->visa_expiry_date }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $student->visa_photo) }}" width="80" height="50" class="clickable-image" data-image="{{ asset('storage/' . $student->visa_photo) }}">
                    </td>
                    <td>
                        <img src="{{ asset('storage/' . $student->green_card_photo) }}" width="80" height="50" class="clickable-image" data-image="{{ asset('storage/' . $student->green_card_photo) }}">
                    </td>
                    <td>
                        @if (auth()->id() == $student->user_id)
                            <a href="{{ route('students_data.edit', $student->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            @else
                <p>No details found.</p>
            @endif
        </table>
    </div>

    <!-- Mobile View -->
    <div class="d-md-none">
    @if ($students->isNotEmpty())
        @foreach ($students as $student)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Name: {{ $student->full_name }}</h5>
                <p><strong>Passport Number:</strong> {{ $student->passport_number }}</p>
                <p><strong>Visa Start Date:</strong> {{ $student->visa_start_date }}</p>
                <p><strong>Visa Expiry Date:</strong> {{ $student->visa_expiry_date }}</p>
                <p style="text-align: center;"> Submited Photo </p>
                <p><strong>Passport Photo:</strong></p>
                <img src="{{ asset('storage/' . $student->passport_photo) }}" class="img-fluid clickable-image" data-image="{{ asset('storage/' . $student->passport_photo) }}">

                <p><strong>Visa Photo:</strong></p>
                <img src="{{ asset('storage/' . $student->visa_photo) }}" class="img-fluid clickable-image" data-image="{{ asset('storage/' . $student->visa_photo) }}">

                <p><strong>Green Card Photo:</strong></p>
                <img src="{{ asset('storage/' . $student->green_card_photo) }}" class="img-fluid clickable-image" data-image="{{ asset('storage/' . $student->green_card_photo) }}">

                @if (auth()->id() == $student->user_id)
                <a href="{{ route('students_data.edit', $student->id) }}" class="btn btn-warning btn-lg mt-2 px-4 py-2 fw-bold">Edit</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
        <p>No details found.</p>
    @endif
</div>
@endsection

