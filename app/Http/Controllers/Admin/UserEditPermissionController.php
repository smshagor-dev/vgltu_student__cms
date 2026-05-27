<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use App\Support\UserProfileEditSettings;
use Illuminate\Http\Request;

class UserEditPermissionController extends Controller
{
    public function edit()
    {
        $settings = WebsiteSetting::query()->first() ?? new WebsiteSetting();
        $fieldDefinitions = UserProfileEditSettings::fields();
        $editableFields = UserProfileEditSettings::normalizeEditableFields($settings->user_editable_fields);

        return view('admin.user_edit_permissions.edit', compact('settings', 'fieldDefinitions', 'editableFields'));
    }

    public function update(Request $request)
    {
        $settings = WebsiteSetting::query()->first() ?? new WebsiteSetting();
        $editableFields = UserProfileEditSettings::normalizeEditableFields($request->input('user_editable_fields', []));

        $settings->fill([
            'user_editable_fields' => $editableFields,
        ])->save();

        return redirect()
            ->route('admin.user-edit-permissions.edit')
            ->with('success', 'User profile edit permissions updated successfully.');
    }
}
