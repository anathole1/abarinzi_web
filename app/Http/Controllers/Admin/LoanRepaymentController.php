<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\User; // For filtering
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class LoanRepaymentController extends Controller
{
    public function __construct()
    {
        // Add appropriate permissions, e.g., 'manage loan repayments'
        // $this->middleware('permission:manage loan repayments');
    }

    /**
     * Display a listing of the loan repayments.
     */
    public function index(Request $request)
    {
        $query = LoanRepayment::with(['user', 'loan.user', 'confirmer']) // Eager load relationships
                               ->orderBy('payment_date', 'desc')
                               ->orderBy('created_at', 'desc');

        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }
        if ($request->filled('user_filter')) {
            $query->where('user_id', $request->user_filter);
        }
        if ($request->filled('loan_id_filter')) {
            $query->where('loan_id', $request->loan_id_filter);
        }

        $repayments = $query->paginate(20)->appends($request->query());
        $users = User::whereHas('roles', fn($q) => $q->where('name', 'member'))
                     ->orderBy('name')->get(); // For user filter dropdown
        $loans = Loan::whereIn('status', ['active', 'defaulted', 'repaid']) // Only show loans that might have repayments
                     ->with('user')
                     ->get(); // For loan filter dropdown

        return view('admin.loan_repayments.index', compact('repayments', 'users', 'loans'));
    }

    /**
     * Display the specified loan repayment.
     */
    public function show(LoanRepayment $loanRepayment) // Route model binding
    {
        $loanRepayment->load(['user', 'loan.user', 'confirmer']);
        return view('admin.loan_repayments.show', compact('loanRepayment'));
    }

    /**
     * Confirm a loan repayment.
     */
    public function confirm(Request $request, LoanRepayment $loanRepayment)
    {
        if ($loanRepayment->status !== 'pending_confirmation') {
            return redirect()->route('admin.loan-repayments.show', $loanRepayment)
                             ->with('error', 'This repayment is not pending confirmation.');
        }

        // Start a database transaction
        DB::beginTransaction();
        try {
            $loanRepayment->status = 'confirmed';
            $loanRepayment->confirmed_by_user_id = Auth::id();
            $loanRepayment->confirmation_date = now();
            $loanRepayment->save();

            // Update the parent loan's balance and status
            $loan = $loanRepayment->loan;
            $loan->updateBalanceAndStatusAfterRepayment($loanRepayment->amount_paid); // We'll add this method to Loan model

            DB::commit();
            return redirect()->route('admin.loan-repayments.show', $loanRepayment)
                             ->with('success', 'Repayment confirmed successfully. Loan balance updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error confirming loan repayment: ' . $e->getMessage());
            return redirect()->route('admin.loan-repayments.show', $loanRepayment)
                             ->with('error', 'An error occurred while confirming the repayment. Please try again.');
        }
    }

    /**
     * Mark a loan repayment as failed.
     */
    public function fail(Request $request, LoanRepayment $loanRepayment)
    {
        if ($loanRepayment->status !== 'pending_confirmation') {
            return redirect()->route('admin.loan-repayments.show', $loanRepayment)
                             ->with('error', 'This repayment is not pending confirmation.');
        }

        $request->validate(['admin_notes' => 'required|string|max:500']); // Require a reason for failure

        $loanRepayment->status = 'failed';
        $loanRepayment->confirmed_by_user_id = Auth::id(); // User who marked as failed
        $loanRepayment->confirmation_date = now(); // Date of this action
        $loanRepayment->notes = ($loanRepayment->notes ? $loanRepayment->notes . "\n" : '') . "Failed Reason: " . $request->admin_notes;
        $loanRepayment->save();

        return redirect()->route('admin.loan-repayments.show', $loanRepayment)
                         ->with('success', 'Repayment marked as failed.');
    }

    // Optional: Admin might also need to edit/delete repayment records (with care)
    // public function edit(LoanRepayment $loanRepayment) { ... }
    // public function update(Request $request, LoanRepayment $loanRepayment) { ... }
    // public function destroy(LoanRepayment $loanRepayment) { ... }
}