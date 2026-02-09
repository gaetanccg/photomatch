<?php

namespace App\Policies;

use App\Enums\BookingStatus;
use App\Models\BookingRequest;
use App\Models\User;

class BookingRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isPhotographer() || $user->isClient();
    }

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

    public function create(User $user): bool
    {
        return $user->isClient();
    }

    public function update(User $user, BookingRequest $bookingRequest): bool
    {
        // Only the photographer can respond to pending requests
        if ($bookingRequest->status !== BookingStatus::Pending) {
            return false;
        }

        return $user->photographer?->id === $bookingRequest->photographer_id;
    }

    public function delete(User $user, BookingRequest $bookingRequest): bool
    {
        // Only pending requests can be deleted
        if ($bookingRequest->status !== BookingStatus::Pending) {
            return false;
        }

        // Client can cancel pending requests for their projects
        if ($user->isClient() && $bookingRequest->project->client_id === $user->id) {
            return true;
        }

        // Photographer can cancel pending requests they received
        if ($user->isPhotographer() && $user->photographer?->id === $bookingRequest->photographer_id) {
            return true;
        }

        return false;
    }
}
