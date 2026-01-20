<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Photographer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'siret',
        'bio',
        'keywords',
        'experience_years',
        'portfolio_url',
        'hourly_rate',
        'daily_rate',
        'is_verified',
        'rating',
        'total_missions',
        'location',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'hourly_rate' => 'decimal:2',
            'daily_rate' => 'decimal:2',
            'rating' => 'decimal:1',
            'is_verified' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function specialties(): BelongsToMany
    {
        return $this->belongsToMany(Specialty::class, 'photographer_specialty')
            ->withPivot('experience_level')
            ->withTimestamps();
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class);
    }

    public function bookingRequests(): HasMany
    {
        return $this->hasMany(BookingRequest::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function portfolioImages(): HasMany
    {
        return $this->hasMany(PortfolioImage::class);
    }

    public function completedMissions(): HasMany
    {
        return $this->hasMany(BookingRequest::class)->where('status', 'accepted');
    }

    public function updateRating(): void
    {
        $avgRating = $this->reviews()->avg('rating');
        $this->update([
            'rating' => $avgRating,
            'total_missions' => $this->bookingRequests()->accepted()->count(),
        ]);
    }

    public function getRatingAttribute($value): ?string
    {
        return $value ? number_format($value, 1) : null;
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }

    public function scopeWithSpecialty(Builder $query, int $specialtyId): Builder
    {
        return $query->whereHas('specialties', function ($q) use ($specialtyId) {
            $q->where('specialties.id', $specialtyId);
        });
    }

    public function scopeInPriceRange(Builder $query, ?float $min, ?float $max): Builder
    {
        if ($min !== null) {
            $query->where('hourly_rate', '>=', $min);
        }
        if ($max !== null) {
            $query->where('hourly_rate', '<=', $max);
        }
        return $query;
    }

    public function scopeNearLocation(Builder $query, string $location): Builder
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    public function scopeAvailableOn(Builder $query, string $date): Builder
    {
        return $query->whereHas('availabilities', fn($aq) =>
            $aq->where('date', $date)->where('is_available', true)
        );
    }

    public function scopeMinRating(Builder $query, float $rating): Builder
    {
        return $query->where('rating', '>=', $rating);
    }

    public function scopeWithMinExperience(Builder $query, int $years): Builder
    {
        return $query->where('experience_years', '>=', $years);
    }

    public function scopeTopRated(Builder $query, int $limit = 10): Builder
    {
        return $query->whereNotNull('rating')
            ->where('rating', '>', 0)
            ->orderByDesc('rating')
            ->limit($limit);
    }
}
