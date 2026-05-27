<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::where('user_id', Auth::id())->get();
        return view('complaints.index', compact('complaints'));
    }

    public function create()
    {
        return view('complaints.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Complaint::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'description' => $request->description,
        ]);

        return redirect()->route('complaints.index')->with('success', 'Complaint submitted successfully.');
    }

    public function show(Complaint $complaint)
    {
        return view('complaints.show', compact('complaint'));
    }

    public function adminIndex()
    {
        $complaints = Complaint::with('user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);
        return view('admin.complaints.index', compact('complaints'));
    }

    public function adminShow(Complaint $complaint)
    {
        return view('admin.complaints.show', compact('complaint'));
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved',
        ]);

        $complaint->update(['status' => $request->status]);

        if ($request->status == 'in_progress') {
            return redirect()->route('admin.complaints.inProgress');
        } elseif ($request->status == 'resolved') {
            return redirect()->route('admin.complaints.solved');
        }

        return redirect()->route('admin.complaints.index')->with('success', 'Complaint status updated.');
    }

    public function inProgress()
    {
        $complaints = Complaint::with('user')
            ->where('status', 'in_progress')
            ->latest()
            ->paginate(20);
        return view('admin.complaints.inProgress', compact('complaints'));
    }

    public function solved()
    {
        $complaints = Complaint::with('user')
            ->where('status', 'resolved')
            ->latest()
            ->paginate(20);
        return view('admin.complaints.solved', compact('complaints'));
    }



}
