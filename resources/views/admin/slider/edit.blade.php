@extends('layouts.admin_app')

@section('content')
<h2 style="text-align: center;">Edit Slider</h2>

<form action="{{ route('sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; max-width: 400px; margin: 0 auto;">
    @csrf
    @method('PUT')
    
    <label for="title" style="margin-bottom: 5px;">Slider Title</label>
    <input type="text" name="title" id="title" value="{{ $slider->title }}" required style="padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;">
    
    <label for="image" style="margin-bottom: 5px;">Slider Image</label>
    <input type="file" name="image" id="image" style="padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;">
    
    <button type="submit" style="padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">Update Slider</button>
</form>

<a href="{{ route('sliders.index') }}" style="display: block; text-align: center; padding: 10px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; margin-top: 20px;">Back to Slider List</a>
@endsection