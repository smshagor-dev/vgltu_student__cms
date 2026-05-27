@extends('layouts.admin_app')

@section('content')
<h1 class="mb-4">Edit {{ isset($isSubcategory) && $isSubcategory ? 'Subcategory' : 'Category' }}</h1>

<div class="card">
    <div class="card-header">Edit {{ isset($isSubcategory) && $isSubcategory ? 'Subcategory' : 'Category' }}</div>
    <div class="card-body">
        <form method="POST" action="{{ isset($isSubcategory) && $isSubcategory ? route('subcategories.update', $subcategory->id) : route('categories.update', $category->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">{{ isset($isSubcategory) && $isSubcategory ? 'Subcategory' : 'Category' }} Name</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="{{ isset($isSubcategory) && $isSubcategory ? $subcategory->name : $category->name }}" required>
            </div>

            @if(isset($isSubcategory) && $isSubcategory)
                <div class="mb-3">
                    <label for="category_id" class="form-label">Parent Category</label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        @foreach($categories as $parentCategory)
                            <option value="{{ $parentCategory->id }}" 
                                    {{ $subcategory->category_id == $parentCategory->id ? 'selected' : '' }}>
                                {{ $parentCategory->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <div class="mb-3">
                    <label for="category_type_id" class="form-label">Category Type</label>
                    <select class="form-control" id="category_type_id" name="category_type_id" required>
                        @foreach($categoryTypes as $type)
                            <option value="{{ $type->id }}" 
                                    {{ $category->category_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->type }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="mb-3">
                <label for="photo" class="form-label">Photo</label>
                @if(isset($isSubcategory) && $isSubcategory ? $subcategory->photo : $category->photo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . (isset($isSubcategory) && $isSubcategory ? $subcategory->photo : $category->photo)) }}" 
                             alt="Current Photo" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                @endif
                <input type="file" class="form-control" id="photo" name="photo">
                <small class="form-text text-muted">Leave blank if you don't want to update the photo.</small>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection

