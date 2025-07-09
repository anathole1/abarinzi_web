<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanRepayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LoanRepaymentController extends Controller {
    use AuthorizesRequests;
    public function create(Loan $loan) { 
        // Member is making a repayment FOR a specific loan
        $this->authorize('makeRepayment', $loan); // New policy method
        // Pass loan details to the view if needed
        return view('member.loan_repayments.create', compact('loan'));
    }

    public function store(Request $request, Loan $loan) {
        $this->authorize('makeRepayment', $loan);
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|string|max:100',
            'transaction_id' => 'nullable|string|max:255|unique:loan_repayments,transaction_id',
            'notes' => 'nullable|string',
        ]);
        $repayment = new LoanRepayment([
            'user_id' => Auth::id(), // The user making the repayment
            // 'loan_id' is automatically set by the relationship when using $loan->repayments()->create()
            'amount_paid' => $validated['amount_paid'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'],
            'notes' => $validated['notes'],
            'status' => 'pending_confirmation', // All member-submitted repayments need admin confirmation
        ]);

        $loan->repayments()->save($repayment);

        return redirect()->route('member.loans.show', $loan)
                         ->with('success', 'Repayment submitted successfully. It will be reviewed and confirmed by an administrator.');
        // $loan->repayments()->create([
        //     'user_id' => Auth::id(),
        //     'amount_paid' => $validated['amount_paid'],
        //     'payment_date' => $validated['payment_date'],
        //     'payment_method' => $validated['payment_method'],
        //     'transaction_id' => $validated['transaction_id'],
        //     'notes' => $validated['notes'],
        //     'status' => 'pending_confirmation', // Admin needs to confirm
        // ]);
        // return redirect()->route('member.loans.show', $loan)->with('success', 'Repayment submitted for confirmation.');
    }
}