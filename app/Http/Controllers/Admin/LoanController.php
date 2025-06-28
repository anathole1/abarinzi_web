<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\User; // For user dropdowns
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // To get current admin ID

class LoanController extends Controller
{
    public function __construct()
    {
        // Add permissions as needed, e.g., 'manage loans'
        //$this->middleware('permission:manage loans');
    }

    public function index(Request $request)
    {
        $query = Loan::with('user')->orderBy('application_date', 'desc');

        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }
        if ($request->filled('user_filter')) {
            $query->where('user_id', $request->user_filter);
        }

        $loans = $query->paginate(15)->appends($request->query());
        $users = User::whereHas('roles', fn($q) => $q->where('name', 'member'))->orderBy('name')->get(); // For filter dropdown

        return view('admin.loans.index', compact('loans', 'users'));
    }

    public function create()
    {
        $members = User::whereHas('memberProfile', fn($q) => $q->where('status', 'approved'))
                       ->orderBy('name')->get();
        // For preview on create form, pass an empty loan object
        $loan = new Loan(); // For the accessor to work if you display it
        return view('admin.loans.create', compact('members', 'loan'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount_requested' => 'required|numeric|min:0',
            'purpose' => 'required|string',
            'application_date' => 'required|date',
            'amount_approved' => 'nullable|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'term_months' => 'nullable|integer|min:1',
            'status' => 'required|in:pending,approved,rejected,active,repaid,defaulted',
            'approval_date' => 'nullable|date|after_or_equal:application_date',
            'disbursement_date' => 'nullable|date|after_or_equal:approval_date',
            'first_payment_due_date' => 'nullable|date|after_or_equal:disbursement_date',
            'final_due_date' => 'nullable|date|after_or_equal:first_payment_due_date',
            'admin_notes' => 'nullable|string',
        ]);

        if (in_array($validatedData['status'], ['approved', 'active'])) {
            $validatedData['approved_by_user_id'] = Auth::id();
            // Ensure approval date is set if status is approved/active and not already provided
            if (empty($validatedData['approval_date'])) {
                $validatedData['approval_date'] = now()->toDateString();
            }
        }

        // Loan::create($validatedData);
        $loan = new Loan($validatedData);
        $loan->calculateAndSetTotalRepayment(); // Calculate and set before saving
        $loan->save();

        return redirect()->route('admin.loans.index')->with('success', 'Loan application recorded successfully.');
    }

    public function show(Loan $loan)
    {
        $loan->load('user', 'approver');
        return view('admin.loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        $members = User::whereHas('memberProfile', fn($q) => $q->where('status', 'approved'))
                       ->orderBy('name')->get();
        $loan->load('user');
        return view('admin.loans.edit', compact('loan', 'members'));
    }

    public function update(Request $request, Loan $loan)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id', // Usually not changed after creation, but included for completeness
            'amount_requested' => 'required|numeric|min:0',
            'purpose' => 'required|string',
            'application_date' => 'required|date',
            'amount_approved' => 'nullable|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'term_months' => 'nullable|integer|min:1',
            'status' => 'required|in:pending,approved,rejected,active,repaid,defaulted',
            'approval_date' => 'nullable|date|after_or_equal:application_date',
            'disbursement_date' => 'nullable|date|after_or_equal:approval_date',
            'first_payment_due_date' => 'nullable|date|after_or_equal:disbursement_date',
            'final_due_date' => 'nullable|date|after_or_equal:first_payment_due_date',
            'admin_notes' => 'nullable|string',
        ]);

        // If status changes to approved/active and approved_by is not set, set it.
        if (in_array($validatedData['status'], ['approved', 'active']) && is_null($loan->approved_by_user_id)) {
            $validatedData['approved_by_user_id'] = Auth::id();
            // Ensure approval date is set if status is approved/active and not already provided
            if (empty($validatedData['approval_date']) && empty($loan->approval_date)) {
                $validatedData['approval_date'] = now()->toDateString();
            }
        } elseif ($validatedData['status'] === 'pending' || $validatedData['status'] === 'rejected') {
             // Clear approval info if moved back to pending/rejected
            $validatedData['approved_by_user_id'] = null;
            $validatedData['approval_date'] = null;
            $validatedData['disbursement_date'] = null; // also clear disbursement?
            $validatedData['total_repayment_calculated'] = null;
        }


        // $loan->update($validatedData);
        $loan->fill($validatedData);
        // Recalculate if relevant fields changed or status is now one where it should be calculated
        if ($loan->isDirty('amount_approved') || $loan->isDirty('interest_rate') || $loan->isDirty('term_months') ||
            in_array($loan->status, ['approved', 'active', 'repaid'])) { // Recalculate if repaid too for record
            $loan->calculateAndSetTotalRepayment();
        }
        $loan->save();

        return redirect()->route('admin.loans.index')->with('success', 'Loan information updated successfully.');
    }

    public function destroy(Loan $loan)
    {
        // Add any checks here, e.g., cannot delete an 'active' loan
        if (in_array($loan->status, ['active', 'repaid'])) {
             return redirect()->route('admin.loans.index')->with('error', 'Cannot delete an active or repaid loan record.');
        }
        $loan->delete();
        return redirect()->route('admin.loans.index')->with('success', 'Loan record deleted successfully.');
    }
}