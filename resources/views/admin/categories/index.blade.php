@extends('layouts.admin_app')

@section('content')


<h1 class="mb-4">Manage Categories & Subcategories</h1>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

    <!-- Error Popup -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Whoops! Something went wrong.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

<!-- Create Category Form -->
<div class="card mb-4">
    <div class="card-header">Create Category</div>
    <div class="card-body">
        <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="category_type_id">Category Type</label>
                <select name="category_type_id" id="category_type_id" class="form-control" required>
                    @foreach($categoryTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="photo">Photo</label>
                <input type="file" name="photo" id="photo" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

    </div>
</div>

<!-- Create Subcategory Form -->
<div class="card mb-4">
    <div class="card-header">Create Subcategory</div>
    <div class="card-body">
        <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Subcategory Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="category_id">Parent Category</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    @foreach($allCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="photo">Photo</label>
                <input type="file" name="photo" id="photo" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Create Subcategory</button>
        </form>
    </div>
</div>


<!-- Categories and Subcategories Table -->
<div class="card">
    <div class="card-header">All Categories</div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Photos</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->categoryType->type }}</td>
                    <td>
                    @if($category->photo)
                        <img src="{{ asset('storage/' . $category->photo) }}" alt="Category Photo" style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        <p>No photo available</p>
                    @endif

                    </td>
                    <td>
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="{{ route('categories.destroy', $category->id) }}" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Categories and Subcategories Table -->
<div class="card">
    <div class="card-header">All Subcategories</div>
    <div class="card-body">
    <table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Type</th>
            <th>Category Name</th>
            <th>Subcategory Names</th>
            <th>Subcategory Photos</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $category->categoryType->type }}</td>
            <td>{{ $category->name }}</td>
            <td>
                @if($category->subCategories->isNotEmpty())
                    <ul>
                        @foreach($category->subCategories as $subCategory)
                            <li>{{ $subCategory->name }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>No subcategories available</p>
                @endif
            </td>
            <td>
                @if($category->subCategories->isNotEmpty())
                    <ul>
                        @foreach($category->subCategories as $subCategory)
                            @if($subCategory->photo)
                                <li>
                                    <img src="{{ asset('storage/' . $subCategory->photo) }}" alt="Subcategory Photo" style="width: 40px; height: 40px; object-fit: cover;">
                                </li>
                            @else
                                <li>No photo available</li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <p>No subcategories available</p>
                @endif
            </td>
            <td>
                <!-- Subcategory Actions -->
                @if($category->subCategories->isNotEmpty())
                    <ul>
                        @foreach($category->subCategories as $subCategory)
                            <li>
                                <!-- <a href="{{ route('subcategories.edit', $subCategory->id) }}" class="btn btn-sm btn-warning">Edit</a> -->
                                <form method="POST" action="{{ route('subcategories.custom-destroy', $subCategory->id) }}" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $categories->links() }}
</div>

@endsection
