@extends('layouts.admin_app')

@section('content')

<h2 style="text-align: center;">Slider List</h2>

<a href="{{ route('sliders.create') }}" style="text-align: center; padding: 10px; background-color: #28a745; color: white; text-decoration: none; border-radius: 4px; margin-top: 20px;">Add New Slider</a>

@foreach ($sliders as $slider)
    <div style="border: 1px solid #ddd; padding: 10px; margin: 10px 0; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="margin: 0;">{{ $slider->title }}</h3>
            <img src="{{ asset('images/sliders/'.$slider->image) }}" alt="{{ $slider->title }}" style="width: 200px; height: 100px; object-fit: cover;">
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('sliders.edit', $slider->id) }}" style="padding: 5px 10px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 4px;">Edit</a>
            <form action="{{ route('sliders.destroy', $slider->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" style="padding: 5px 10px; background-color: #dc3545; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Delete</button>
            </form>
        </div>
    </div>
@endforeach

<div class="mt-4 d-flex justify-content-center">
    {{ $sliders->links() }}
</div>


@endsection
