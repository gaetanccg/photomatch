<?php

namespace App\Models;

use App\Enums\ProjectType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhotoProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'title',
        'description',
        'project_type',
        'event_date',
        'date_start',
        'date_end',
        'location',
        'latitude',
        'longitude',
        'budget_min',
        'budget_max',
        'estimated_duration',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'project_type' => ProjectType::class,
            'event_date' => 'date',
            'date_start' => 'date',
            'date_end' => 'date',
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function bookingRequests(): HasMany
    {
        return $this->hasMany(BookingRequest::class, 'project_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeByType(Builder $query, string|ProjectType $type): Builder
    {
        $value = $type instanceof ProjectType ? $type->value : $type;
        return $query->where('project_type', $value);
    }

    public function scopeInBudgetRange(Builder $query, ?float $min, ?float $max): Builder
    {
        if ($min !== null) {
            $query->where('budget_min', '>=', $min);
        }
        if ($max !== null) {
            $query->where('budget_max', '<=', $max);
        }
        return $query;
    }
}
