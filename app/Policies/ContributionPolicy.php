<?php

namespace App\Policies;

use App\Models\Contribution;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContributionPolicy
{
    /**
     * Determine whether the user can view any models.
     * Typically, an admin can view all, or it's based on listing.
     * For member's own list, controller logic handles filtering.
     */
    // public function viewAny(User $user): bool
    // {
    //     return $user->hasRole('admin'); // Example: only admin can view all if there was such a route
    // }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Contribution $contribution): bool
    {
        return $user->id === $contribution->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     * (Approved members can create)
     */
    public function create(User $user): bool
    {
        return $user->hasRole('member') && $user->memberProfile && $user->memberProfile->status === 'approved';
    }

    /**
     * Determine whether the user can update the model.
     * (Typically only admins, or users if status is pending and editable)
     */
    public function update(User $user, Contribution $contribution): bool
    {
        return $user->hasRole('admin'); // Members cannot update after submission in this flow
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Contribution $contribution): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Contribution $contribution): bool
    // {
    //     return $user->hasRole('admin');
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Contribution $contribution): bool
    // {
    //     return $user->hasRole('admin');
    // }
}