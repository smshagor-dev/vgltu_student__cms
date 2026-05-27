<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider; 
use App\Support\ImageCompressor;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::query()
            ->latest()
            ->paginate(20);
        return view('admin.slider.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.slider.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = ImageCompressor::storeInPublicPath($request->image, 'images/sliders', 260, 1920);

        Slider::create([
            'title' => $request->title,
            'image' => $imageName,
        ]);

        return redirect()->route('sliders.index')
                        ->with('success', 'Slider created successfully.');
    }

    public function edit(Slider $slider)
    {
        return view('admin.slider.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = ImageCompressor::storeInPublicPath($request->image, 'images/sliders', 260, 1920);
            $slider->image = $imageName;
        }

        $slider->title = $request->title;
        $slider->save();

        return redirect()->route('sliders.index')
                        ->with('success', 'Slider updated successfully.');
    }

    public function destroy(Slider $slider)
    {
        $slider->delete();
        return redirect()->route('sliders.index')
                        ->with('success', 'Slider deleted successfully.');
    }
}
