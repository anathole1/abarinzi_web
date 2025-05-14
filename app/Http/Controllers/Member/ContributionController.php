<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
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
        $contributionsQuery = $user->contributions();

        // Apply status filter if present
        if ($request->filled('status_filter') && in_array($request->status_filter, ['pending', 'approved', 'rejected'])) {
            $contributionsQuery->where('status', $request->status_filter);
        }

        // Paginated list of all contributions for the main table
        $contributions = $contributionsQuery->clone() // Clone to avoid modifying the original query object
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);

        // Get all approved contributions for sum calculation
        $allApprovedUserContributions = $user->contributions()
            ->where('status', 'approved')
            ->select('type', 'amount') // Select type and amount
            ->get();

        $totalApprovedRegular = 0;
        $totalApprovedSocial = 0;
        $totalApprovedOther = 0; // For contributions that don't fit regular/social

        foreach ($allApprovedUserContributions as $contribution) {
            $category = $this->getContributionCategory($contribution->type);
            if ($category === 'regular') {
                $totalApprovedRegular += $contribution->amount;
            } elseif ($category === 'social') {
                $totalApprovedSocial += $contribution->amount;
            } else {
                $totalApprovedOther += $contribution->amount;
            }
        }

        // Counts by status
        $statusCounts = $user->contributions()
            ->groupBy('status')
            ->select('status', DB::raw('count(*) as count'))
            ->pluck('count', 'status');

        // Ensure all statuses have a count, even if 0
        $pendingCount = $statusCounts->get('pending', 0);
        $approvedCount = $statusCounts->get('approved', 0); // This is total approved items count
        $rejectedCount = $statusCounts->get('rejected', 0);


        return view('member.contributions.index', compact(
            'contributions',
            'totalApprovedRegular',     // New variable for the view
            'totalApprovedSocial',      // New variable for the view
            'totalApprovedOther',       // New variable for the view
            'pendingCount',
            'approvedCount',            // Total count of approved contributions
            'rejectedCount'
        ));
    }


    public function create()
    {
        // You might want to pass contribution types or other data here
        return view('member.contributions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|max:100',
            'transaction_id' => 'nullable|string|max:255|unique:contributions,transaction_id',
            'description' => 'nullable|string',
            'payment_date' => 'nullable|date',
        ]);

        $contribution = new Contribution($request->all());
        $contribution->user_id = Auth::id();
        $contribution->status = 'pending'; // Default to pending
        $contribution->save();

        // You might want to include payment instructions here based on payment_method
        return redirect()->route('member.contributions.index')
                         ->with('success', 'Contribution submitted successfully! It is now pending approval. Please follow payment instructions if applicable.');
    }

    public function show(Contribution $contribution)
    {
        // Authorization is handled by middleware 'can:view,contribution'
        // and the policy we will create next.
        return view('member.contributions.show', compact('contribution'));
    }
}