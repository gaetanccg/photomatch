<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRequestCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public BookingRequest $bookingRequest,
        public string $cancelledBy // 'client' or 'photographer'
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $project = $this->bookingRequest->project;

        return (new MailMessage)
            ->subject('Demande de réservation annulée')
            ->view('emails.booking-request-cancelled', [
                'bookingRequest' => $this->bookingRequest,
                'photographer' => $this->bookingRequest->photographer,
                'client' => $project->client,
                'cancelledBy' => $this->cancelledBy,
                'notifiable' => $notifiable,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $project = $this->bookingRequest->project;
        $photographer = $this->bookingRequest->photographer;

        $message = $this->cancelledBy === 'client'
            ? $project->client->name . ' a annulé sa demande pour "' . $project->title . '"'
            : $photographer->user->name . ' a annulé la demande pour "' . $project->title . '"';

        return [
            'type' => 'booking_request_cancelled',
            'booking_request_id' => $this->bookingRequest->id,
            'project_id' => $project->id,
            'project_title' => $project->title,
            'photographer_id' => $photographer->id,
            'photographer_name' => $photographer->user->name,
            'client_name' => $project->client->name,
            'cancelled_by' => $this->cancelledBy,
            'message' => $message,
        ];
    }
}
