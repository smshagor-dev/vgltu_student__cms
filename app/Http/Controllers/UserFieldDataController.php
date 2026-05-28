<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use App\Models\User;
use App\Models\UserCustomFieldOption;
use App\Models\UserFieldData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserFieldDataController extends Controller
{
    private function getRequestedField(Request $request): ?CustomField
    {
        $fieldId = $request->input('submitted_field_id');

        if (!$fieldId) {
            return null;
        }

        return CustomField::with('options')->findOrFail($fieldId);
    }

    private function getSubmissionFields(Request $request)
    {
        $requestedField = $this->getRequestedField($request);

        if ($requestedField) {
            return collect([$requestedField]);
        }

        return CustomField::with('options')->get();
    }

    private function storeFieldSubmission(CustomField $field, Request $request, int $userId, User $user, bool $allowExisting = false): bool
    {
        $fieldKey = 'field_' . $field->id;

        if ($field->field_type === 'image') {
            $hasSubmission = $request->hasFile($fieldKey);
        } else {
            $hasSubmission = $request->filled($fieldKey) || $request->has($fieldKey);
        }

        if (!$hasSubmission) {
            return false;
        }

        if (!$allowExisting) {
            $alreadyFilled = UserFieldData::where('user_id', $userId)
                ->where('field_id', $field->id)
                ->exists();

            if ($alreadyFilled) {
                return false;
            }
        }

        if ($field->target_audience === 'room' && !$allowExisting) {
            $roomAlreadyFilled = UserFieldData::where('field_id', $field->id)
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('room_number', $user->room_number);
                })
                ->exists();

            if ($roomAlreadyFilled) {
                return false;
            }
        }

        if ($field->field_type === 'image') {
            $request->validate([
                $fieldKey => 'required|file|mimes:jpeg,png,jpg|max:10240',
            ]);

            UserFieldData::create([
                'user_id' => $userId,
                'field_id' => $field->id,
                'value_id' => null,
                'value' => $request->file($fieldKey)->store('public/uploads'),
                'description' => null,
                'status' => 'pending',
                'updated_by' => $userId,
            ]);

            return true;
        }

        if ($field->field_type === 'text') {
            $request->validate([
                $fieldKey => 'required|string',
            ]);

            $value = $request->input($fieldKey);
            $customFieldOption = UserCustomFieldOption::where('option_value', $value)->first();
            $valueId = $customFieldOption ? $customFieldOption->id : null;

            $alreadyExists = UserFieldData::where('user_id', $userId)
                ->where('field_id', $field->id)
                ->where('value_id', $valueId)
                ->exists();

            if ($alreadyExists) {
                return false;
            }

            UserFieldData::create([
                'user_id' => $userId,
                'field_id' => $field->id,
                'value_id' => $valueId,
                'value' => $value,
                'description' => null,
                'status' => 'pending',
                'updated_by' => $userId,
            ]);

            return true;
        }

        if ($field->field_type === 'multiple_choice') {
            $request->validate([
                $fieldKey => 'required|array|min:1',
            ]);

            $selectedOptions = $request->input($fieldKey, []);
            $descriptions = $request->input('description_' . $field->id, []);
            $stored = false;

            foreach ($selectedOptions as $optionValue) {
                $customFieldOption = UserCustomFieldOption::where('option_value', $optionValue)
                    ->where('user_custom_field_id', $field->id)
                    ->first();

                if (!$customFieldOption) {
                    continue;
                }

                $alreadyExists = UserFieldData::where('user_id', $userId)
                    ->where('field_id', $field->id)
                    ->where('value_id', $customFieldOption->id)
                    ->exists();

                if ($alreadyExists) {
                    continue;
                }

                UserFieldData::create([
                    'user_id' => $userId,
                    'field_id' => $field->id,
                    'value_id' => $customFieldOption->id,
                    'value' => $optionValue,
                    'description' => $descriptions[$optionValue] ?? null,
                    'status' => 'pending',
                    'updated_by' => $userId,
                ]);

                $stored = true;
            }

            return $stored;
        }

        return false;
    }

    public function create()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $roomNumber = $user->room_number;

        $fields = CustomField::with('options')->get();
        $filledFieldIds = UserFieldData::where('user_id', $userId)->pluck('field_id')->toArray();
        $roomFilledFieldIds = UserFieldData::whereHas('user', function ($query) use ($roomNumber) {
                $query->where('room_number', $roomNumber);
            })
            ->pluck('field_id')
            ->unique()
            ->toArray();

        $submittedData = UserFieldData::whereHas('user', function ($query) use ($roomNumber) {
            $query->where('room_number', $roomNumber);
        })->get();

        $unfilledFields = $fields->filter(function ($field) use ($filledFieldIds, $roomFilledFieldIds) {
            $isOwnFieldFilled = in_array($field->id, $filledFieldIds);
            $isRoomFieldFilled = $field->target_audience === 'room' && in_array($field->id, $roomFilledFieldIds);

            return !$isOwnFieldFilled && !$isRoomFieldFilled;
        });

        if ($unfilledFields->isEmpty()) {
            return view('user.custom_fields.already_submitted', compact('submittedData'));
        }

        return view('user.custom_fields.create', compact('unfilledFields', 'submittedData'));
    }

    public function store(Request $request)
    {
        $userId = Auth::id();
        $user = User::findOrFail($userId);
        $fields = $this->getSubmissionFields($request);
        $stored = false;

        foreach ($fields as $field) {
            $stored = $this->storeFieldSubmission($field, $request, $userId, $user) || $stored;
        }

        return redirect()->back()->with('success', $stored ? 'Data submitted successfully!' : 'No new data was submitted.');
    }

    public function existingCreate()
    {
        $fields = CustomField::with('options')->get();
        $userId = Auth::id();
        $filledFieldIds = UserFieldData::where('user_id', $userId)->pluck('field_id')->toArray();
        $filledValues = UserFieldData::where('user_id', $userId)->get()->groupBy('field_id');
        $filledDescriptions = UserFieldData::where('user_id', $userId)
            ->whereNotNull('description')
            ->pluck('description', 'value_id')
            ->toArray();
        $studentFields = CustomField::where('target_audience', 'student')->get();
        $roomFields = CustomField::where('target_audience', 'room')->get();
        $submittedData = UserFieldData::whereHas('user', function ($query) {
            $query->where('room_number', Auth::user()->room_number);
        })->get();

        return view('user.custom_fields.existing_create', compact('fields', 'filledFieldIds', 'filledValues', 'filledDescriptions', 'studentFields', 'roomFields', 'submittedData'));
    }

    public function existingStore(Request $request)
    {
        Log::info('existingStore method called', $request->all());

        $userId = Auth::id();
        $user = Auth::user();
        $fields = $this->getSubmissionFields($request);
        $stored = false;

        foreach ($fields as $field) {
            $stored = $this->storeFieldSubmission($field, $request, $userId, $user, true) || $stored;
        }

        return redirect()->back()->with('success', $stored ? 'Data submitted successfully!' : 'No new data was submitted.');
    }

    public function index()
    {
        $userId = Auth::id();

        $submittedData = UserFieldData::with('customField')
            ->where('user_id', $userId)
            ->get();

        foreach ($submittedData as $data) {
            if ($data->customField->field_type === 'multiple_choice' && !empty($data->value)) {
                $decodedValue = json_decode($data->value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data->value = $decodedValue;
                }
            }
        }

        return view('user.custom_fields.index', compact('submittedData'));
    }

    public function destroy($id)
    {
        $userId = Auth::id();

        $fieldData = UserFieldData::where('id', $id)->where('user_id', $userId)->first();

        if (!$fieldData) {
            return redirect()->back()->with('error', 'Field data not found or not authorized to delete');
        }

        $fieldData->delete();

        return redirect()->route('admin.user-custom-data.index')->with('success', 'Field data deleted successfully');
    }

    public function edit($id)
    {
        $userFieldData = UserFieldData::findOrFail($id);
        $options = UserCustomFieldOption::all();
        $allUserFieldData = UserFieldData::all();

        return view('userFieldData.edit', compact('userFieldData', 'options', 'allUserFieldData'));
    }

    public function update(Request $request, $id)
    {
        $userFieldData = UserFieldData::findOrFail($id);

        $request->validate([
            'field_value' => 'required',
            'description' => 'nullable|array',
        ]);

        $userFieldData->value = implode(',', $request->input('field_value', []));
        $userFieldData->description = implode('|', $request->input('description', []));
        $userFieldData->save();

        return redirect()->route('user.custom-fields.index')->with('success', 'Data Updated successfully!');
    }

    public function showUserFieldData($userId)
    {
        $submittedData = UserFieldData::where('user_id', $userId)
            ->with('customField')
            ->get();

        return view('user.custom_fields.index', compact('submittedData'));
    }

    public function showCustomFieldsData()
    {
    }
}
