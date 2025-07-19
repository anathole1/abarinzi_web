<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Models\User; // For dropdowns
use App\Models\MemberCategory; 
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
        // Define the variables that the _form.blade.php partial expects.
        // On the create page, these will be null/empty, as no user is selected yet.
        $selectedUserOption = null;
        $memberCategoryAmounts = []; // Pass an empty array

        return view('admin.contributions.create', compact('selectedUserOption', 'memberCategoryAmounts'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|in:monthly_membership,social_contribution,other',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:100',
            'transaction_id' => 'nullable|string|max:255|unique:contributions,transaction_id',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
            'payment_date' => 'nullable|date',
        ]);

        $contributionData = $validatedData;
        if (in_array($validatedData['status'], ['approved', 'rejected'])) {
            $contributionData['approved_by'] = Auth::id();
        }

        Contribution::create($contributionData);
        return redirect()->route('admin.contributions.index')->with('success', 'Contribution created successfully.');
    }

    public function show(Contribution $contribution)
    {
        $contribution->load('user', 'approver');
        return view('admin.contributions.show', compact('contribution'));
    }

    public function edit(Contribution $contribution)
    {
        $contribution->load('user.memberProfile.memberCategory');

        $selectedUserOption = null;
        if ($contribution->user) {
            $selectedUserOption = [
                'id' => $contribution->user->id,
                'text' => "{$contribution->user->name} ({$contribution->user->email})"
            ];
        }

        $memberCategoryAmounts = [];
        if ($contribution->user?->memberProfile?->memberCategory) { // Using nullsafe operator for robustness
            $memberCategoryAmounts[$contribution->user->id] = [
                'monthly' => $contribution->user->memberProfile->memberCategory->monthly_contribution,
                'social' => $contribution->user->memberProfile->memberCategory->social_monthly_contribution,
            ];
        }

        return view('admin.contributions.edit', compact('contribution', 'selectedUserOption', 'memberCategoryAmounts'));
    }
    public function update(Request $request, Contribution $contribution)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:100',
            'transaction_id' => 'nullable|string|max:255|unique:contributions,transaction_id,' . $contribution->id,
            'description' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
            'payment_date' => 'nullable|date',
        ]);

        $contributionData = $validatedData;
        // If status is changed and it's an approval/rejection action
        if ($contribution->isDirty('status') && in_array($validatedData['status'], ['approved', 'rejected'])) {
            $contributionData['approved_by'] = Auth::id();
        } elseif ($validatedData['status'] === 'pending') {
            $contributionData['approved_by'] = null;
        }

        $contribution->update($contributionData);
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