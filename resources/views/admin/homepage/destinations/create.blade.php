@extends('layouts.admin_app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Create Study Destination</h2>
    <form method="POST" action="{{ route('admin.homepage.destinations.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.homepage.destinations._form', ['destination' => new \App\Models\StudyDestination()])
        <div class="mt-3">
            <button class="btn btn-primary">Create Destination</button>
        </div>
    </form>
</div>
@endsection
