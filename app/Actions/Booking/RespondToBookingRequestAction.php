<?php

namespace App\Actions\Booking;

use App\Enums\BookingStatus;
use App\Events\BookingRequestResponded;
use App\Models\BookingRequest;
use Carbon\Carbon;

class RespondToBookingRequestAction
{
    public function execute(
        BookingRequest $bookingRequest,
        string|BookingStatus $status,
        ?string $response = null,
        ?float $proposedPrice = null
    ): BookingRequest {
        $statusValue = $status instanceof BookingStatus ? $status : BookingStatus::from($status);

        $data = [
            'status' => $statusValue,
            'photographer_response' => $response,
            'responded_at' => Carbon::now(),
        ];

        if ($statusValue === BookingStatus::Accepted && $proposedPrice !== null) {
            $data['proposed_price'] = $proposedPrice;
        }

        $bookingRequest->update($data);

        event(new BookingRequestResponded($bookingRequest));

        return $bookingRequest;
    }

    public function canRespond(BookingRequest $bookingRequest): bool
    {
        return $bookingRequest->status === BookingStatus::Pending;
    }
}
