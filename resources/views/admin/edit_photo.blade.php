@extends('layouts.admin_app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Photo</title>

    <!-- Include Bootstrap 4.6 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Additional inline custom styles */
        .preview img {
            max-width: 150px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Edit Photo</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.upload.updatePhoto', $photo->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="photo">Current Photo:</label><br>
            <img src="{{ Storage::url($photo->file_path) }}" alt="Current Photo" class="img-fluid preview">
        </div>

        <div class="form-group">
            <label for="new_photo">Upload New Photo:</label>
            <input type="file" name="new_photo" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary btn-block">Update Photo</button>
    </form>

    <form action="{{ route('admin.upload.destroyPhoto', $photo->id) }}" method="POST" class="mt-3">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-block">Delete Photo</button>
    </form>
</div>

<!-- Include Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
@endsection
