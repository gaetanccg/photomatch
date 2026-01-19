<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function client(): BelongsTo
    {
        return $this->project->client();
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
}
