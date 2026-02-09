<?php

namespace App\Policies;

use App\Enums\BookingStatus;
use App\Models\PhotoProject;
use App\Models\User;

class PhotoProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isClient();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PhotoProject $photoProject): bool
    {
        return $user->id === $photoProject->client_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isClient();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PhotoProject $photoProject): bool
    {
        return $user->id === $photoProject->client_id
            && in_array($photoProject->status, ['draft', 'published']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PhotoProject $photoProject): bool
    {
        if ($user->id !== $photoProject->client_id) {
            return false;
        }

        // Cannot delete if has accepted requests
        return !$photoProject->bookingRequests()
            ->where('status', BookingStatus::Accepted)
            ->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PhotoProject $photoProject): bool
    {
        return $user->id === $photoProject->client_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PhotoProject $photoProject): bool
    {
        return $user->id === $photoProject->client_id;
    }
}
