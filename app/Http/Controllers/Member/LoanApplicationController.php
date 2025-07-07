<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\MemberCategory; // To get loan limits, interest
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Contribution;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

class LoanApplicationController extends Controller
{
    use AuthorizesRequests; 
    public function index()
    {
        $loans = Auth::user()->loans()->orderBy('application_date', 'desc')->paginate(10);
        return view('member.loans.index', compact('loans'));
    }

    public function create()
    {
        $user = Auth::user();
        $memberProfile = $user->memberProfile()->with('memberCategory')->first();

        if (!$memberProfile || !$memberProfile->memberCategory) {
            return redirect()->route('member.loans.index')
                ->with('error', 'Your membership category details are required to apply for a loan. Please contact support.');
        }

        $memberCategory = $memberProfile->memberCategory;
        $maxLoanPercentage = $memberCategory->percentage_of_loan_allowed; // e.g., 50.00 for 50%
        $defaultInterestRate = $memberCategory->monthly_interest_rate_loan;

        // Calculate actual eligible savings for loan
        // Sum of all 'approved' contributions that are NOT 'social_contribution'
        $memberEligibleSavingsForLoan = $user->contributions()
            ->where('status', 'approved')
            ->where('type', '!=', 'social_contribution') // Exclude social contributions
            ->sum('amount'); // Sum the 'amount' column

        // Calculate the loan limit based on the eligible savings and category percentage
        $loanLimit = 0; // Default to 0
        if ($memberEligibleSavingsForLoan > 0 && $maxLoanPercentage > 0) {
            $loanLimit = ($memberEligibleSavingsForLoan * $maxLoanPercentage) / 100;
        }

        return view('member.loans.create', compact('loanLimit', 'defaultInterestRate', 'memberCategory', 'memberEligibleSavingsForLoan'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $memberProfile = $user->memberProfile()->with('memberCategory')->first();

        if (!$memberProfile || !$memberProfile->memberCategory) {
            return redirect()->route('member.loans.index')
                ->with('error', 'Cannot process loan application without valid membership details.');
        }
        $memberCategory = $memberProfile->memberCategory;

        // Recalculate loan limit server-side for validation
        $memberEligibleSavingsForLoan = $user->contributions()
            ->where('status', 'approved')
            ->where('type', '!=', 'social_contribution')
            ->sum('amount');

        $maxAllowedAmount = 0;
        if ($memberEligibleSavingsForLoan > 0 && $memberCategory->percentage_of_loan_allowed > 0) {
            $maxAllowedAmount = ($memberEligibleSavingsForLoan * $memberCategory->percentage_of_loan_allowed) / 100;
        }
        // Ensure max allowed amount is at least a minimum loanable amount if you have one, or handle 0
        $minLoanAmount = 1000; // Example minimum loan amount

        $validatedData = $request->validate([
            // Ensure amount requested is not more than the calculated maxAllowedAmount
            // Also ensure it's not less than a minimum if applicable
            'amount_requested' => [
                'required',
                'numeric',
                "min:{$minLoanAmount}", // Example minimum
                function ($attribute, $value, $fail) use ($maxAllowedAmount) {
                    if ($maxAllowedAmount <= 0) {
                        $fail("You are currently not eligible for a loan based on your contributions.");
                    } elseif ($value > $maxAllowedAmount) {
                        $fail("The requested amount exceeds your current loan limit of RWF " . number_format($maxAllowedAmount, 0) . ".");
                    }
                },
            ],
            'purpose' => 'required|string|min:10',
            'term_months' => 'required|integer|min:3|max:60', // Example term limits
        ], [
            // Custom messages can still be used if needed for other rules
            // 'amount_requested.max' => "The requested amount exceeds your current loan limit...", // This specific message is now handled by the closure
        ]);

        // ... rest of your store logic (same as before)
        $loan = new Loan([
            'user_id' => $user->id,
            'amount_requested' => $validatedData['amount_requested'],
            'purpose' => $validatedData['purpose'],
            'term_months' => $validatedData['term_months'],
            'interest_rate' => $memberCategory->monthly_interest_rate_loan,
            'application_date' => now()->toDateString(),
            'status' => 'pending',
        ]);
        $loan->calculateAndSetTotalRepayment();
        $loan->save();

        return redirect()->route('member.loans.index')->with('success', 'Loan application submitted successfully. It will be reviewed soon.');
    }

    public function show(Loan $my_loan) // Route model binding, Laravel handles finding loan by ID
    {
        // Policy to ensure member can only view their own loan
        $this->authorize('view', $my_loan); // Assumes you have a LoanPolicy

        $my_loan->load('user'); // Load user if needed, though it's their own
        return view('member.loans.show', ['loan' => $my_loan]);
    }
}