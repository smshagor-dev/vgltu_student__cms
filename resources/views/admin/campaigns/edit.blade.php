@extends('layouts.admin_app')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="mb-1">Edit Campaign</h2>
        <p class="text-muted mb-0">Update the campaign title, fields, or visibility.</p>
    </div>

    <form method="POST" action="{{ route('admin.campaigns.update', $campaign) }}">
        @csrf
        @method('PUT')
        @include('admin.campaigns._form')
    </form>
</div>
@endsection
