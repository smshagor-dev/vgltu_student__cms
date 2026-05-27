<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserCustomField;
use App\Models\UserFieldData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminFieldDataController extends Controller
{
    // Display users and their submitted data
    public function index(Request $request)
    {
        $query = $request->input('query');

        // Search users by room number or get all users
        $users = User::with('fieldData.customField')
            ->when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where('room_number', 'LIKE', '%' . $query . '%');
            })
            ->orderBy('room_number')
            ->paginate(20)
            ->withQueryString();

        return view('admin.user_custom_data.index', compact('users'));
    }

    // Show the form to edit submitted data for a user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $submittedData = UserFieldData::with('customField')->where('user_id', $id)->get();
    
        return view('admin.user_custom_data.edit', compact('user', 'submittedData'));
    }
    

// Update the submitted data for a user
public function update(Request $request, $id)
{
    $userId = $id;
    $fields = UserCustomField::all();

    foreach ($fields as $field) {
        // Retrieve any existing data for the user and field
        $alreadyFilled = UserFieldData::where('user_id', $userId)
            ->where('field_id', $field->id)
            ->first();

        $fieldName = 'field_' . $field->id;

        // Handle image fields
        if ($field->field_type === 'image') {
            if ($request->hasFile($fieldName)) {
                $request->validate([
                    $fieldName => 'nullable|file|mimes:jpeg,png,jpg|max:10240',
                ]);

                // Delete the old image if it exists
                if ($alreadyFilled && $alreadyFilled->value) {
                    Storage::delete($alreadyFilled->value);
                }

                $path = $request->file($fieldName)->store('public/uploads');

                // Update or create the record
                if ($alreadyFilled) {
                    $alreadyFilled->update(['value' => $path]);
                } else {
                    UserFieldData::create([
                        'user_id' => $userId,
                        'field_id' => $field->id,
                        'value' => $path,
                    ]);
                }
            }
        }
        // Handle text fields
        elseif ($field->field_type === 'text') {
            $request->validate([
                $fieldName => 'nullable|string',
            ]);

            // Update or create the record
            $value = $request->input($fieldName);
            if ($alreadyFilled) {
                $alreadyFilled->update(['value' => $value]);
            } else {
                UserFieldData::create([
                    'user_id' => $userId,
                    'field_id' => $field->id,
                    'value' => $value,
                ]);
            }
        }
        // Handle multiple choice fields
        elseif ($field->field_type === 'multiple_choice' && $request->has($fieldName)) {
            $request->validate([
                $fieldName => 'nullable|array',
            ]);

            $selectedOptions = $request->input($fieldName);

            // Delete existing data for the field
            UserFieldData::where('user_id', $userId)
                ->where('field_id', $field->id)
                ->delete();

            // Save each selected option
            foreach ($selectedOptions as $option) {
                UserFieldData::create([
                    'user_id' => $userId,
                    'field_id' => $field->id,
                    'value' => $option,
                ]);
            }
        }
    }

    return redirect()->route('admin.user-custom-data.index')->with('success', 'Data updated successfully');
}

    // Delete a field data entry for a user
    public function destroyFieldData($userId, $fieldId)
    {
        // Find the field data to delete
        $fieldData = UserFieldData::where('user_id', $userId)
            ->where('field_id', $fieldId)
            ->firstOrFail();

        // If the field data is an image, delete the file from storage
        if ($fieldData->customField->field_type === 'image' && $fieldData->value) {
            \Storage::delete($fieldData->value);
        }

        // Delete the field data
        $fieldData->delete();

        return redirect()->route('admin.user-custom-data.index')->with('success', 'Field data deleted successfully.');
    }

    // Change the status of the user's submitted data
    public function changeStatus(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $status = $request->input('status');

        // Update the user's status
        $user->status = $status;
        $user->save();

        return response()->json(['success' => true, 'status' => $status]);
    }

    // AdminFieldDataController.php

    public function updateStatus(Request $request, $userId, $fieldId)
    {
        $fieldData = UserFieldData::where('user_id', $userId)
            ->where('field_id', $fieldId)
            ->firstOrFail();
    
        // Toggle the status between 'not solved' and 'solved'
        $fieldData->status = ($fieldData->status === 'not solved') ? 'solved' : 'not solved';
        $fieldData->save();
    
        // Redirect based on the new status
        if ($fieldData->status === 'solved') {
            return redirect()->route('admin.user-custom-data.index')->with('success', 'Data marked as solved.');
        } else {
            return redirect()->route('admin.user-custom-data.index')->with('success', 'Data marked as not solved.');
        }
    }


    public function solvedData()
    {
        $solvedData = UserFieldData::where('status', 'solved')
            ->with('user', 'customField')
            ->latest()
            ->paginate(20);

        return view('admin.user_custom_data.solved', compact('solvedData'));
    }




}
