<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCustomFieldOption;

class CustomFieldOptionController extends Controller
{
    
    public function edit(UserCustomFieldOption $option) // Correct model name
    {
        return view('admin.custom_fields.edit_option', compact('option'));
    }

    public function update(Request $request, UserCustomFieldOption $option) // Correct model name
    {
        $request->validate([
            'option_value' => 'required|string|max:255',
        ]);

        $option->update([
            'option_value' => $request->option_value,
        ]);

        return redirect()->route('admin.custom-fields.index')->with('success', 'Option updated successfully');
    }

    public function destroy(UserCustomFieldOption $option) // Correct model name
    {
        $option->delete();

        return redirect()->route('admin.custom-fields.index')->with('success', 'Option deleted successfully');
    }
}
