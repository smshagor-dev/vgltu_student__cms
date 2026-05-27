<?php

namespace App\Http\Controllers;

use App\Models\CustomField; // Updated model name
use App\Models\UserFieldData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\UserCustomFieldOption;
use Illuminate\Support\Facades\Log;
use App\Models\User;





class UserFieldDataController extends Controller
{
   public function create()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $roomNumber = $user->room_number;
    
        // Fetch all custom fields with their options (eager loading)
        $fields = CustomField::with('options')->get();
    
        // Get already filled field IDs by the user
        $filledFieldIds = UserFieldData::where('user_id', $userId)->pluck('field_id')->toArray();
    
        // Fetch student-related fields (Each student can fill them)
        $studentFields = CustomField::where('target_audience', 'student')->get();
    
        // Fetch room-related fields
        $roomFields = CustomField::where('target_audience', 'room')->get();
        
        // Check if any user from the same room has already filled the room-related fields
        $roomFieldIds = $roomFields->pluck('id')->toArray();
        $isRoomFilled = UserFieldData::whereIn('field_id', $roomFieldIds)
            ->whereHas('user', function ($query) use ($roomNumber) {
                $query->where('room_number', $roomNumber);
            })->exists();
    
        // Fetch submitted data for the user's room
        $submittedData = UserFieldData::whereHas('user', function ($query) use ($roomNumber) {
            $query->where('room_number', $roomNumber);
        })->get();
    
        // Filter unfilled fields
        $unfilledFields = $fields->filter(function ($field) use ($filledFieldIds, $isRoomFilled) {
            return !in_array($field->id, $filledFieldIds) && !($field->target_audience === 'room' && $isRoomFilled);
        });
    
        // If no unfilled fields exist, show "already submitted" view
        if ($unfilledFields->isEmpty()) {
            return view('user.custom_fields.already_submitted', compact('submittedData'));
        }
    
        // Return create view with data
        return view('user.custom_fields.create', compact('unfilledFields', 'submittedData'));
    }



    public function store(Request $request)
{
    $userId = Auth::id();
    $user = User::find($userId); // Get user data (including room_number if applicable)
    
    // Fetch all custom fields with their options
    $fields = CustomField::with('options')->get();

    foreach ($fields as $field) {
        // Skip already filled values
        $alreadyFilled = UserFieldData::where('user_id', $userId)
            ->where('field_id', $field->id)
            ->exists();

        if ($alreadyFilled) {
            continue;
        }

        // Default values
        $value = null;
        $valueId = null;
        $description = null;

        if ($field->target_audience === 'student' || !$field->target_audience) {
            // Allow all users to submit for 'student' target audience without restriction
            if ($field->field_type === 'image') {
                $request->validate([
                    'field_' . $field->id => 'required|file|mimes:jpeg,png,jpg|max:10240',
                ]);

                $path = $request->file('field_' . $field->id)->store('public/uploads');
                $value = $path;
            } elseif ($field->field_type === 'text') {
                $request->validate([
                    'field_' . $field->id => 'required|string',
                ]);

                $value = $request->input('field_' . $field->id);
            } elseif ($field->field_type === 'multiple_choice') {
                $request->validate([
                    'field_' . $field->id => 'required|array|min:1',
                ]);

                $selectedOptions = $request->input('field_' . $field->id);
                $descriptions = $request->input('description_' . $field->id, []);

                foreach ($selectedOptions as $optionValue) {
                    // Find the corresponding option ID
                    $customFieldOption = UserCustomFieldOption::where('option_value', $optionValue)
                        ->where('user_custom_field_id', $field->id)
                        ->first();

                    if ($customFieldOption) {
                        // Check if the entry already exists for the user and value_id combination
                        $exists = UserFieldData::where('user_id', $userId)
                            ->where('field_id', $field->id)
                            ->where('value_id', $customFieldOption->id)
                            ->exists();

                        if (!$exists) {
                            UserFieldData::create([
                                'user_id' => $userId,
                                'field_id' => $field->id,
                                'value_id' => $customFieldOption->id, // Store the ID instead of text
                                'value' => $optionValue, // Store only the selected option text
                                'description' => $descriptions[$optionValue] ?? null,
                                'status' => 'pending',
                                'updated_by' => $userId,
                            ]);
                        }
                    }
                }
                continue; // Skip saving single-entry fields below
            }

            // If the field is not multiple choice, handle storing single value
            if ($value !== null) {
                // Find the corresponding option ID if applicable
                $customFieldOption = UserCustomFieldOption::where('option_value', $value)->first();
                $valueId = $customFieldOption ? $customFieldOption->id : null;

                // Check if the entry already exists for the user and value_id combination
                $exists = UserFieldData::where('user_id', $userId)
                    ->where('field_id', $field->id)
                    ->where('value_id', $valueId)
                    ->exists();

                if (!$exists) {
                    UserFieldData::create([
                        'user_id' => $userId,
                        'field_id' => $field->id,
                        'value_id' => $valueId, // Store ID if found
                        'value' => $value,
                        'description' => $description,
                        'status' => 'pending',
                        'updated_by' => $userId,
                    ]);
                }
            }
        } elseif ($field->target_audience === 'room') {
            // Check if any user in the same room has already submitted data
            $roomNumber = $user->room_number; // Get the user's room number
            $alreadySubmittedInRoom = UserFieldData::where('field_id', $field->id)
                ->whereHas('user', function ($query) use ($roomNumber) {
                    $query->where('room_number', $roomNumber);
                })
                ->exists();

            if (!$alreadySubmittedInRoom) {
                // Allow the user to submit if no one from the same room has submitted
                if ($field->field_type === 'image') {
                    $request->validate([
                        'field_' . $field->id => 'required|file|mimes:jpeg,png,jpg|max:10240',
                    ]);

                    $path = $request->file('field_' . $field->id)->store('public/uploads');
                    $value = $path;
                } elseif ($field->field_type === 'text') {
                    $request->validate([
                        'field_' . $field->id => 'required|string',
                    ]);

                    $value = $request->input('field_' . $field->id);
                } elseif ($field->field_type === 'multiple_choice') {
                    $request->validate([
                        'field_' . $field->id => 'required|array|min:1',
                    ]);

                    $selectedOptions = $request->input('field_' . $field->id);
                    $descriptions = $request->input('description_' . $field->id, []);

                    foreach ($selectedOptions as $optionValue) {
                        // Find the corresponding option ID
                        $customFieldOption = UserCustomFieldOption::where('option_value', $optionValue)
                            ->where('user_custom_field_id', $field->id)
                            ->first();

                        if ($customFieldOption) {
                            // Check if the entry already exists for the user and value_id combination
                            $exists = UserFieldData::where('user_id', $userId)
                                ->where('field_id', $field->id)
                                ->where('value_id', $customFieldOption->id)
                                ->exists();

                            if (!$exists) {
                                UserFieldData::create([
                                    'user_id' => $userId,
                                    'field_id' => $field->id,
                                    'value_id' => $customFieldOption->id, // Store the ID instead of text
                                    'value' => $optionValue, // Store only the selected option text
                                    'description' => $descriptions[$optionValue] ?? null,
                                    'status' => 'pending',
                                    'updated_by' => $userId,
                                ]);
                            }
                        }
                    }
                    continue; // Skip saving single-entry fields below
                }

                // If the field is not multiple choice, handle storing single value
                if ($value !== null) {
                    // Find the corresponding option ID if applicable
                    $customFieldOption = UserCustomFieldOption::where('option_value', $value)->first();
                    $valueId = $customFieldOption ? $customFieldOption->id : null;

                    // Check if the entry already exists for the user and value_id combination
                    $exists = UserFieldData::where('user_id', $userId)
                        ->where('field_id', $field->id)
                        ->where('value_id', $valueId)
                        ->exists();

                    if (!$exists) {
                        UserFieldData::create([
                            'user_id' => $userId,
                            'field_id' => $field->id,
                            'value_id' => $valueId, // Store ID if found
                            'value' => $value,
                            'description' => $description,
                            'status' => 'pending',
                            'updated_by' => $userId,
                        ]);
                    }
                }
            }
        }
    }

    return redirect()->route('user.custom-fields.index')->with('success', 'Data submitted successfully!');
}

    
    public function existingCreate()
    {
        // Fetch all custom fields with their options without any restrictions
        $fields = CustomField::with('options')->get();
    
        // Get the logged-in user's ID
        $userId = Auth::id();
    
        // Get already filled field IDs by the user
        $filledFieldIds = UserFieldData::where('user_id', $userId)->pluck('field_id')->toArray();
    
        // Fetch the filled values and descriptions for the user
        $filledValues = UserFieldData::where('user_id', $userId)->get()->groupBy('field_id');
        $filledDescriptions = UserFieldData::where('user_id', $userId)
                                           ->whereNotNull('description')
                                           ->pluck('description', 'value_id')
                                           ->toArray();
    
        // Fetch student-related fields
        $studentFields = CustomField::where('target_audience', 'student')->get();
    
        // Fetch room-related fields
        $roomFields = CustomField::where('target_audience', 'room')->get();
    
        // Fetch submitted data for the user's room
        $submittedData = UserFieldData::whereHas('user', function ($query) {
            $query->where('room_number', Auth::user()->room_number);
        })->get();
    
        // Pass data to the view
        return view('user.custom_fields.existing_create', compact('fields', 'filledFieldIds', 'filledValues', 'filledDescriptions', 'studentFields', 'roomFields', 'submittedData'));
    }



public function existingStore(Request $request)
{
    Log::info('existingStore method called', $request->all());

    $userId = Auth::id();
    $userRoomNumber = Auth::user()->room_number;

    $fields = CustomField::with('options')->get();

    foreach ($fields as $field) {
        $fieldKey = 'field_' . $field->id;

        if (!$request->has($fieldKey)) {
            Log::warning("Skipping field {$field->id}, no data found.");
            continue;
        }

        $value = null;
        $valueId = null;
        $description = null;

        if ($field->field_type === 'image') {
            if ($request->hasFile($fieldKey)) {
                $path = $request->file($fieldKey)->store('public/uploads');
                $value = $path;
            } else {
                Log::error("Image file not uploaded for field {$field->id}");
                continue;
            }
        } elseif ($field->field_type === 'text') {
            $value = $request->input($fieldKey);
        } elseif ($field->field_type === 'multiple_choice') {
            $selectedOptions = $request->input($fieldKey);
            $descriptions = $request->input('description_' . $field->id, []);

            foreach ($selectedOptions as $optionValue) {
                $customFieldOption = UserCustomFieldOption::where('option_value', $optionValue)
                    ->where('user_custom_field_id', $field->id)
                    ->first();

                if ($customFieldOption) {
                    // ✅ Check value_id instead of field_id
                    $alreadyFilledOption = UserFieldData::where('user_id', $userId)
                        ->where('value_id', $customFieldOption->id)
                        ->exists();

                    if ($alreadyFilledOption) {
                        Log::warning("Skipping option {$customFieldOption->id}, already filled for user {$userId}.");
                        continue;
                    }

                    Log::info("Storing multiple-choice field {$field->id} with option: {$optionValue}");
                    UserFieldData::create([
                        'user_id' => $userId,
                        'field_id' => $field->id,
                        'value_id' => $customFieldOption->id,
                        'value' => $optionValue,
                        'description' => $descriptions[$optionValue] ?? null,
                        'status' => 'pending',
                        'updated_by' => $userId,
                    ]);
                }
            }
            continue;
        }

        if ($value !== null) {
            $customFieldOption = UserCustomFieldOption::where('option_value', $value)->first();
            $valueId = $customFieldOption ? $customFieldOption->id : null;

            // ✅ Check value_id instead of field_id
            $alreadyFilled = UserFieldData::where('user_id', $userId)
                ->where('value_id', $valueId)
                ->exists();

            if ($alreadyFilled) {
                Log::warning("Skipping field {$field->id}, value_id {$valueId} already filled for user {$userId}.");
                continue;
            }

            Log::info("Storing field {$field->id} with value: {$value}");

            UserFieldData::create([
                'user_id' => $userId,
                'field_id' => $field->id,
                'value_id' => $valueId,
                'value' => $value,
                'description' => $description,
                'status' => 'pending',
                'updated_by' => $userId,
            ]);
        }
    }

    return redirect()->route('user.custom-fields.index')->with('success', 'Data submitted successfully!');
}






    // Display the submitted data
    public function index()
    {
        $userId = Auth::id();

        // Retrieve the custom fields and their submitted data for the authenticated user
        $submittedData = UserFieldData::with('customField')
            ->where('user_id', $userId)
            ->get();

        // Decode the JSON values for multiple-choice fields
         foreach ($submittedData as $data) {
        if ($data->customField->field_type === 'multiple_choice' && !empty($data->value)) {
            // Check if the value is a valid JSON string
            $decodedValue = json_decode($data->value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data->value = $decodedValue; // Update with the decoded value
            }
        }
    }

        // Pass the data to the view
        return view('user.custom_fields.index', compact('submittedData'));
    }

    // Delete specific user field data
    public function destroy($id)
    {
        $userId = Auth::id();

        // Find the data entry by ID and ensure it belongs to the authenticated user
        $fieldData = UserFieldData::where('id', $id)->where('user_id', $userId)->first();

        if (!$fieldData) {
            return redirect()->back()->with('error', 'Field data not found or not authorized to delete');
        }

        // Delete the field data
        $fieldData->delete();

        return redirect()->route('admin.user-custom-data.index')->with('success', 'Field data deleted successfully');
    }


    public function edit($id)
    {
        // Find the user field data by ID
        $userFieldData = UserFieldData::findOrFail($id);
        
        // Fetch all options from the user_custom_field_options table
        $options = UserCustomFieldOption::all();
        
        $allUserFieldData = UserFieldData::all();
        
        // Pass the data and options to the view
        return view('userFieldData.edit', compact('userFieldData', 'options', 'allUserFieldData'));
    }


    public function update(Request $request, $id)
    {
        // Find the user field data to update
        $userFieldData = UserFieldData::findOrFail($id);
    
        // Validate the input (you may want to adjust the validation rules)
        $request->validate([
            'field_value' => 'required',  // Adjust validation based on the field type
            'description' => 'nullable|array',
        ]);
    
        // Update the field value
        $userFieldData->value = implode(',', $request->input('field_value', [])); // Update multiple choices as comma-separated
        $userFieldData->description = implode('|', $request->input('description', [])); // Update descriptions as pipe-separated
    
        // Handle other fields (e.g., text, image) as needed here
    
        // Save the updated data
        $userFieldData->save();
    
        return redirect()->route('user.custom-fields.index')->with('success', 'Data Updated successfully!');

    }

        // Data show
        public function showUserFieldData($userId)
        {
            // Fetch the user field data with the custom fields
            $submittedData = UserFieldData::where('user_id', $userId)
                                        ->with('customField') // Load related custom fields
                                        ->get();

            return view('user.custom_fields.index', compact('submittedData'));
        }
        
        
        public function showCustomFieldsData()
        {
            
        }
        
        


    
}
