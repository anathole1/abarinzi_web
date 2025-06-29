<?php

namespace App\Http\Controllers; // Assuming it's not in Member subdirectory

use App\Models\MemberProfile;
use App\Models\MemberCategory; // Import MemberCategory
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // For generating accountNo if needed

class MemberProfileController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        // Prevent access if profile already exists and is not rejected
        if ($user->memberProfile && $user->memberProfile->status !== 'rejected') {
            if ($user->memberProfile->status === 'pending') {
                return redirect()->route('dashboard')->with('status', 'Your membership application is pending review.');
            }
            return redirect()->route('dashboard');
        }

        $membershipCategories = MemberCategory::where('is_active', true)->orderBy('name')->get();
        // Pre-fill email from user account
        $prefilledEmail = $user->email;

        return view('member-profile.create', compact('membershipCategories', 'prefilledEmail'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // If profile exists and status is not 'rejected', prevent re-submission
        if ($user->memberProfile && $user->memberProfile->status !== 'rejected') {
            return redirect()->route('dashboard')->withErrors(['profile' => 'You have already submitted your membership details or it is being processed.']);
        }

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:member_profiles,email,' . ($user->memberProfile->id ?? null), // Unique profile email
            'phone_number' => 'required|string|max:20|unique:member_profiles,phone_number,' . ($user->memberProfile->id ?? null),
            'national_id' => 'required|string|max:50|unique:member_profiles,national_id,' . ($user->memberProfile->id ?? null),
            'year_left_efotec' => 'nullable|string|max:10',
            'current_location' => 'nullable|string|max:255',
            'dateJoined' => 'required|date|before_or_equal:today',
            'member_category_id' => 'required|exists:member_categories,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // New photo field
        ]);

 $photoPathDatabase = null; // This will store the path relative to the 'public' directory

        if ($request->hasFile('photo')) {
            $imageFile = $request->file('photo');
            $uploadDirectory = 'uploads/member_photos/'; // Path within public directory
            $destinationPath = public_path($uploadDirectory); // Absolute path to public/uploads/member_photos/

            // Create a unique filename
            $filename = time() . '_' . Str::slug(pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $imageFile->getClientOriginalExtension();

            // Ensure the directory exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true); // Create it with writable permissions
            }

            // Delete old photo if updating and exists
            if($user->memberProfile && $user->memberProfile->photoUrl && file_exists(public_path($user->memberProfile->photoUrl))){
                unlink(public_path($user->memberProfile->photoUrl));
            }

            // Move the uploaded file to the public directory
            $imageFile->move($destinationPath, $filename);
            $photoPathDatabase = $uploadDirectory . $filename; // e.g., "uploads/member_photos/12345_john_doe.jpg"

        } elseif ($user->memberProfile && $user->memberProfile->photoUrl) {
            // Keep existing photo if not updating and it exists
            $photoPathDatabase = $user->memberProfile->photoUrl;
        }

        $profileData = [
            'user_id' => $user->id,
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'], // Profile specific email
            'phone_number' => $validatedData['phone_number'],
            'national_id' => $validatedData['national_id'],
            'year_left_efotec' => $validatedData['year_left_efotec'],
            'current_location' => $validatedData['current_location'],
            'dateJoined' => $validatedData['dateJoined'],
            'member_category_id' => $validatedData['member_category_id'],
            'photoUrl' => $photoPathDatabase,
            'status' => 'pending', // Reset status to pending on re-submission after rejection
            // 'accountNo' => $this->generateAccountNumber(), // Generate account number here or upon approval
        ];

        // Update existing profile if it was rejected, otherwise create new
        if ($user->memberProfile && $user->memberProfile->status === 'rejected') {
            // Ensure accountNo is not overwritten if it exists, or generate if it doesn't
            if(empty($user->memberProfile->accountNo)) {
                $profileData['accountNo'] = $this->generateAccountNumber();
            } else {
                 $profileData['accountNo'] = $user->memberProfile->accountNo;
            }
            $user->memberProfile->update($profileData);
        } else {
            $profileData['accountNo'] = $this->generateAccountNumber();
            MemberProfile::create($profileData);
        }


        return redirect()->route('dashboard')->with('status', 'Membership information submitted successfully! Your application is pending review.');
    }

    /**
     * Generate a unique account number.
     * Implement your preferred logic (e.g., prefix + timestamp + random).
     */
    protected function generateAccountNumber(): string
    {
        // Basic example: EFOTEC-YearMonth-RandomString
        // Ensure this is sufficiently unique for your needs.
        $prefix = "2001";
        $timestamp = now()->format('Ym');
        do {
            $randomPart = Str::upper(Str::random(4));
            $accountNo = "{$prefix}{$timestamp}{$randomPart}";
        } while (MemberProfile::where('accountNo', $accountNo)->exists());
        return $accountNo;
    }
}