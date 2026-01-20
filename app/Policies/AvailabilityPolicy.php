<?php

namespace App\Policies;

use App\Models\Availability;
use App\Models\User;

class AvailabilityPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isPhotographer();
    }

    public function view(User $user, Availability $availability): bool
    {
        return $user->photographer?->id === $availability->photographer_id;
    }

    public function create(User $user): bool
    {
        return $user->isPhotographer();
    }

    public function update(User $user, Availability $availability): bool
    {
        return $user->photographer?->id === $availability->photographer_id;
    }

    public function delete(User $user, Availability $availability): bool
    {
        return $user->photographer?->id === $availability->photographer_id;
    }
}
