<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Review $review): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isClient();
    }

    public function respond(User $user, Review $review): bool
    {
        return $user->photographer?->id === $review->photographer_id;
    }

    public function delete(User $user, Review $review): bool
    {
        // Only admin can delete reviews
        return $user->isAdmin();
    }
}
