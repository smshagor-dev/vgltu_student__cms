@extends('layouts.admin_app')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }

        .container {
            max-width: 1180px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .add-video-btn {
            text-align: right;
            margin-bottom: 20px;
        }

        .add-video-btn a {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f1f1f1;
        }

        .video-row {
            display: flex;
            flex-direction: row;
            align-items: center;
            padding: 10px 0;
        }

        .video-row video {
            border-radius: 5px;
        }

        .video-info {
            padding-left: 20px;
        }

        .action-links a {
            color: #007bff;
            text-decoration: none;
            margin-right: 10px;
        }

        .delete-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .video-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .video-info {
                padding-left: 0;
                padding-top: 10px;
            }

            video {
                width: 100%;
            }

            table {
                width: 100%;
            }

            th, td {
                padding: 8px;
                font-size: 14px;
            }

            .add-video-btn a {
                padding: 8px 16px;
                font-size: 14px;
            }

            .action-links a, .delete-btn {
                display: block;
                margin-bottom: 8px;
            }
        }

        @media (max-width: 480px) {
            th, td {
                font-size: 12px;
                padding: 6px;
            }

            .add-video-btn {
                text-align: center;
            }

            .add-video-btn a {
                width: 100%;
                display: block;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Video List</h2>
        
        <!-- Add new video button -->
        <div class="add-video-btn">
            <a href="{{ route('admin.videos.create') }}">
                Add New Video
            </a>
        </div>

        <!-- Video table -->
        <table>
            <thead>
                <tr>
                    <th>Title & Description</th>
                    <th>Video</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($videos as $video)
                    <tr>
                        <td colspan="2">
                            <div class="video-row">
                                <div>
                                    <video width="200" controls>
                                        <source src="{{ asset('storage/'.$video->video_path) }}" type="video/mp4">
                                    </video>
                                </div>
                                <div class="video-info">
                                    <strong>{{ $video->title }}</strong><br>
                                    <span>{{ $video->short_description }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="action-links">
                            <!-- Edit link -->
                            <a href="{{ route('admin.videos.edit', $video->id) }}">Edit</a>
                            <!-- Delete form -->
                            <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>

@endsection
