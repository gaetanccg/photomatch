<?php

namespace App\Policies;

use App\Models\BookingRequest;
use App\Models\User;

class BookingRequestPolicy
{
    public function view(User $user, BookingRequest $bookingRequest): bool
    {
        // Photographer can view their own requests
        if ($user->isPhotographer() && $user->photographer?->id === $bookingRequest->photographer_id) {
            return true;
        }

        // Client can view requests for their projects
        if ($user->isClient() && $bookingRequest->project->client_id === $user->id) {
            return true;
        }

        return false;
    }

    public function update(User $user, BookingRequest $bookingRequest): bool
    {
        // Only the photographer can respond to requests
        return $user->photographer?->id === $bookingRequest->photographer_id;
    }
}
