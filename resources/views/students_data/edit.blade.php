@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Visa Details</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('students_data.update', $studentData->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Visa Start Date</label>
            <input type="date" name="visa_start_date" class="form-control" value="{{ $studentData->visa_start_date }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Visa Expiry Date</label>
            <input type="date" name="visa_expiry_date" class="form-control" value="{{ $studentData->visa_expiry_date }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Visa Photo</label>
            <input type="file" name="visa_photo" class="form-control">
            <small>Current: <img src="{{ asset('storage/' . $studentData->visa_photo) }}" width="100"></small>
        </div>

        <button type="submit" class="btn btn-primary">Update Visa Details</button>
    </form>
</div>
@endsection
