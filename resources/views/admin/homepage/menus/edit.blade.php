@extends('layouts.admin_app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Edit Header Menu</h2>
    <form method="POST" action="{{ route('admin.homepage.menus.update', $menu) }}">
        @csrf
        @method('PUT')
        @include('admin.homepage.menus._form')
        <div class="mt-3">
            <button class="btn btn-primary">Update Menu</button>
        </div>
    </form>
</div>
@endsection
