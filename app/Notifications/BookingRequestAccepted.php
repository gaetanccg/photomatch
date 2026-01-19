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
        $photographer = $this->bookingRequest->photographer;
        $photographerUser = $photographer->user;

        return (new MailMessage)
            ->subject('Bonne nouvelle ! Votre demande a été acceptée')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Excellente nouvelle ! Votre demande pour le projet "' . $project->title . '" a été acceptée.')
            ->line('**Photographe :** ' . $photographerUser->name)
            ->when($photographerUser->phone, function ($message) use ($photographerUser) {
                return $message->line('**Téléphone :** ' . $photographerUser->phone);
            })
            ->when($this->bookingRequest->proposed_price, function ($message) {
                return $message->line('**Tarif convenu :** ' . number_format($this->bookingRequest->proposed_price, 0, ',', ' ') . '€');
            })
            ->when($this->bookingRequest->photographer_response, function ($message) {
                return $message->line('**Message du photographe :** ' . $this->bookingRequest->photographer_response);
            })
            ->action('Voir les détails', url('/client/requests/' . $this->bookingRequest->id))
            ->line('Vous pouvez maintenant prendre contact avec le photographe pour finaliser les détails de votre projet.')
            ->salutation('L\'équipe PhotoMatch');
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
