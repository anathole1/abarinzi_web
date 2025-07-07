<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // For DB::raw if needed
use App\Models\User;                // For admin stats
use App\Models\MemberProfile;        // For admin stats

class DashboardController extends Controller
{
    // Helper function for categorizing contributions (can be a private method or Trait)
    private function getDashboardContributionCategorization(string $type): string {
        $type = strtolower(trim($type));
        // IMPORTANT: Keep this mapping updated with your actual contribution types
        $regularTypes = ['monthly_membership' /*, 'annual dues', 'general support'*/]; // Add all your "regular" types
        $socialTypes = ['social_contribution' /*, 'event_fee', 'welfare'*/]; // Add all your "social" types

        if (in_array($type, $regularTypes)) return 'regular';
        if (in_array($type, $socialTypes)) return 'social';
        return 'other';
    }

    public function index()
    {
        $user = Auth::user();
        $viewData = [];

        if ($user->hasRole('admin') || $user->hasRole('author')) { // Simplified: Admins and Authors see a generic 'dashboard' view
            // You can add specific data for admin/author dashboards here if needed
            if ($user->hasRole('admin')) {
                $viewData['totalUsers'] = User::count();
                $viewData['pendingMembershipsCount'] = MemberProfile::where('status', 'pending')->count();
            }
            // Authors might have other specific stats later
            return view('dashboard', $viewData); // This is your Breeze main dashboard
        }

        // Logic for 'member' role
        if ($user->memberProfile) {
            if ($user->memberProfile->status === 'pending') {
                return view('dashboard-pending', $viewData);
            } elseif ($user->memberProfile->status === 'approved') {
                $user->memberProfile->load('memberCategory'); // Eager load category

                // Contribution summary for approved members
                $allApprovedUserContributions = $user->contributions()
                    ->where('status', 'approved')
                    ->select('type', 'amount') // Ensure 'type' is selected
                    ->get();

                $totalApprovedRegular = 0;
                $totalApprovedSocial = 0;
                $totalApprovedOther = 0;

                foreach ($allApprovedUserContributions as $contribution) {
                    // Use the helper method from this controller
                    $category = $this->getDashboardContributionCategorization($contribution->type);
                    if ($category === 'regular') {
                        $totalApprovedRegular += $contribution->amount;
                    } elseif ($category === 'social') {
                        $totalApprovedSocial += $contribution->amount;
                    } else {
                        $totalApprovedOther += $contribution->amount;
                    }
                }
                $viewData['totalApprovedRegular'] = $totalApprovedRegular;
                $viewData['totalApprovedSocial'] = $totalApprovedSocial;
                $viewData['totalApprovedOther'] = $totalApprovedOther;

                $statusCounts = $user->contributions()
                    ->groupBy('status')
                    ->select('status', DB::raw('count(*) as count'))
                    ->pluck('count', 'status');
                $viewData['pendingContributionsCount'] = $statusCounts->get('pending', 0);
                $viewData['approvedContributionsCount'] = $statusCounts->get('approved', 0); // Total approved items

                $viewData['memberProfile'] = $user->memberProfile; // Pass profile with loaded category
                return view('dashboard', $viewData); // Breeze main dashboard
            } elseif ($user->memberProfile->status === 'rejected') {
                 $viewData['memberProfile'] = $user->memberProfile; // Pass profile for rejected message
                return view('dashboard', $viewData); // Breeze main dashboard, handles rejected message
            }
        }

        // Fallback if member has no profile yet
        return redirect()->route('member-profile.create');
    }
}