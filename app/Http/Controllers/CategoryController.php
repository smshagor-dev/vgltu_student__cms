<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\CategoryPhoto;
use App\Models\CategoryType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Support\ImageCompressor;



class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('categoryType', 'subCategories')
            ->latest()
            ->paginate(20);
        $allCategories = Category::orderBy('name')->get();
        $categoryTypes = CategoryType::all();

        return view('admin.categories.index', compact('categories', 'allCategories', 'categoryTypes'));
    }


    public function store(Request $request)
    {
        // Determine if this is a subcategory creation
        $isSubcategory = $request->has('category_id');

        // Validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            $isSubcategory ? 'category_id' : 'category_type_id' => 'required|exists:' . ($isSubcategory ? 'categories' : 'category_types') . ',id',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:20048', // Photo validation
        ]);

        // Handle file upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = ImageCompressor::storeUploadedFile(
                $request->file('photo'),
                $isSubcategory ? 'subcategory_photos' : 'category_photos'
            );
        }

        if ($isSubcategory) {
            // Create subcategory
            SubCategory::create([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'photo' => $photoPath, // Save photo path
            ]);

            return redirect()->route('categories.index')->with('success', 'Subcategory created successfully!');
        } else {
            // Create category
            Category::create([
                'name' => $validated['name'],
                'category_type_id' => $validated['category_type_id'],
                'photo' => $photoPath, // Save photo path
            ]);

            return redirect()->route('categories.index')->with('success', 'Category created successfully!');
        }
    }

    public function edit($id)
    {
        $isSubcategory = false;
        $category = Category::find($id);
        $categoryTypes = CategoryType::all();

        return view('admin.categories.edit', compact('category', 'categoryTypes', 'isSubcategory'));
    }

    public function editSubcategory($id)
    {
        $isSubcategory = true;
        $subcategory = SubCategory::find($id);
        $categories = Category::all();

        return view('admin.categories.edit', compact('subcategory', 'categories', 'isSubcategory'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_type_id' => 'nullable|exists:category_types,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = Category::findOrFail($id);

        $category->name = $validated['name'];
        $category->category_type_id = $validated['category_type_id'];

        if ($request->hasFile('photo')) {
            if ($category->photo && Storage::exists('public/' . $category->photo)) {
                Storage::delete('public/' . $category->photo);
            }

            $category->photo = ImageCompressor::storeUploadedFile($request->file('photo'), 'category_photos');
        }

        $category->save();

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    public function updateSubcategory(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $subcategory = SubCategory::findOrFail($id);

        $subcategory->name = $validated['name'];
        $subcategory->category_id = $validated['category_id'];

        if ($request->hasFile('photo')) {
            if ($subcategory->photo && Storage::exists('public/' . $subcategory->photo)) {
                Storage::delete('public/' . $subcategory->photo);
            }

            $subcategory->photo = ImageCompressor::storeUploadedFile($request->file('photo'), 'subcategory_photos');
        }

        $subcategory->save();

        return redirect()->route('categories.index')->with('success', 'Subcategory updated successfully!');
    }

    

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }

    public function getCategories($typeId)
    {
        // Fetch categories based on the given category_type_id
        $categories = Category::where('category_type_id', $typeId)->get();

        // Return the categories as JSON
        return response()->json($categories);
    }

    public function getSubCategories($categoryId)
    {
        $subCategories = SubCategory::where('category_id', $categoryId)->get(['id', 'name']);
        return response()->json($subCategories);
    }
    
    public function destroySubCategory(SubCategory $subCategory)
    {
        // Delete the associated photo if it exists
        if ($subCategory->photo && Storage::exists('public/' . $subCategory->photo)) {
            Storage::delete('public/' . $subCategory->photo);
        }
    
        $subCategory->delete();
    
        return redirect()->route('categories.index')->with('success', 'Subcategory deleted successfully!');
    }

}
