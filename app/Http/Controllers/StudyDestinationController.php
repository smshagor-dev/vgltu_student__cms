<?php

namespace App\Http\Controllers;

use App\Models\StudyDestination;
use App\Support\PublicAsset;

class StudyDestinationController extends Controller
{
    public function show(string $slug)
    {
        $destination = StudyDestination::query()->where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('study-destinations.show', [
            'destination' => $destination,
            'flagUrl' => PublicAsset::url($destination->flag_image_path),
        ]);
    }
}
