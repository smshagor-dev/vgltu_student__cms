<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserCustomField;
use App\Models\UserFieldData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CustomField;



class FormSubmissionController extends Controller
{
    
    public function index()
    {
        // Get custom fields with their options and submission counts (distinct users per field)
        $customFieldData = DB::table('user_custom_fields as ucf')
            ->leftJoin('user_field_data as ufd', 'ucf.id', '=', 'ufd.field_id')
            ->select(
                'ucf.id as field_id',
                'ucf.field_label',
                'ucf.field_type',
                'ucf.target_audience',
                DB::raw('COUNT(DISTINCT ufd.user_id) as submission_count')
            )
            ->groupBy('ucf.id', 'ucf.field_label', 'ucf.field_type', 'ucf.target_audience')
            ->paginate(20);
    
        // Fetch all field options for each field_id
        $fieldOptions = DB::table('user_custom_field_options')->get()->groupBy('user_custom_field_id');
    
        // Get all user field data
        $userFieldData = DB::table('user_field_data as ufd')
            ->leftJoin('users as u', 'ufd.user_id', '=', 'u.id')
            ->select(
                'ufd.id',
                'ufd.user_id',
                'ufd.field_id',
                'ufd.value_id',
                'ufd.value',
                'ufd.description',
                'ufd.status',
                'u.room_number',
                'u.full_name'
            )
            ->get(); // Collection of user field data
    
        // Process each field data and associate options with submission counts and distinct users
        $customFieldData->getCollection()->transform(function ($field) use ($userFieldData, $fieldOptions) {
            $options = $fieldOptions->get($field->field_id, collect())->pluck('option_value')->toArray();
            $optionCounts = [];
            $optionUsers = [];
    
            // Loop over each option and count distinct users for that option
            foreach ($options as $option) {
                // Get distinct users who selected this option
                $distinctUsers = DB::table('user_field_data')
                    ->where('field_id', $field->field_id)
                    ->whereIn('value_id', function ($query) use ($field) {
                        $query->select('id')
                            ->from('user_custom_field_options')
                            ->where('user_custom_field_id', $field->field_id);
                    })
                    ->where('value', 'LIKE', "%{$option}%") // Assuming value is a plain text or comma-separated
                    ->distinct()
                    ->pluck('user_id'); // Get distinct user IDs who selected this option
    
                // Store the count of distinct users for this option
                $optionCounts[$option] = $distinctUsers->count(); // Count of distinct users
                $optionUsers[$option] = $distinctUsers; // Store distinct user IDs
            }
    
            return (object) [
                'field_id' => $field->field_id,
                'field_label' => $field->field_label,
                'field_type' => $field->field_type,
                'target_audience' => $field->target_audience,
                'options' => $options,
                'option_counts' => $optionCounts,
                'option_users' => $optionUsers,
                'submission_count' => $field->submission_count, // This is still the total distinct user count for the field
            ];
        });
    
        // Pass the data to the view
        return view('admin.form-submissions.index', compact('customFieldData', 'userFieldData'));
    }





    public function viewOptionUsers($field_id, $option)
    {
        // Fetch the field data from the user_custom_fields table
        $field = DB::table('user_custom_fields')
            ->where('id', $field_id)
            ->first();
        
        // Check if the field exists
        if (!$field) {
            return redirect()->back()->with('error', 'Field not found');
        }
    
        // Fetch the user data along with their field values
        $userFieldData = DB::table('user_field_data as ufd')
            ->join('users as u', 'ufd.user_id', '=', 'u.id')
            ->join('user_custom_fields as ucf', 'ufd.field_id', '=', 'ucf.id')
            ->join('user_custom_field_options as uco', 'ufd.value_id', '=', 'uco.id')
            ->select(
                'ufd.id', 
                'ufd.user_id', 
                'ufd.field_id', 
                'ufd.value', 
                'ufd.description', 
                'ufd.status', 
                'u.room_number', 
                'u.full_name', 
                'ucf.field_label', 
                'ucf.field_type', 
                'uco.option_value'
            )
            ->where('ufd.field_id', $field_id)
            ->where('uco.option_value', 'like', "%$option%")
            ->paginate(20);
    
        // Fetch distinct options related to the field from the user_custom_field_options table
        $options = DB::table('user_custom_field_options')
            ->where('user_custom_field_id', $field_id)  // Link to the correct field
            ->distinct()
            ->get(['option_value']);  // Select only option_value
    
        // Pass data to the view
        return view('admin.form-submissions.view_option', compact('field', 'userFieldData', 'options'));
    }

    
    // Change the status of the user's submitted data
    public function changeStatus(Request $request, $userId, $valueId)
    {
        \Log::info("Change status called for user: $userId, value: $valueId");
        $fieldData = UserFieldData::where('user_id', $userId)
            ->where('id', $valueId)
            ->firstOrFail();
    
        $status = $request->input('status');
    
        if (!in_array($status, ['pending', 'solved'])) {
            return response()->json(['error' => 'Invalid status'], 400);
        }
    
        $fieldData->status = $status;
        $fieldData->save();
    
        return response()->json(['success' => true, 'status' => $status]);
    }
    
    public function destroyFieldData($userId, $valueId)
    {
        $fieldData = UserFieldData::where('user_id', $userId)->where('id', $valueId)->firstOrFail();
    
        $fieldData->delete();
    
        return response()->json(['success' => true]);
    }


    
    public function viewUsers($fieldId)
    {
        // Retrieve all submissions for the given field, including related user and field data
        $users = User::whereHas('fieldData', function ($query) use ($fieldId) {
                $query->where('field_id', $fieldId);
            })
            ->with(['fieldData' => function ($query) use ($fieldId) {
                $query->where('field_id', $fieldId)->with('customField');
            }])
            ->orderBy('full_name')
            ->paginate(20);

        return view('admin.form-submissions.view-users', compact('users'));
    }



}
