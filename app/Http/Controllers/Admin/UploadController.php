<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MediaUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\SubCategory;

class UploadController extends Controller
{
    public function create()
    {
        $categories = Category::all(); // Assuming you have a Category model
        $subCategories = SubCategory::all(); // Assuming you have a SubCategory model

        return view('admin.upload', compact('categories', 'subCategories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'sub_category' => 'nullable|string|max:255',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,mp4,mov|max:10240',
            'description' => 'nullable|string',
        ]);

        foreach ($request->file('files') as $file) {
            $path = $file->store('uploads', 'public');

            MediaUpload::create([
                'title' => $request->input('title'),
                'category' => $request->input('category'),
                'sub_category' => $request->input('sub_category'),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'description' => $request->input('description'),
            ]);
        }

        return back()->with('success', 'Files uploaded successfully!');
    }

    public function index()
    {
        $uploads = MediaUpload::all();
        return view('admin.view', compact('uploads'));
    }

    public function edit($id)
    {
        $upload = MediaUpload::findOrFail($id);
        return view('admin.edit', compact('upload'));
    }

    public function update(Request $request, $id)
    {
        $upload = MediaUpload::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'sub_category' => 'nullable|string|max:255',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:10240',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('uploads', 'public');
                $upload->file_path = $path;
                $upload->file_type = $file->getClientOriginalExtension();
            }
        }

        $upload->update([
            'title' => $request->input('title'),
            'category' => $request->input('category'),
            'sub_category' => $request->input('sub_category'),
            'description' => $request->input('description'),
        ]);

        return back()->with('success', 'Upload updated successfully!');
    }
}
