<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Description;

class DescriptionController extends Controller
{
    public function index()
    {
        $descriptions = Description::query()
            ->latest()
            ->paginate(20);
        return view('admin.description.index', compact('descriptions'));
    }

    public function create()
    {
        return view('admin.description.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'description' => 'required|max:255',
        ]);

        Description::create([
            'type' => $request->type,
            'description' => $request->description,
        ]);

        return redirect()->route('description.index')->with('success', 'Description added successfully');
    }

    public function edit($id)
    {
        $description = Description::findOrFail($id);
        return view('admin.description.edit', compact('description'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required',
            'description' => 'required|max:255',
        ]);

        $description = Description::findOrFail($id);
        $description->update([
            'type' => $request->type,
            'description' => $request->description,
        ]);

        return redirect()->route('description.index')->with('success', 'Description updated successfully');
    }

    public function destroy($id)
    {
        $description = Description::findOrFail($id);
        $description->delete();

        return redirect()->route('description.index')->with('success', 'Description deleted successfully');
    }
}
