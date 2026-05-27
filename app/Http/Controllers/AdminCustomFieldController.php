<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCustomField;
use App\Models\CustomField;
use App\Models\UserCustomFieldOption;
use App\Support\UserNotificationPublisher;
use Illuminate\Support\Facades\Auth;



class AdminCustomFieldController extends Controller
{
    public function index()
    {
        $fields = UserCustomField::query()
            ->with('options')
            ->latest()
            ->paginate(20);

        $options = UserCustomFieldOption::all();

        return view('admin.custom_fields.index', compact('fields', 'options'));
    }


    public function create()
    {
        return view('admin.custom_fields.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|string|in:text,image,multiple_choice',
            'options' => 'nullable|array',
            'target_audience' => 'required|string|in:room,student',
        ]);
    
        $customField = UserCustomField::create([
            'field_label' => $validated['field_label'],
            'field_type' => $validated['field_type'],
            'target_audience' => $validated['target_audience'],
        ]);
    
        // Store multiple choice options as separate rows
        if ($validated['field_type'] === 'multiple_choice' && !empty($validated['options'])) {
            foreach ($validated['options'] as $option) {
                UserCustomFieldOption::create([
                    'user_custom_field_id' => $customField->id,
                    'option_value' => $option,
                ]);
            }
        }

        UserNotificationPublisher::broadcastToUsers([
            'created_by_admin_id' => Auth::guard('admin')->id(),
            'type' => 'custom_field',
            'title' => trim((string) $request->input('notification_title')) ?: 'New student form available',
            'description' => trim((string) $request->input('notification_description')) ?: 'A new custom field form is ready for your submission.',
            'url' => route('user.custom-fields.create'),
            'icon' => 'fas fa-file-signature',
        ]);
    
        return redirect()->route('admin.custom-fields.index')->with('success', 'Field created successfully.');
    }





    // Show the edit form for the field
    public function edit($id)
    {
        // Find the custom field by ID
        $field = CustomField::findOrFail($id);
    
        // Fetch the associated options for this field
        $options = UserCustomFieldOption::where('user_custom_field_id', $field->id)->get();
    
        // Pass the field and options to the view
        return view('admin.custom_fields.edit', compact('field', 'options'));
    }




    // Update the custom field
   public function update(Request $request, $id)
    {
        $field = CustomField::findOrFail($id);
    
        // Validate input
        $request->validate([
            'field_label' => 'required|string|max:255',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
        ]);
    
        // Update only the field label
        $field->update([
            'field_label' => $request->input('field_label'),
        ]);
    
        // Add new options if provided
        if ($request->has('options')) {
            foreach ($request->input('options') as $optionValue) {
                UserCustomFieldOption::firstOrCreate([
                    'user_custom_field_id' => $field->id, // Fixed column name
                    'option_value' => $optionValue,
                ]);
            }
        }
    
        return redirect()->route('admin.custom-fields.index')->with('success', 'Field updated successfully');
    }








    public function destroy($id)
    {
        $field = UserCustomField::findOrFail($id);
        $field->delete();

        return redirect()->route('admin.custom-fields.index')->with('success', 'Field deleted successfully');
    }
}
