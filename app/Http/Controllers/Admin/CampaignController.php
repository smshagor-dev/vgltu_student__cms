<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\User;
use App\Support\UserEmailService;
use App\Support\UserNotificationPublisher;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::withCount('submissions')
            ->latest()
            ->paginate(20);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $campaign = new Campaign([
            'is_active' => true,
            'field_names' => [''],
            'field_definitions' => [
                ['label' => '', 'type' => 'text'],
            ],
        ]);

        return view('admin.campaigns.create', compact('campaign'));
    }

    public function store(Request $request)
    {
        $campaign = Campaign::create($this->validatedData($request));
        $notificationTitle = trim((string) $request->input('notification_title')) ?: 'New campaign available';
        $notificationDescription = trim((string) $request->input('notification_description')) ?: 'A new campaign is now available for students.';

        UserNotificationPublisher::broadcastToUsers([
            'created_by_admin_id' => Auth::guard('admin')->id(),
            'type' => 'campaign',
            'title' => $notificationTitle,
            'description' => $notificationDescription,
            'url' => route('campaigns.show', $campaign),
            'icon' => 'fas fa-bullhorn',
        ]);

        User::query()
            ->where(function ($query) {
                $query->where('approved', true)->orWhereNull('approved');
            })
            ->whereNotNull('email')
            ->select('id', 'full_name', 'email')
            ->chunkById(100, function ($users) use ($campaign, $notificationTitle, $notificationDescription) {
                foreach ($users as $user) {
                    UserEmailService::sendCampaignAnnouncement($user, $campaign, $notificationTitle, $notificationDescription);
                }
            });

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign created successfully.');
    }

    public function edit(Campaign $campaign)
    {
        if (empty($campaign->field_definitions)) {
            $campaign->field_definitions = $campaign->normalizedFieldDefinitions();
        }

        if (empty($campaign->field_definitions)) {
            $campaign->field_definitions = [
                ['label' => '', 'type' => 'text'],
            ];
        }

        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $campaign->update($this->validatedData($request));

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign deleted successfully.');
    }

    protected function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'field_definitions' => 'required|array|min:1',
            'field_definitions.*.label' => 'required|string|max:255',
            'field_definitions.*.type' => 'required|in:text,checkbox',
            'is_active' => 'nullable|boolean',
        ]);

        $fieldDefinitions = collect($validated['field_definitions'])
            ->map(function ($definition) {
                return [
                    'label' => trim((string) ($definition['label'] ?? '')),
                    'type' => ($definition['type'] ?? 'text') === 'checkbox' ? 'checkbox' : 'text',
                ];
            })
            ->filter(fn ($definition) => $definition['label'] !== '')
            ->values()
            ->all();

        if ($fieldDefinitions === []) {
            throw ValidationException::withMessages([
                'field_definitions' => 'Add at least one field.',
            ]);
        }

        return [
            'title' => $validated['title'],
            'field_names' => collect($fieldDefinitions)->pluck('label')->all(),
            'field_definitions' => $fieldDefinitions,
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
