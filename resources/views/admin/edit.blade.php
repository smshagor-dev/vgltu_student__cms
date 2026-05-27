@extends('layouts.admin_app')

@section('content')
<div class="container">
    <h2 class="my-4 text-center">Edit Media</h2>
    <div class="card shadow p-4">
        <form action="{{ route('admin.upload.update', $upload->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ $upload->title }}" required>
                </div>
                <div class="col-md-6">
                    <label for="category_type" class="form-label">Category Type</label>
                    <select name="category_type_id" id="category_type" class="form-select" required>
                        <option value="">Select Category Type</option>
                        @foreach($categoryTypes as $type)
                            <option value="{{ $type->id }}" {{ $upload->category_type_id == $type->id ? 'selected' : '' }}>{{ $type->type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="category" class="form-label">Category</label>
                    <select name="category" id="category" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $upload->category == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="sub_category" class="form-label">Sub Category</label>
                    <select name="sub_category" id="sub_category" class="form-select">
                        <option value="">Select Sub Category</option>
                        @foreach($subCategories as $subCategory)
                            <option value="{{ $subCategory->id }}" {{ $upload->sub_category == $subCategory->id ? 'selected' : '' }}>{{ $subCategory->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="files" class="form-label">Replace File (Optional)</label>
                <input type="file" name="files[]" id="files" class="form-control">
                @if($upload->file_path)
                    <small>Current File: <a href="{{ asset('storage/' . $upload->file_path) }}" target="_blank">{{ $upload->file_path }}</a></small>
                @endif
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4">{{ $upload->description }}</textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary px-5">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
