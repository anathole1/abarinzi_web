<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberProfile;
use App\Models\User; // For filtering options
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Display a listing of memberships, with filtering.
     */
    public function index(Request $request)
    {
        $query = MemberProfile::with('user')->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status_filter') && in_array($request->status_filter, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status_filter);
        }

        // Search by name or email (across User and MemberProfile)
        if ($request->filled('search_term')) {
            $searchTerm = '%' . $request->search_term . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', $searchTerm)
                  ->orWhere('last_name', 'like', $searchTerm)
                  ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm)
                                ->orWhere('email', 'like', $searchTerm);
                  });
            });
        }

        $memberships = $query->paginate(15)->appends($request->query()); // appends query string to pagination links

        return view('admin.memberships.index', compact('memberships'));
    }

    /**
     * Display the specified member profile details.
     */
    public function show(MemberProfile $memberProfile) // Route model binding
    {
        $memberProfile->load('user'); // Eager load user details
        return view('admin.memberships.show', compact('memberProfile'));
    }


    /**
     * Approve a membership.
     */
    public function approve(MemberProfile $memberProfile)
    {
        if ($memberProfile->status !== 'pending') {
            return redirect()->route('admin.memberships.index')->with('error', 'This membership is not pending approval.');
        }
        $memberProfile->status = 'approved';
        $memberProfile->save();
        // Optionally, send a notification
        return redirect()->route('admin.memberships.index')->with('success', 'Membership for ' . $memberProfile->user->name . ' approved.');
    }

    /**
     * Reject a membership.
     */
    public function reject(MemberProfile $memberProfile)
    {
        if ($memberProfile->status !== 'pending') {
            return redirect()->route('admin.memberships.index')->with('error', 'This membership is not pending rejection.');
        }
        $memberProfile->status = 'rejected';
        $memberProfile->save();
        // Optionally, send a notification
        return redirect()->route('admin.memberships.index')->with('success', 'Membership for ' . $memberProfile->user->name . ' rejected.');
    }
}

// namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\Controller;
// use App\Models\MemberProfile;
// use Illuminate\Http\Request;

// class MembershipController extends Controller
// {
//     /**
//      * Display a listing of pending memberships.
//      */
//     public function index()
//     {
//         $pendingMemberships = MemberProfile::with('user') // Eager load user relationship
//                                         ->where('status', 'pending')
//                                         ->orderBy('created_at', 'desc')
//                                         ->paginate(15); // Paginate for many entries

//         return view('admin.memberships.index', compact('pendingMemberships'));
//     }

//     /**
//      * Approve a membership.
//      */
//     public function approve(MemberProfile $memberProfile)
//     {
//         if ($memberProfile->status !== 'pending') {
//             return redirect()->route('admin.memberships.index')->with('error', 'This membership is not pending approval.');
//         }

//         $memberProfile->status = 'approved';
//         $memberProfile->save();

//         // Optionally, send a notification to the user
//         // $memberProfile->user->notify(new MembershipApprovedNotification($memberProfile));

//         return redirect()->route('admin.memberships.index')->with('success', 'Membership for ' . $memberProfile->user->name . ' approved successfully.');
//     }

//     /**
//      * Reject a membership.
//      */
//     public function reject(MemberProfile $memberProfile)
//     {
//         if ($memberProfile->status !== 'pending') {
//             return redirect()->route('admin.memberships.index')->with('error', 'This membership is not pending rejection.');
//         }

//         $memberProfile->status = 'rejected';
//         $memberProfile->save();

//         // Optionally, send a notification to the user
//         // $memberProfile->user->notify(new MembershipRejectedNotification($memberProfile));

//         return redirect()->route('admin.memberships.index')->with('success', 'Membership for ' . $memberProfile->user->name . ' rejected.');
//     }
// }