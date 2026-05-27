@extends('layouts.admin_app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Edit Study Destination</h2>
    <form method="POST" action="{{ route('admin.homepage.destinations.update', $destination) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.homepage.destinations._form')
        <div class="mt-3">
            <button class="btn btn-primary">Update Destination</button>
        </div>
    </form>
</div>
@endsection
