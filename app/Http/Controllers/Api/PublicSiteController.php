<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\PublicSiteData;
use Illuminate\Http\JsonResponse;

class PublicSiteController extends Controller
{
    public function shell(): JsonResponse
    {
        return response()->json(PublicSiteData::shell());
    }

    public function homepage(): JsonResponse
    {
        return response()->json(PublicSiteData::homepage());
    }
}
