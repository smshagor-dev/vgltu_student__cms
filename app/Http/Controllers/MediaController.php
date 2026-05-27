<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        return view('admin.upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'photos.*' => 'mimes:jpeg,png,jpg,gif|max:20480',
            'videos.*' => 'mimes:mp4,mov,avi,flv|max:1073741824',
        ]);

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('public/photos');
                Photo::create([
                    'file_path' => $path,
                ]);
            }
        }

        // Handle video uploads
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $path = $video->store('public/videos');
                Video::create([
                    'file_path' => $path,
                ]);
            }
        }

        return back()->with('success', 'Files uploaded successfully.');
    }
    

}
