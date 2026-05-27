<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::where('is_active', true)
            ->with(['submissions' => function ($query) {
                $query->where('user_id', Auth::id());
            }])
            ->latest()
            ->get();

        return view('campaigns.index', compact('campaigns'));
    }

    public function show(Campaign $campaign)
    {
        abort_unless($campaign->is_active, 404);

        $submission = $campaign->submissions()->where('user_id', Auth::id())->first();
        $fieldDefinitions = $campaign->normalizedFieldDefinitions();

        return view('campaigns.show', compact('campaign', 'submission', 'fieldDefinitions'));
    }

    public function store(Request $request, Campaign $campaign)
    {
        abort_unless($campaign->is_active, 404);

        $existingSubmission = $campaign->submissions()->where('user_id', Auth::id())->first();
        if ($existingSubmission) {
            return redirect()->route('campaigns.show', $campaign)->with('error', 'You have already submitted this campaign.');
        }

        $rules = [];
        $fieldDefinitions = $campaign->normalizedFieldDefinitions();
        foreach ($fieldDefinitions as $index => $fieldDefinition) {
            $rules['submission.' . $index] = $fieldDefinition['type'] === 'checkbox'
                ? 'required|in:yes,no'
                : 'required|string|max:5000';
        }

        $validated = $request->validate($rules);

        $submissionData = [];
        foreach ($fieldDefinitions as $index => $fieldDefinition) {
            $submissionData[] = [
                'field_name' => $fieldDefinition['label'],
                'field_type' => $fieldDefinition['type'],
                'value' => trim((string) ($validated['submission'][$index] ?? '')),
            ];
        }

        CampaignSubmission::create([
            'campaign_id' => $campaign->id,
            'user_id' => Auth::id(),
            'submission' => $submissionData,
        ]);

        return redirect()->route('campaigns.show', $campaign)->with('success', 'Campaign submitted successfully.');
    }
}
