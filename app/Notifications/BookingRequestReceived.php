<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRequestReceived extends Notification implements ShouldQueue
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
        $client = $project->client;

        return (new MailMessage)
            ->subject('Nouvelle demande de réservation - ' . $project->title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Vous avez reçu une nouvelle demande de réservation pour le projet "' . $project->title . '".')
            ->line('**Client :** ' . $client->name)
            ->line('**Type de projet :** ' . $this->getProjectTypeLabel($project->project_type))
            ->line('**Localisation :** ' . $project->location)
            ->when($this->bookingRequest->client_message, function ($message) {
                return $message->line('**Message du client :** ' . $this->bookingRequest->client_message);
            })
            ->when($this->bookingRequest->proposed_price, function ($message) {
                return $message->line('**Prix proposé :** ' . number_format($this->bookingRequest->proposed_price, 0, ',', ' ') . '€');
            })
            ->action('Voir la demande', url('/photographer/requests/' . $this->bookingRequest->id))
            ->line('Connectez-vous pour accepter ou décliner cette demande.')
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
        $client = $project->client;

        return [
            'type' => 'booking_request_received',
            'booking_request_id' => $this->bookingRequest->id,
            'project_id' => $project->id,
            'project_title' => $project->title,
            'client_id' => $client->id,
            'client_name' => $client->name,
            'message' => 'Nouvelle demande de ' . $client->name . ' pour "' . $project->title . '"',
        ];
    }

    private function getProjectTypeLabel(string $type): string
    {
        return match ($type) {
            'event' => 'Événement',
            'product' => 'Produit',
            'real_estate' => 'Immobilier',
            'corporate' => 'Corporate',
            'portrait' => 'Portrait',
            'other' => 'Autre',
            default => $type,
        };
    }
}
