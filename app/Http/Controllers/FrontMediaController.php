<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class FrontMediaController extends Controller
{
    // Display all photos and videos
    public function index()
    {
        $photos = Photo::all();
        $videos = Video::all();

        return view('front.media', compact('photos', 'videos'));
    }

    // Download a photo
    public function downloadPhoto($id)
    {
        $photo = Photo::findOrFail($id);
        return Storage::download($photo->file_path);
    }

    // Download a video
    public function downloadVideo($id)
    {
        $video = Video::findOrFail($id);
        return Storage::download($video->file_path);
    }
}
