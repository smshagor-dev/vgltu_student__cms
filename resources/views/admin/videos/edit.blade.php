@extends('layouts.admin_app')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Video</title>

    <!-- TinyMCE Script for editing video description -->
    <script src="https://cdn.tiny.cloud/1/wj0tnfge5dwt2pfaan81gg68pfs8bqtzmjrn9k5kxmwaqb0e/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#description', 
            plugins: 'lists link image table code',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
            menubar: false
        });
    </script>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #333;">Edit Video</h2>

        <form action="{{ route('admin.videos.update', $video->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Title Input -->
            <div style="margin-bottom: 15px;">
                <label for="title" style="display: block; font-weight: bold; margin-bottom: 5px;">Title:</label>
                <input type="text" name="title" value="{{ $video->title }}" required 
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <!-- Short Description Input -->
            <div style="margin-bottom: 15px;">
                <label for="short_description" style="display: block; font-weight: bold; margin-bottom: 5px;">Short Description:</label>
                <input type="text" name="short_description" value="{{ $video->short_description }}" required
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <!-- Description Input with TinyMCE -->
            <div style="margin-bottom: 15px;">
                <label for="description" style="display: block; font-weight: bold; margin-bottom: 5px;">Description:</label>
                <textarea id="description" name="description" required 
                          style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">{{ $video->description }}</textarea>
            </div>

            <!-- Video Input (Optional to upload new video) -->
            <div style="margin-bottom: 15px;">
                <label for="video" style="display: block; font-weight: bold; margin-bottom: 5px;">Upload New Video:</label>
                <input type="file" name="video" accept="video/*" 
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <!-- Submit Button -->
            <div style="text-align: center;">
                <button type="submit" 
                        style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                    Update Video
                </button>
            </div>
        </form>
    </div>
</body>
</html>

@endsection