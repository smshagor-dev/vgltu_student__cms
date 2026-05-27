@extends('layouts.admin_app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Create Header Menu</h2>
    <form method="POST" action="{{ route('admin.homepage.menus.store') }}">
        @csrf
        @include('admin.homepage.menus._form', ['menu' => new \App\Models\HeaderMenu()])
        <div class="mt-3">
            <button class="btn btn-primary">Create Menu</button>
        </div>
    </form>
</div>
@endsection
