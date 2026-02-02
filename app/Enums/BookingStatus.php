<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Declined = 'declined';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'En attente',
            self::Accepted => 'Acceptée',
            self::Declined => 'Déclinée',
            self::Cancelled => 'Annulée',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'yellow',
            self::Accepted => 'green',
            self::Declined => 'red',
            self::Cancelled => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn(self $case) => [$case->value => $case->label()]
        )->toArray();
    }
}
