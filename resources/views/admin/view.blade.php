@extends('layouts.admin_app')

@section('content')
<div class="container">
    <h2 class="my-4 text-center">Uploaded Media</h2>
    <div class="card shadow p-4">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Category Type</th>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>File</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($uploads as $upload)
                    <tr>
                        <td>{{ $upload->id }}</td>
                        <td>{{ $upload->title }}</td>
                        <td>{{ $upload->categoryType->type ?? 'N/A' }}</td>
                        <td>{{ $upload->category ?? 'N/A' }}</td>
                        <td>{{ $upload->sub_category ?? 'N/A' }}</td>
                        <td>
                            @if(in_array($upload->file_type, ['jpg', 'jpeg', 'png']))
                                <img src="{{ asset('storage/' . $upload->file_path) }}" alt="Image" width="50">
                            @elseif(in_array($upload->file_type, ['mp4', 'mov']))
                                <video width="50" controls>
                                    <source src="{{ asset('storage/' . $upload->file_path) }}" type="video/{{ $upload->file_type }}">
                                    Your browser does not support the video tag.
                                </video>
                            @else
                                <a href="{{ asset('storage/' . $upload->file_path) }}" target="_blank">View File</a>
                            @endif
                        </td>
                        <td>{{ $upload->description ?? 'No description' }}</td>
                        <td>
                            <a href="{{ route('admin.upload.edit', $upload->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.upload.destroy', $upload->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No uploads found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 d-flex justify-content-center">
            {{ $uploads->links() }}
        </div>
    </div>
</div>
@endsection
