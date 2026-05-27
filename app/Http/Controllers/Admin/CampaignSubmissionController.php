<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;

class CampaignSubmissionController extends Controller
{
    public function index(Campaign $campaign)
    {
        $submissions = $campaign->submissions()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('admin.campaigns.submissions', compact('campaign', 'submissions'));
    }
}
