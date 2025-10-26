<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\MemberProfile;

class DashboardController extends Controller
{
    /**
     * Categorizes contribution types for summary display.
     */
    private function getDashboardContributionCategorization(string $type): string
    {
        $type = strtolower(trim($type));
        $regularTypes = ['monthly_membership'];
        $socialTypes = ['social_contribution'];

        if (in_array($type, $regularTypes)) return 'regular';
        if (in_array($type, $socialTypes)) return 'social';
        return 'other';
    }

    /**
     * Display the appropriate dashboard based on user permissions and profile status.
     */
    public function index()
    {
        $user = Auth::user();
        $viewData = [];

        // --- HIERARCHICAL PERMISSION-BASED ROUTING ---

        // 1. Check for Admin-level access.
        // We use a high-level permission that only admins should have.
        if ($user->can('manage roles and permissions')) {
            // Admin can see everything, so redirect to the dedicated admin dashboard.
            return redirect()->route('admin.dashboard');
        }

        // 2. Check for Approval-level access (if not an admin).
        if ($user->can('approve memberships')) {
            // For now, an approver can also use the admin dashboard,
            // which will show them the sections they have access to.
            return redirect()->route('admin.dashboard');
        }

        // 3. Check for Author-level access (if not an admin or approver).
        if ($user->can('manage website content')) {
            // Authors can also use the admin dashboard layout.
            // The navigation will only show them what they can access.
            return redirect()->route('admin.dashboard');
        }

        // 4. THE CRUCIAL CHECK FOR NEW MEMBERS
        // If the user has permission to be a member but doesn't have a profile record yet,
        // they must be redirected to create one.
        if ($user->can('complete member profile') && !$user->memberProfile) {
            return redirect()->route('member-profile.create');
        }

        // 5. Handle existing members who have already submitted a profile
        if ($user->memberProfile) {
            // For pending members, show a specific, simplified dashboard.
            if ($user->memberProfile->status === 'pending') {
                return view('dashboard-pending');
            }

            // For approved or rejected members, we prepare data and show the main dashboard view.
            // The view itself will differentiate the content.
            $viewData['memberProfile'] = $user->memberProfile;

            if ($user->memberProfile->status === 'approved') {
                $user->memberProfile->load('memberCategory');

                // Contribution summary logic for approved members
                $allApprovedUserContributions = $user->contributions()->where('status', 'approved')->select('type', 'amount')->get();
                $totalApprovedRegular = 0; $totalApprovedSocial = 0; $totalApprovedOther = 0;

                foreach ($allApprovedUserContributions as $contribution) {
                    $category = $this->getDashboardContributionCategorization($contribution->type);
                    if ($category === 'regular') { $totalApprovedRegular += $contribution->amount; }
                    elseif ($category === 'social') { $totalApprovedSocial += $contribution->amount; }
                    else { $totalApprovedOther += $contribution->amount; }
                }
                $viewData['totalApprovedRegular'] = $totalApprovedRegular;
                $viewData['totalApprovedSocial'] = $totalApprovedSocial;
                $viewData['totalApprovedOther'] = $totalApprovedOther;

                $statusCounts = $user->contributions()->groupBy('status')->select('status', DB::raw('count(*) as count'))->pluck('count', 'status');
                $viewData['pendingContributionsCount'] = $statusCounts->get('pending', 0);
                $viewData['approvedContributionsCount'] = $statusCounts->get('approved', 0);
            }
        }

        // This is the final destination for all members who have a profile (approved or rejected).
        // It will receive the prepared $viewData.
        return view('dashboard', $viewData);
    }
}

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB; // For DB::raw if needed
// use App\Models\User;                // For admin stats
// use App\Models\MemberProfile;        // For admin stats

// class DashboardController extends Controller
// {
//     // Helper function for categorizing contributions (can be a private method or Trait)
//     private function getDashboardContributionCategorization(string $type): string {
//         $type = strtolower(trim($type));
//         // IMPORTANT: Keep this mapping updated with your actual contribution types
//         $regularTypes = ['monthly_membership' /*, 'annual dues', 'general support'*/]; // Add all your "regular" types
//         $socialTypes = ['social_contribution' /*, 'event_fee', 'welfare'*/]; // Add all your "social" types

//         if (in_array($type, $regularTypes)) return 'regular';
//         if (in_array($type, $socialTypes)) return 'social';
//         return 'other';
//     }

//     public function index()
//     {
//         $user = Auth::user();
//         $viewData = [];

//         // --- NEW PERMISSION-BASED LOGIC ---

//         // If user can manage users, they are likely an admin. Send to admin dash.
//         if ($user->can('manage users')) {
//             $viewData['totalUsers'] = User::count();
//             $viewData['pendingMembershipsCount'] = MemberProfile::where('status', 'pending')->count();
//             // This is your full admin dashboard view
//             return view('dashboard', $viewData);
//         }

//         // If user can approve things but not manage users, they are an 'approval' role.
//         if ($user->can('approve memberships')) {
//              // You can create a dedicated 'approval dashboard' view or use the main one
//             return view('dashboard', $viewData);
//         }

//         // If user can manage content, they are an 'author'
//         if ($user->can('manage website content')) {
//             return view('dashboard', $viewData);
//         }

//         // If the user has the permission to be a member and needs to complete their profile
//         if ($user->can('complete member profile') && !$user->memberProfile) {
//             return redirect()->route('member-profile.create');
//         }

//         // --- END NEW LOGIC ---


//         // Existing logic for members who have a profile
//         if ($user->memberProfile) {
//             if ($user->memberProfile->status === 'pending') {
//                 return view('dashboard-pending', $viewData);
//             } elseif ($user->memberProfile->status === 'approved') {
//                 $user->memberProfile->load('memberCategory');
//                 // ... (your existing contribution summary logic) ...
//                 $allApprovedUserContributions = $user->contributions()->where('status', 'approved')->select('type', 'amount')->get();
//                 // ...
//                 $viewData['memberProfile'] = $user->memberProfile;
//                 return view('dashboard', $viewData);
//             } elseif ($user->memberProfile->status === 'rejected') {
//                  $viewData['memberProfile'] = $user->memberProfile;
//                 return view('dashboard', $viewData);
//             }
//         }

//         // Fallback for any other authenticated user without a specific path
//         // For example, a user with no roles/permissions assigned yet
//         return view('dashboard');
//     }

//     // public function index()
//     // {
//     //     $user = Auth::user();
//     //     $viewData = [];

//     //     if ($user->hasRole('admin') || $user->hasRole('author')) { // Simplified: Admins and Authors see a generic 'dashboard' view
//     //         // You can add specific data for admin/author dashboards here if needed
//     //         if ($user->hasRole('admin')) {
//     //             $viewData['totalUsers'] = User::count();
//     //             $viewData['pendingMembershipsCount'] = MemberProfile::where('status', 'pending')->count();
//     //         }
//     //         // Authors might have other specific stats later
//     //         return view('dashboard', $viewData); // This is your Breeze main dashboard
//     //     }

//     //     // Logic for 'member' role
//     //     if ($user->memberProfile) {
//     //         if ($user->memberProfile->status === 'pending') {
//     //             return view('dashboard-pending', $viewData);
//     //         } elseif ($user->memberProfile->status === 'approved') {
//     //             $user->memberProfile->load('memberCategory'); // Eager load category

//     //             // Contribution summary for approved members
//     //             $allApprovedUserContributions = $user->contributions()
//     //                 ->where('status', 'approved')
//     //                 ->select('type', 'amount') // Ensure 'type' is selected
//     //                 ->get();

//     //             $totalApprovedRegular = 0;
//     //             $totalApprovedSocial = 0;
//     //             $totalApprovedOther = 0;

//     //             foreach ($allApprovedUserContributions as $contribution) {
//     //                 // Use the helper method from this controller
//     //                 $category = $this->getDashboardContributionCategorization($contribution->type);
//     //                 if ($category === 'regular') {
//     //                     $totalApprovedRegular += $contribution->amount;
//     //                 } elseif ($category === 'social') {
//     //                     $totalApprovedSocial += $contribution->amount;
//     //                 } else {
//     //                     $totalApprovedOther += $contribution->amount;
//     //                 }
//     //             }
//     //             $viewData['totalApprovedRegular'] = $totalApprovedRegular;
//     //             $viewData['totalApprovedSocial'] = $totalApprovedSocial;
//     //             $viewData['totalApprovedOther'] = $totalApprovedOther;

//     //             $statusCounts = $user->contributions()
//     //                 ->groupBy('status')
//     //                 ->select('status', DB::raw('count(*) as count'))
//     //                 ->pluck('count', 'status');
//     //             $viewData['pendingContributionsCount'] = $statusCounts->get('pending', 0);
//     //             $viewData['approvedContributionsCount'] = $statusCounts->get('approved', 0); // Total approved items

//     //             $viewData['memberProfile'] = $user->memberProfile; // Pass profile with loaded category
//     //             return view('dashboard', $viewData); // Breeze main dashboard
//     //         } elseif ($user->memberProfile->status === 'rejected') {
//     //              $viewData['memberProfile'] = $user->memberProfile; // Pass profile for rejected message
//     //             return view('dashboard', $viewData); // Breeze main dashboard, handles rejected message
//     //         }
//     //     }

//     //     // Fallback if member has no profile yet
//     //     return redirect()->route('member-profile.create');
//     // }
// } -->