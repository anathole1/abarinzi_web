<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileCompletedAndApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // If user is not authenticated, let other middleware (like 'auth') handle it.
        if (!$user) {
            return $next($request);
        }

        // Admins can access anything.
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // For members:
        if ($user->hasRole('member')) {
            // 1. Profile must exist.
            if (!$user->memberProfile) {
                // Allow access to profile creation page.
                if ($request->routeIs('member-profile.create')) {
                    return $next($request);
                }
                return redirect()->route('member-profile.create')->with('warning', 'Please complete your membership profile to continue.');
            }

            // 2. Profile status must be 'approved' for routes protected by this middleware.
            if ($user->memberProfile->status !== 'approved') {
                // Allow access to dashboard (it will show pending/rejected message).
                if ($request->routeIs('dashboard')) {
                    return $next($request);
                }
                // For other pages that specifically require approval, redirect to dashboard.
                return redirect()->route('dashboard')->with('info', 'Your membership is not yet approved. Access to this section is restricted.');
            }
        }
        // If all checks pass (or user is not a member and not admin - though 'auth' should catch this)
        return $next($request);
    }
}