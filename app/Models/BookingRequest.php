<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BookingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'photographer_id',
        'status',
        'client_message',
        'photographer_response',
        'proposed_price',
        'sent_at',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'responded_at' => 'datetime',
            'proposed_price' => 'decimal:2',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(PhotoProject::class, 'project_id');
    }

    public function photographer(): BelongsTo
    {
        return $this->belongsTo(Photographer::class);
    }

    /**
     * Get the client (owner of the project) through the project relationship.
     * Note: Use project.client eager loading instead of this accessor for queries.
     */
    public function getClientAttribute(): ?User
    {
        return $this->project?->client;
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted(Builder $query): Builder
    {
        return $query->where('status', 'accepted');
    }

    public function scopeDeclined(Builder $query): Builder
    {
        return $query->where('status', 'declined');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function hasReview(): bool
    {
        return $this->review()->exists();
    }

    public function canBeReviewed(): bool
    {
        return $this->status === 'accepted' && !$this->hasReview();
    }
}
