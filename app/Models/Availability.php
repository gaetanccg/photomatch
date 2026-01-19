<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'photographer_id',
        'date',
        'is_available',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_available' => 'boolean',
        ];
    }

    public function photographer(): BelongsTo
    {
        return $this->belongsTo(Photographer::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    public function scopeForDate(Builder $query, $date): Builder
    {
        return $query->whereDate('date', $date);
    }
}
