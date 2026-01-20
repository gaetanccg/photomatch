<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRequestAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public BookingRequest $bookingRequest
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
            ->subject('Bonne nouvelle ! Votre demande a été acceptée')
            ->view('emails.booking-request-accepted', [
                'bookingRequest' => $this->bookingRequest,
                'photographer' => $this->bookingRequest->photographer,
                'client' => $project->client,
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

        return [
            'type' => 'booking_request_accepted',
            'booking_request_id' => $this->bookingRequest->id,
            'project_id' => $project->id,
            'project_title' => $project->title,
            'photographer_id' => $photographer->id,
            'photographer_name' => $photographer->user->name,
            'message' => $photographer->user->name . ' a accepté votre demande pour "' . $project->title . '"',
        ];
    }
}
