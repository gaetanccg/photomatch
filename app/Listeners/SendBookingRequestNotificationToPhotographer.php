<?php

namespace App\Listeners;

use App\Events\BookingRequestCreated;
use App\Notifications\BookingRequestReceived;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBookingRequestNotificationToPhotographer implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(BookingRequestCreated $event): void
    {
        $bookingRequest = $event->bookingRequest;
        $photographer = $bookingRequest->photographer;
        $photographerUser = $photographer->user;

        // Send notification to the photographer
        $photographerUser->notify(new BookingRequestReceived($bookingRequest));
    }
}
