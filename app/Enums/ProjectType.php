<?php

namespace App\Enums;

enum ProjectType: string
{
    case Event = 'event';
    case Product = 'product';
    case RealEstate = 'real_estate';
    case Corporate = 'corporate';
    case Portrait = 'portrait';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Event => 'Événement',
            self::Product => 'Produit',
            self::RealEstate => 'Immobilier',
            self::Corporate => 'Corporate',
            self::Portrait => 'Portrait',
            self::Other => 'Autre',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn (self $case) => [$case->value => $case->label()]
        )->toArray();
    }
}
