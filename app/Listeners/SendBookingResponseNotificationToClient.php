<?php

namespace App\Listeners;

use App\Enums\BookingStatus;
use App\Events\BookingRequestResponded;
use App\Notifications\BookingRequestAccepted;
use App\Notifications\BookingRequestDeclined;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBookingResponseNotificationToClient implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(BookingRequestResponded $event): void
    {
        $bookingRequest = $event->bookingRequest;
        $client = $bookingRequest->project->client;

        // Send appropriate notification based on status
        if ($bookingRequest->status === BookingStatus::Accepted) {
            $client->notify(new BookingRequestAccepted($bookingRequest));
        } else {
            $client->notify(new BookingRequestDeclined($bookingRequest));
        }
    }
}
