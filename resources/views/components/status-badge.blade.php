@props(['status'])

@php
    // Handle both string status and BookingStatus enum
    if ($status instanceof \App\Enums\BookingStatus) {
        $statusValue = $status->value;
        $label = $status->label();
        $classes = $status->badgeClasses();
    } else {
        $statusValue = $status;
        $classes = match($statusValue) {
            'draft' => 'bg-gray-100 text-gray-800',
            'published' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'accepted' => 'bg-green-100 text-green-800',
            'declined' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };

        $labels = [
            'draft' => 'Brouillon',
            'published' => 'Publié',
            'in_progress' => 'En cours',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
            'pending' => 'En attente',
            'accepted' => 'Acceptée',
            'declined' => 'Refusée',
        ];
        $label = $labels[$statusValue] ?? ucfirst($statusValue);
    }
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ $label }}
</span>
