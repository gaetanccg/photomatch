<?php

namespace App\Actions\Booking;

use App\Enums\BookingStatus;
use App\Events\BookingRequestCreated;
use App\Models\BookingRequest;
use App\Models\PhotoProject;
use App\Models\Photographer;
use Carbon\Carbon;

class CreateBookingRequestAction
{
    public function execute(
        PhotoProject $project,
        Photographer $photographer,
        ?string $message = null,
        ?float $proposedPrice = null
    ): BookingRequest {
        $bookingRequest = BookingRequest::create([
            'project_id' => $project->id,
            'photographer_id' => $photographer->id,
            'status' => BookingStatus::Pending,
            'client_message' => $message,
            'proposed_price' => $proposedPrice,
            'sent_at' => Carbon::now(),
        ]);

        event(new BookingRequestCreated($bookingRequest));

        return $bookingRequest;
    }

    public function canCreate(PhotoProject $project, Photographer $photographer): array
    {
        $errors = [];

        if ($project->status !== 'published') {
            $errors[] = 'Le projet doit être publié pour envoyer une demande.';
        }

        $existingRequest = BookingRequest::where('project_id', $project->id)
            ->where('photographer_id', $photographer->id)
            ->exists();

        if ($existingRequest) {
            $errors[] = 'Une demande a déjà été envoyée à ce photographe pour ce projet.';
        }

        return $errors;
    }
}
