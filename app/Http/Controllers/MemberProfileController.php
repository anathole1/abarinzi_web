<?php

namespace App\Http\Controllers;

use App\Models\MemberProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       // Prevent access if profile already exists (unless admin wants to edit - handle separately)
        if (Auth::user()->memberProfile) {
             // If profile exists and is pending, redirect to a pending page or dashboard with message
            if (Auth::user()->memberProfile->status === 'pending') {
                return redirect()->route('dashboard')->with('status', 'Your membership application is pending review.');
            }
            // If approved, redirect to dashboard
            return redirect()->route('dashboard');
        }

        $membershipCategories = MemberProfile::getMembershipCategoryAmounts();
        return view('member-profile.create', compact('membershipCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Prevent creating multiple profiles for the same user
        if ($user->memberProfile) {
            return redirect()->route('dashboard')->withErrors(['profile' => 'You have already submitted your membership details.']);
        }

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:member_profiles,phone_number',
            'national_id' => 'required|string|max:50|unique:member_profiles,national_id',
            'year_left_efotec' => 'nullable|integer|digits:4', // Or 'string|max:10'
            'current_location' => 'nullable|string|max:255',
            'membership_category' => 'required|in:bronze,silver,gold',
        ]);

        $profile = new MemberProfile($validatedData);
        $profile->user_id = $user->id;
        $profile->status = 'pending'; // Default status
        $profile->save();

        return redirect()->route('dashboard')->with('status', 'Membership information submitted successfully! Your application is pending review.');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(MemberProfile $memberProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MemberProfile $memberProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MemberProfile $memberProfile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MemberProfile $memberProfile)
    {
        //
    }
}