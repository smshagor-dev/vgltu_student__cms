@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Student Data</h2>

    {{-- Show general validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>There were some problems with your input:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @php
        $userHasRecord = \App\Models\StudentsData::where('user_id', auth()->id())->exists();
    @endphp
    @if (!$userHasRecord)
    <form action="{{ route('students_data.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Passport Number</label>
            <input type="text" name="passport_number" class="form-control @error('passport_number') is-invalid @enderror" value="{{ old('passport_number') }}" required>
            @error('passport_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Your Passport Photo</label>
            <input type="file" name="passport_photo" class="form-control @error('passport_photo') is-invalid @enderror" required>
            @error('passport_photo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Visa Start Date</label>
            <input type="date" name="visa_start_date" class="form-control @error('visa_start_date') is-invalid @enderror" value="{{ old('visa_start_date') }}" required>
            @error('visa_start_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Visa Expiry Date</label>
            <input type="date" name="visa_expiry_date" class="form-control @error('visa_expiry_date') is-invalid @enderror" value="{{ old('visa_expiry_date') }}" required>
            @error('visa_expiry_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Visa Photo</label>
            <input type="file" name="visa_photo" class="form-control @error('visa_photo') is-invalid @enderror" required>
            @error('visa_photo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Green Card Photo</label>
            <input type="file" name="green_card_photo" class="form-control @error('green_card_photo') is-invalid @enderror" required>
            @error('green_card_photo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Save Student Data</button>
    </form>
    @else
</div>
<div class="alert alert-warning">
        <strong>You have already submitted your student data.</strong> You can only submit once.
    </div>
@endif
@endsection
