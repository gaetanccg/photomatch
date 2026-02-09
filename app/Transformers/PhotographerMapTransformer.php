<?php

namespace App\Transformers;

use App\Models\Photographer;
use Illuminate\Support\Collection;

class PhotographerMapTransformer
{
    public function transformCollection(Collection $photographers): Collection
    {
        return $photographers
            ->filter(fn ($p) => $p->latitude && $p->longitude)
            ->map(fn ($p) => $this->transform($p))
            ->values();
    }

    public function transform(Photographer $photographer): array
    {
        return [
            'id' => $photographer->id,
            'name' => $photographer->user->name,
            'location' => $photographer->location,
            'lat' => (float) $photographer->latitude,
            'lng' => (float) $photographer->longitude,
            'rating' => $photographer->rating,
            'hourly_rate' => $photographer->hourly_rate,
            'photo' => $photographer->portfolioImages->first()?->url
                ?? $photographer->user->profile_photo_url
                ?? null,
            'url' => route('photographers.show', $photographer),
        ];
    }
}
