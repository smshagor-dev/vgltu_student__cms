@extends('layouts.admin_app')

@section('content')
<div class="container">
    <h2>Edit Student Data</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.studentsdata.update', $student->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Passport Number</label>
            <input type="text" name="passport_number" class="form-control" value="{{ $student->passport_number }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Visa Start Date</label>
            <input type="date" name="visa_start_date" class="form-control" value="{{ $student->visa_start_date }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Visa Expiry Date</label>
            <input type="date" name="visa_expiry_date" class="form-control" value="{{ $student->visa_expiry_date }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Passport Photo</label>
            <input type="file" name="passport_photo" class="form-control">
            <small>Current: <img src="{{ asset('storage/' . $student->passport_photo) }}" width="100"></small>
        </div>

        <div class="mb-3">
            <label class="form-label">Visa Photo</label>
            <input type="file" name="visa_photo" class="form-control">
            <small>Current: <img src="{{ asset('storage/' . $student->visa_photo) }}" width="100"></small>
        </div>

        <div class="mb-3">
            <label class="form-label">Green Card Photo</label>
            <input type="file" name="green_card_photo" class="form-control">
            <small>Current: <img src="{{ asset('storage/' . $student->green_card_photo) }}" width="100"></small>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">Update</button>
    </form>
</div>
@endsection
