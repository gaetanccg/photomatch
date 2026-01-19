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
        'bio',
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
}
