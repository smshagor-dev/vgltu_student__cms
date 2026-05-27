<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class StudentByReligionController extends Controller
{
    public function index(Request $request)
    {
        // Fetch distinct countries and religions from the database
        $countries = User::distinct()->pluck('country');
        $religions = User::distinct()->pluck('religion');

        // Create the base query
        $query = User::query();

        // Apply filters if 'religion' or 'country' is present in the request
        if ($request->has('religion') && $request->religion != '') {
            $query->where('religion', $request->religion);
        }

        if ($request->has('country') && $request->country != '') {
            $query->where('country', $request->country);
        }

        // Fetch filtered users
        $users = $query->get();

        // Structure the data by block
        $structuredData = $this->structureByBlock($users);

        // Return the view with countries, religions, and structured data
        return view('admin.students_by_religion', compact('structuredData', 'countries', 'religions'));
    }


    public function showBlock(Request $request, $block)
    {
        // Get the filters for religion and country from the request
        $religion = $request->input('religion');
        $country = $request->input('country');

        // Start the query to fetch all users
        $query = User::query();

        // Apply the filters if they are present
        if ($religion) {
            $query->where('religion', $religion);
        }

        if ($country) {
            $query->where('country', $country);
        }

        // Fetch the filtered users
        $users = $query->get();

        // Structure the data by block
        $structuredData = $this->structureByBlock($users);

        // Check if the block exists
        if (!isset($structuredData[$block])) {
            abort(404, 'Block not found');
        }

        // Get the floors for the selected block
        $floors = $structuredData[$block];

        // Fetch unique religions and countries from the database for the dropdowns
        $religions = User::distinct()->pluck('religion');
        $countries = User::distinct()->pluck('country');

        // Return the view with block data, floors, filters, and dropdown data
        return view('admin.student_by_block', compact('block', 'floors', 'religion', 'country', 'religions', 'countries'));
    }


    private function structureByBlock($users)
    {
        $blocks = [
            '1st Block' => [],
            '2nd Block' => [],
            '3rd Block' => [],
            '4th Block' => []
        ];

        foreach ($users as $user) {
            $blockNumber = (int) substr($user->room_number, 0, 1);
            $floorNumber = (int) substr($user->room_number, 1, 1);

            $blockKey = match ($blockNumber) {
                1 => '1st Block',
                2 => '2nd Block',
                3 => '3rd Block',
                4 => '4th Block',
                default => 'Others'
            };

            $floorKey = match ($floorNumber) {
                1 => '1st Floor',
                2 => '2nd Floor',
                3 => '3rd Floor',
                4 => '4th Floor',
                5 => '5th Floor',
                6 => '6th Floor',
                7 => '7th Floor',
                8 => '8th Floor',
                9 => '9th Floor',
                default => 'Unknown Floor'
            };

            $blocks[$blockKey][$floorKey][$user->room_number][] = $user;
        }

        return $blocks;
    }
}
