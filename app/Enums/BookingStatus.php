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

    public function badgeClasses(): string
    {
        return match($this) {
            self::Pending => 'bg-yellow-100 text-yellow-800',
            self::Accepted => 'bg-green-100 text-green-800',
            self::Declined => 'bg-red-100 text-red-800',
            self::Cancelled => 'bg-gray-100 text-gray-800',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn(self $case) => [$case->value => $case->label()]
        )->toArray();
    }
}
