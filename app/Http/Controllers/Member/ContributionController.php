<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Models\MemberProfile; // To get category details
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContributionController extends Controller
{    private function getContributionCategory(string $type): string
    {
        $type = strtolower(trim($type)); // Normalize for comparison

        // --- EXAMPLE MAPPING - CUSTOMIZE THIS ---
        $regularTypes = [
            'regular',
            'annual membership fee', // Example
            'monthly dues',          // Example
            'general support',       // Example
            'project contribution',  // Example
            // Add all your 'regular' contribution types here (lowercase)
        ];
        $socialTypes = [
            'social',
            'gala dinner ticket',        // Example
            'social event contribution', // Example
            'welfare fund',              // Example
            'end of year party',       // Example
            // Add all your 'social' contribution types here (lowercase)
        ];
        // --- END EXAMPLE MAPPING ---


        if (in_array($type, $regularTypes)) {
            return 'regular';
        } elseif (in_array($type, $socialTypes)) {
            return 'social';
        }
        return 'other'; // For types not fitting into regular/social
    }
    public function index(Request $request)
    {
        $user = Auth::user();
        // ... (your existing index logic for fetching and summarizing) ...
        // Make sure the $contributions passed to the view are fetched correctly.
        // The previous totalApprovedRegular/Social logic will need to be reviewed
        // if 'type' is no longer generic.

        $contributionsQuery = $user->contributions();
        if ($request->filled('status_filter') && in_array($request->status_filter, ['pending', 'approved', 'rejected'])) {
            $contributionsQuery->where('status', $request->status_filter);
        }
        $contributions = $contributionsQuery->clone()
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);

        // For summaries, you might need to adjust how you categorize if 'type' is now specific
        $allApprovedUserContributions = $user->contributions()->where('status', 'approved')->get();
        $totalApprovedRegular = 0; // This now specifically means 'monthly_membership'
        $totalApprovedSocial = 0;  // This now specifically means 'social_contribution'
        $totalApprovedOther = 0;

        foreach ($allApprovedUserContributions as $contribution) {
            if ($contribution->type === 'monthly_membership') {
                $totalApprovedRegular += $contribution->amount;
            } elseif ($contribution->type === 'social_contribution') {
                $totalApprovedSocial += $contribution->amount;
            } else { // 'other'
                $totalApprovedOther += $contribution->amount;
            }
        }
        $statusCounts = $user->contributions()
            ->groupBy('status')->select('status', DB::raw('count(*) as count'))->pluck('count', 'status');
        $pendingCount = $statusCounts->get('pending', 0);
        $approvedCount = $statusCounts->get('approved', 0);
        $rejectedCount = $statusCounts->get('rejected', 0);

        return view('member.contributions.index', compact(
            'contributions', // Use this for the table
            'totalApprovedRegular', 'totalApprovedSocial', 'totalApprovedOther',
            'pendingCount', 'approvedCount', 'rejectedCount'
        ));
    }


    public function create()
    {
        $user = Auth::user();
        // Ensure member profile is approved
        if (!$user->memberProfile || $user->memberProfile->status !== 'approved') {
            return redirect()->route('dashboard')->with('error', 'Your membership profile must be approved to make contributions.');
        }

        $memberCategory = $user->memberProfile->memberCategory; // Eager load if not already
        $defaultMonthlyAmount = $memberCategory ? $memberCategory->monthly_contribution : null;
        $defaultSocialAmount = $memberCategory ? $memberCategory->social_monthly_contribution : null;

        // Pass these to the view for JS auto-filling
        return view('member.contributions.create', compact('defaultMonthlyAmount', 'defaultSocialAmount'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->memberProfile || $user->memberProfile->status !== 'approved') {
            return redirect()->route('member.contributions.index')->with('error', 'Your membership profile must be approved.');
        }

        $validatedData = $request->validate([
            'type' => 'required|string|in:monthly_membership,social_contribution,other',
            'amount' => 'required_if:type,other|nullable|numeric|min:0.01', // Amount required only if type is 'other'
            'payment_method' => 'required|string|max:100',
            'transaction_id' => 'nullable|string|max:255|unique:contributions,transaction_id',
            'description' => 'nullable|string',
            'payment_date' => 'nullable|date',
        ]);

        $amountToStore = $validatedData['amount']; // Default to user input (for 'other' or 'social')

        if ($validatedData['type'] === 'monthly_membership') {
            $memberCategory = $user->memberProfile->memberCategory;
            if ($memberCategory) {
                $amountToStore = $memberCategory->monthly_contribution;
            } else {
                // Handle case where member category is somehow missing, though should be enforced by profile completion
                return back()->with('error', 'Your membership category is not set. Cannot process monthly contribution.')->withInput();
            }
        } elseif ($validatedData['type'] === 'social_contribution') {
            // If you want social contribution to also be fixed based on category:
            $memberCategory = $user->memberProfile->memberCategory;
            if ($memberCategory && $memberCategory->social_monthly_contribution !== null) {
                $amountToStore = $memberCategory->social_monthly_contribution;
            } else if (empty($amountToStore)) { // If social amount can be variable but wasn't provided
                 return back()->with('error', 'Please enter an amount for social contribution or select a category with a defined social contribution.')->withInput();
            }
            // If social_monthly_contribution is null on category, it means member enters amount
            // If amount is also empty, then it's an error for social
            if(empty($amountToStore)) {
                 return back()->withErrors(['amount' => 'Amount is required for social contributions if not defined by category.'])->withInput();
            }
        }


        $contribution = new Contribution([
            'user_id' => Auth::id(),
            'type' => $validatedData['type'],
            'amount' => $amountToStore,
            'payment_method' => $validatedData['payment_method'],
            'transaction_id' => $validatedData['transaction_id'],
            'description' => $validatedData['description'],
            'payment_date' => $validatedData['payment_date'],
            'status' => 'pending',
        ]);
        $contribution->save();

        return redirect()->route('member.contributions.index')
                         ->with('success', 'Contribution submitted successfully! It is now pending approval.');
    }


    // public function index(Request $request)
    // {
    //     $user = Auth::user();
    //     $contributionsQuery = $user->contributions();

    //     if ($request->filled('status_filter') && in_array($request->status_filter, ['pending', 'approved', 'rejected'])) {
    //         $contributionsQuery->where('status', $request->status_filter);
    //     }

    //     // CHANGE THIS LINE BACK:
    //     $contributions = $contributionsQuery->clone() // Use $contributions
    //                             ->orderBy('created_at', 'desc')
    //                             ->paginate(10);

    //     // For summaries
    //     $allApprovedUserContributions = $user->contributions()->where('status', 'approved')->get();
    //     $totalApprovedRegular = 0;
    //     $totalApprovedSocial = 0;
    //     $totalApprovedOther = 0;

    //     foreach ($allApprovedUserContributions as $c) { // Renamed loop var to avoid conflict
    //         if ($c->type === 'monthly_membership') {
    //             $totalApprovedRegular += $c->amount;
    //         } elseif ($c->type === 'social_contribution') {
    //             $totalApprovedSocial += $c->amount;
    //         } else { // 'other'
    //             $totalApprovedOther += $c->amount;
    //         }
    //     }
    //     $statusCounts = $user->contributions()
    //         ->groupBy('status')->select('status', DB::raw('count(*) as count'))->pluck('count', 'status');
    //     $pendingCount = $statusCounts->get('pending', 0);
    //     $approvedCount = $statusCounts->get('approved', 0);
    //     $rejectedCount = $statusCounts->get('rejected', 0);

    //     return view('member.contributions.index', compact(
    //         'contributions', // Pass $contributions
    //         'totalApprovedRegular', 'totalApprovedSocial', 'totalApprovedOther',
    //         'pendingCount', 'approvedCount', 'rejectedCount'
    //     ));
    // }

    // public function create()
    // {
    //     $user = Auth::user();
    //     // Ensure member profile is approved
    //     if (!$user->memberProfile || $user->memberProfile->status !== 'approved') {
    //         return redirect()->route('dashboard')->with('error', 'Your membership profile must be approved to make contributions.');
    //     }
    
    //     $memberCategory = $user->memberProfile->memberCategory; // Eager load if not already
    //     // HERE IS THE LOGIC:
    //     $defaultMonthlyAmount = $memberCategory ? $memberCategory->monthly_contribution : null;
    //     $defaultSocialAmount = $memberCategory ? $memberCategory->social_monthly_contribution : null;
    
    //     return view('member.contributions.create', compact('defaultMonthlyAmount', 'defaultSocialAmount'));
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'type' => 'required|string|max:255',
    //         'amount' => 'required|numeric|min:1',
    //         'payment_method' => 'required|string|max:100',
    //         'transaction_id' => 'nullable|string|max:255|unique:contributions,transaction_id',
    //         'description' => 'nullable|string',
    //         'payment_date' => 'nullable|date',
    //     ]);

    //     $contribution = new Contribution($request->all());
    //     $contribution->user_id = Auth::id();
    //     $contribution->status = 'pending'; // Default to pending
    //     $contribution->save();

    //     // You might want to include payment instructions here based on payment_method
    //     return redirect()->route('member.contributions.index')
    //                      ->with('success', 'Contribution submitted successfully! It is now pending approval. Please follow payment instructions if applicable.');
    // }

    public function show(Contribution $contribution)
    {
        // Authorization is handled by middleware 'can:view,contribution'
        // and the policy we will create next.
        return view('member.contributions.show', compact('contribution'));
    }
}