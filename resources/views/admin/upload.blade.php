@extends('layouts.admin_app')

@section('content')
<div class="container">
    <h2 class="my-4 text-center">Upload Media</h2>
    <div class="card shadow p-4">
        <form action="{{ route('admin.upload.storeMedia') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter title" required>
                </div>
                <div class="col-md-6">
                    <label for="category_type" class="form-label">Category Type</label>
                    <select name="category_type_id" id="category_type" class="form-select" required>
                        <option value="">Select Category Type</option>
                        @foreach($categoryTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->type }}</option>
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
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="sub_category" class="form-label">Sub Category</label>
                    <select name="sub_category" id="sub_category" class="form-select">
                        <option value="">Select Sub Category</option>
                        @foreach($subCategories as $subCategory)
                            <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Upload Type Selection -->
            <div class="mb-3">
                <label class="form-label">Upload Type</label>
                <div>
                    <input type="radio" id="upload_server" name="upload_type" value="server" checked>
                    <label for="upload_server">Upload to Server</label>

                    <input type="radio" id="upload_drive" name="upload_type" value="drive" class="ms-3">
                    <label for="upload_drive">Upload from Google Drive</label>
                </div>
            </div>

            <!-- File Upload Section -->
            <div id="server_upload_section">
                <div class="mb-3">
                    <label for="files" class="form-label">Upload Files</label>
                    <div id="file-input-container">
                        <div class="input-group mb-2">
                            <input type="file" name="files[]" id="files" class="form-control">
                        </div>
                    </div>
                    <button type="button" id="add-file" class="btn btn-secondary btn-sm">+ Add More Files</button>
                </div>
            </div>

            <!-- Google Drive Link Section -->
            <div id="drive_upload_section" style="display: none;">
                <div class="mb-3">
                    <label for="video_urls" class="form-label">Google Drive Video Links</label>
                    <div id="drive-input-container">
                        <div class="input-group mb-2">
                            <input type="url" name="video_url" id="video_url" class="form-control" placeholder="Enter Google Drive URL ">
                        </div>
                    </div>
                    <button type="button" id="add-drive-link" class="btn btn-secondary btn-sm">+ Add More Links</button>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter description"></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary px-5">Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Toggle upload sections based on selection
    document.querySelectorAll('input[name="upload_type"]').forEach((input) => {
        input.addEventListener('change', function () {
            if (this.value === 'server') {
                document.getElementById('server_upload_section').style.display = 'block';
                document.getElementById('drive_upload_section').style.display = 'none';
            } else {
                document.getElementById('server_upload_section').style.display = 'none';
                document.getElementById('drive_upload_section').style.display = 'block';
            }
        });
    });

    // Add more file input fields dynamically
    document.getElementById('add-file').addEventListener('click', function () {
        let container = document.getElementById('file-input-container');
        let fileInputWrapper = document.createElement('div');
        fileInputWrapper.classList.add('input-group', 'mb-2');

        fileInputWrapper.innerHTML = `
            <input type="file" name="files[]" class="form-control">
            <button type="button" class="btn btn-danger btn-sm remove-file">Remove</button>
        `;

        container.appendChild(fileInputWrapper);

        // Remove file input field
        fileInputWrapper.querySelector('.remove-file').addEventListener('click', function () {
            fileInputWrapper.remove();
        });
    });

    // Add more Google Drive video link inputs dynamically
    document.getElementById('add-drive-link').addEventListener('click', function () {
        let container = document.getElementById('drive-input-container');
        let linkInputWrapper = document.createElement('div');
        linkInputWrapper.classList.add('input-group', 'mb-2');

        linkInputWrapper.innerHTML = `
            <input type="url" name="video_urls[]" class="form-control" placeholder="Enter Google Drive URL">
            <button type="button" class="btn btn-danger btn-sm remove-link">Remove</button>
        `;

        container.appendChild(linkInputWrapper);

        // Remove link input field
        linkInputWrapper.querySelector('.remove-link').addEventListener('click', function () {
            linkInputWrapper.remove();
        });
    });
</script>
@endsection
