@extends('layouts.admin_app')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="mb-1">Create Campaign</h2>
        <p class="text-muted mb-0">Build a student campaign with a title and multiple submission fields.</p>
    </div>

    <form method="POST" action="{{ route('admin.campaigns.store') }}">
        @include('admin.campaigns._form')
    </form>
</div>
@endsection
