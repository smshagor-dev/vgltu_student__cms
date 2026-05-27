@extends('layouts.app')

@section('content')
<div style="text-align: center; margin-top: 20px;">
    <h1 style="font-size: 2rem; font-weight: bold; margin-bottom: 20px;">Videos for {{ $category->name }}</h1>
</div>

<!-- Card Layout for Subcategories -->
<div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; margin-top: 20px;">
    @foreach ($category->subCategories as $subCategory)
        <div style="
            width: 300px; 
            background-color: #f9f9f9; 
            border: 1px solid #ddd; 
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
        " 
        onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 6px 14px rgba(0, 0, 0, 0.15)';" 
        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 10px rgba(0, 0, 0, 0.1)';">
            <div style="background-color: #007BFF; color: #fff; padding: 15px; text-align: center; font-size: 1.2rem; font-weight: bold;">
                {{ $subCategory->name }}
            </div>
            <div style="padding: 15px; text-align: center;">
                <!-- Link to view videos for the subcategory -->
                <a href="{{ route('subcategory.videos', ['category_id' => $category->id, 'subcategory_id' => $subCategory->id]) }}" 
                   style="color: #007BFF; text-decoration: none; font-size: 1.1rem; font-weight: bold;">
                    View Videos
                </a>
            </div>
        </div>
    @endforeach
</div>
@endsection
