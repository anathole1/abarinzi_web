<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Models\User; // For dropdowns
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContributionController extends Controller
{
    public function index(Request $request)
    {
        $query = Contribution::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }
        if ($request->filled('user_filter')) {
            $query->where('user_id', $request->user_filter);
        }

        $contributions = $query->paginate(15);
        $users = User::whereHas('roles', fn($q) => $q->where('name', 'member'))->orderBy('name')->get(); // For filter

        return view('admin.contributions.index', compact('contributions', 'users'));
    }

    public function create()
    {
        $members = User::whereHas('roles', fn($q) => $q->where('name', 'member'))
                        ->whereHas('memberProfile', fn($q) => $q->where('status', 'approved'))
                        ->orderBy('name')->get();
        return view('admin.contributions.create', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:100',
            'transaction_id' => 'nullable|string|max:255|unique:contributions,transaction_id',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
            'payment_date' => 'nullable|date',
        ]);

        $contribution = new Contribution($request->all());
        $contribution->approved_by = ($request->status === 'approved' || $request->status === 'rejected') ? Auth::id() : null;
        $contribution->save();

        return redirect()->route('admin.contributions.index')->with('success', 'Contribution created successfully.');
    }

    public function show(Contribution $contribution)
    {
        $contribution->load('user', 'approver');
        return view('admin.contributions.show', compact('contribution'));
    }

    public function edit(Contribution $contribution)
    {
        $members = User::whereHas('roles', fn($q) => $q->where('name', 'member'))
                        ->whereHas('memberProfile', fn($q) => $q->where('status', 'approved'))
                        ->orderBy('name')->get();
        $contribution->load('user');
        return view('admin.contributions.edit', compact('contribution', 'members'));
    }

    public function update(Request $request, Contribution $contribution)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:100',
            'transaction_id' => 'nullable|string|max:255|unique:contributions,transaction_id,' . $contribution->id,
            'description' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
            'payment_date' => 'nullable|date',
        ]);

        $contribution->fill($request->all());
        if ($contribution->isDirty('status') && ($request->status === 'approved' || $request->status === 'rejected')) {
            $contribution->approved_by = Auth::id();
        } elseif ($request->status === 'pending') {
            $contribution->approved_by = null;
        }
        $contribution->save();

        return redirect()->route('admin.contributions.index')->with('success', 'Contribution updated successfully.');
    }

    public function destroy(Contribution $contribution)
    {
        $contribution->delete();
        return redirect()->route('admin.contributions.index')->with('success', 'Contribution deleted successfully.');
    }

    public function approve(Contribution $contribution)
    {
        if ($contribution->status === 'approved') {
             return redirect()->back()->with('info', 'Contribution is already approved.');
        }
        $contribution->status = 'approved';
        $contribution->approved_by = Auth::id();
        // Consider setting payment_date here if not already set and payment is confirmed
        // $contribution->payment_date = now();
        $contribution->save();

        // Optionally, notify the user
        // $contribution->user->notify(new ContributionApprovedNotification($contribution));

        return redirect()->route('admin.contributions.index')->with('success', 'Contribution approved.');
    }

    public function reject(Contribution $contribution)
    {
        if ($contribution->status === 'rejected') {
             return redirect()->back()->with('info', 'Contribution is already rejected.');
        }
        $contribution->status = 'rejected';
        $contribution->approved_by = Auth::id();
        $contribution->save();

        // Optionally, notify the user
        // $contribution->user->notify(new ContributionRejectedNotification($contribution));

        return redirect()->route('admin.contributions.index')->with('success', 'Contribution rejected.');
    }
}