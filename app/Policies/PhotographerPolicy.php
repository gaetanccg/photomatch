<?php

namespace App\Policies;

use App\Models\Photographer;
use App\Models\User;

class PhotographerPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Photographer $photographer): bool
    {
        return true;
    }

    public function update(User $user, Photographer $photographer): bool
    {
        return $user->photographer?->id === $photographer->id;
    }

    public function updateSpecialties(User $user, Photographer $photographer): bool
    {
        return $user->photographer?->id === $photographer->id;
    }
}
