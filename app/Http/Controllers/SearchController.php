<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;

class SearchController extends Controller
{

    public function showSearchForm()
    {
        return view('admin.users.search');
    }

    // Perform the search based on selected criteria
    public function search(Request $request)
    {
      
        $criteria = $request->search_criteria;
        $searchTerm = $request->$criteria; 
    
        $query = User::query();
    
        // Perform search only if criteria and search term are provided
        if ($criteria && $searchTerm) {
            $query->where($request->search_criteria, 'LIKE', '%' . $searchTerm . '%');
        }
    
        $users = $query->orderBy('room_number')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.search_results', compact('users'));
    }
}
