@extends('layouts.admin_app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Video</title>

    <!-- Include Bootstrap 4.6 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Additional inline custom styles */
        .preview video {
            max-width: 300px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Edit Video</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.upload.updateVideo', $video->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="video">Current Video:</label><br>
            <video width="300" controls>
                <source src="{{ Storage::url($video->file_path) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>

        <div class="form-group">
            <label for="new_video">Upload New Video:</label>
            <input type="file" name="new_video" class="form-control" accept="video/*">
        </div>

        <button type="submit" class="btn btn-primary btn-block">Update Video</button>
    </form>

    <form action="{{ route('admin.upload.destroyVideo', $video->id) }}" method="POST" class="mt-3">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-block">Delete Video</button>
    </form>
</div>

<!-- Include Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
@endsection
