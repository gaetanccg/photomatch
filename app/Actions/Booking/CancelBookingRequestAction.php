<?php

namespace App\Actions\Booking;

use App\Models\BookingRequest;
use App\Models\User;
use App\Notifications\BookingRequestCancelled;

class CancelBookingRequestAction
{
    public function execute(BookingRequest $bookingRequest, User $cancelledBy): void
    {
        $isClient = $cancelledBy->isClient();
        $cancelledByRole = $isClient ? 'client' : 'photographer';

        $notifiable = $isClient
            ? $bookingRequest->photographer->user
            : $bookingRequest->project->client;

        $notifiable->notify(new BookingRequestCancelled($bookingRequest, $cancelledByRole));

        $bookingRequest->delete();
    }

    public function getRedirectRoute(User $user): string
    {
        return $user->isClient()
            ? 'client.requests.index'
            : 'photographer.requests.index';
    }
}
