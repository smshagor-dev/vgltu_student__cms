<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MediaUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\CategoryType;
use App\Http\Controllers\CategoryController;
use App\Support\ImageCompressor;

class UploadController extends Controller
{
    public function create()
    {   
        $categoryTypes = CategoryType::all();
        $categories = Category::all();
        $subCategories = SubCategory::all();

        return view('admin.upload', compact('categories', 'subCategories', 'categoryTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_type_id' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'sub_category' => 'nullable|string|max:255',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,mp4,mov|max:1000240',
            'description' => 'nullable|string',
        ]);

        foreach ($request->file('files') as $file) {
            $path = ImageCompressor::canCompress($file)
                ? ImageCompressor::storeUploadedFile($file, 'uploads')
                : $file->store('uploads', 'public');

            MediaUpload::create([
                'title' => $request->input('title'),
                'category_type_id' => $request->input('category_type_id'),
                'category' => $request->input('category'),
                'sub_category' => $request->input('sub_category'),
                'file_path' => $path,
                'file_type' => ImageCompressor::canCompress($file) ? 'webp' : $file->getClientOriginalExtension(),
                'description' => $request->input('description'),
            ]);
        }

        return back()->with('success', 'Files uploaded successfully!');
    }

    public function index()
    {
        $uploads = MediaUpload::with('categoryType')
            ->latest()
            ->paginate(20);
        return view('admin.view', compact('uploads'));
    }

    public function edit($id)
    {
        $upload = MediaUpload::findOrFail($id);
        $categories = Category::all();
        $subCategories = SubCategory::all();
        $categoryTypes = CategoryType::all();

        return view('admin.edit', compact('upload', 'categories', 'subCategories', 'categoryTypes'));
    }

    public function update(Request $request, $id)
    {
        $upload = MediaUpload::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category_type_id' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'sub_category' => 'nullable|string|max:255',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:1000240',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = ImageCompressor::canCompress($file)
                    ? ImageCompressor::storeUploadedFile($file, 'uploads')
                    : $file->store('uploads', 'public');
                $upload->file_path = $path;
                $upload->file_type = ImageCompressor::canCompress($file) ? 'webp' : $file->getClientOriginalExtension();
            }
        }

        $upload->update([
            'title' => $request->input('title'),
            'category_type_id' => $request->input('category_type_id'),
            'category' => $request->input('category'),
            'sub_category' => $request->input('sub_category'),
            'description' => $request->input('description'),
        ]);

        return back()->with('success', 'Upload updated successfully!');
    }

    public function destroy($id)
    {
        $upload = MediaUpload::findOrFail($id);

        // Optionally delete the file from storage
        if (file_exists(public_path('storage/' . $upload->file_path))) {
            unlink(public_path('storage/' . $upload->file_path));
        }

        // Delete the record from the database
        $upload->delete();

        return redirect()->route('admin.upload.index')->with('success', 'Upload deleted successfully.');
    }
    
    // Google Drive video 

    public function storeMedia(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_type_id' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'sub_category' => 'nullable|string|max:255',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:1000240',
            'video_url' => 'nullable|url', // Validate URL for Google Drive
            'description' => 'nullable|string',
        ]);
    
        // If files are uploaded
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Store the file and save its path
                $path = ImageCompressor::canCompress($file)
                    ? ImageCompressor::storeUploadedFile($file, 'uploads')
                    : $file->store('uploads', 'public');
                
                MediaUpload::create([
                    'title' => $request->input('title'),
                    'category_type_id' => $request->input('category_type_id'),
                    'category' => $request->input('category'),
                    'sub_category' => $request->input('sub_category'),
                    'file_path' => $path,
                    'file_type' => ImageCompressor::canCompress($file) ? 'webp' : $file->getClientOriginalExtension(),
                    'description' => $request->input('description'),
                ]);
            }
        }
    
        // If a Google Drive URL is provided
        if ($request->filled('video_url')) {
            // Extract the Google Drive video ID from the URL
            preg_match('/\/d\/(.*?)\//', $request->video_url, $matches);
            $videoId = $matches[1] ?? null;
    
            if (!$videoId) {
                return back()->withErrors(['video_url' => 'Invalid Google Drive link format.']);
            }
    
            // Save the Google Drive video with its ID
            MediaUpload::create([
                'title' => $request->input('title'),
                'category_type_id' => $request->input('category_type_id'),
                'category' => $request->input('category'),
                'sub_category' => $request->input('sub_category'),
                'file_path' => $videoId, // Store the Google Drive ID as the path
                'file_type' => 'google_drive', // Mark it as a Google Drive link
                'description' => $request->input('description'),
            ]);
        }
    
        return back()->with('success', 'Media uploaded successfully!');
    }
    
    public function showPhotos($categoryId, $subcategoryId)
    {
        // Retrieve the category, subcategory, and their associated photos
        $category = Category::findOrFail($categoryId);
        $subcategory = SubCategory::findOrFail($subcategoryId);
    
        // Fetch photos related to the subcategory (assuming the relationship exists)
        $photos = MediaUpload::where('category', $categoryId)
                             ->where('sub_category', $subcategoryId)
                             ->get();
    
        return view('subcategory.photos', compact('category', 'subcategory', 'photos'));
    }

}
