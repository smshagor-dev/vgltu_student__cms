@extends('layouts.admin_app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Add Department</h2>
    <form method="POST" action="{{ route('admin.homepage.courses.store') }}">
        @csrf
        @include('admin.homepage.courses._form', ['course' => $course])
        <div class="mt-3 admin-actions-inline">
            <button class="btn btn-primary">Create Department</button>
            <a href="{{ route('admin.homepage.pages.courses.edit') }}" class="btn btn-outline-primary">Back</a>
        </div>
    </form>
</div>

<script src="https://cdn.tiny.cloud/1/wj0tnfge5dwt2pfaan81gg68pfs8bqtzmjrn9k5kxmwaqb0e/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea#course_description',
        height: 380,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | code',
    });
</script>
@endsection
