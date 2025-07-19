<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\MemberProfileUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileUpdateController extends Controller {
    // Add permission checks in constructor or routes

    public function index() {
        $pendingUpdates = MemberProfileUpdate::with(['user', 'memberProfile'])
                                             ->where('status', 'pending')
                                             ->orderBy('created_at', 'asc')
                                             ->paginate(15);
        return view('admin.profile_updates.index', compact('pendingUpdates'));
    }

    public function show(MemberProfileUpdate $profileUpdate) {
        $profileUpdate->load(['user', 'memberProfile.memberCategory']);
        return view('admin.profile_updates.show', compact('profileUpdate'));
    }

    public function approve(MemberProfileUpdate $profileUpdate) {
        if ($profileUpdate->status !== 'pending') {
            return redirect()->route('admin.profile-updates.index')->with('error', 'This request has already been processed.');
        }

        DB::beginTransaction();
        try {
            $memberProfile = $profileUpdate->memberProfile;
            $newData = $profileUpdate->updated_data;

            // Handle photo update specifically
            $newPhotoPath = $newData['photoUrl'] ?? null;
            $oldPhotoPath = $memberProfile->photoUrl;

            if ($newPhotoPath && $newPhotoPath !== $oldPhotoPath) {
                // New photo was uploaded, delete the old one
                if ($oldPhotoPath && Storage::disk('public')->exists($oldPhotoPath)) {
                    Storage::disk('public')->delete($oldPhotoPath);
                }
                // Move photo from pending to main directory (or just update path)
                // For simplicity, we assume the path is fine as is, but you could move it
                // from 'member_photos_pending_update' to 'member_photos'.
            }

            $memberProfile->update($newData);
            $profileUpdate->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);
            DB::commit();
            return redirect()->route('admin.profile-updates.index')->with('success', 'Profile update approved and applied.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error("Profile Update Approval Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while approving the update.');
        }
    }

    public function reject(Request $request, MemberProfileUpdate $profileUpdate) {
        $request->validate(['rejection_reason' => 'required|string|max:1000']);
         if ($profileUpdate->status !== 'pending') {
            return redirect()->route('admin.profile-updates.index')->with('error', 'This request has already been processed.');
        }
        // Delete the uploaded (but now rejected) photo if it exists
        $newPhotoPath = $profileUpdate->updated_data['photoUrl'] ?? null;
        if ($newPhotoPath && $newPhotoPath !== $profileUpdate->memberProfile->photoUrl && Storage::disk('public')->exists($newPhotoPath)) {
            Storage::disk('public')->delete($newPhotoPath);
        }
        $profileUpdate->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);
        // TODO: Notify user of rejection
        return redirect()->route('admin.profile-updates.index')->with('success', 'Profile update request rejected.');
    }
}