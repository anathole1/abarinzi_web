<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MemberProfile;
use App\Models\MemberProfileUpdate; // Import for stats
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Fetch any data needed specifically for the admin dashboard statistics
        $viewData = [
            'totalUsers' => User::count(),
            'pendingMembershipsCount' => MemberProfile::where('status', 'pending')->count(),
            'activeMembersCount' => MemberProfile::where('status', 'approved')->count(),
            // No need to pass pendingUpdatesCount as the view calculates it, but you could pass it from here too.
        ];

        return view('admin.dashboard', $viewData);
    }
}