<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberProfile;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Display a listing of pending memberships.
     */
    public function index()
    {
        $pendingMemberships = MemberProfile::with('user') // Eager load user relationship
                                        ->where('status', 'pending')
                                        ->orderBy('created_at', 'desc')
                                        ->paginate(15); // Paginate for many entries

        return view('admin.memberships.index', compact('pendingMemberships'));
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

        // Optionally, send a notification to the user
        // $memberProfile->user->notify(new MembershipApprovedNotification($memberProfile));

        return redirect()->route('admin.memberships.index')->with('success', 'Membership for ' . $memberProfile->user->name . ' approved successfully.');
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

        // Optionally, send a notification to the user
        // $memberProfile->user->notify(new MembershipRejectedNotification($memberProfile));

        return redirect()->route('admin.memberships.index')->with('success', 'Membership for ' . $memberProfile->user->name . ' rejected.');
    }
}