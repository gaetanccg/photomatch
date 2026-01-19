<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Specialty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($specialty) {
            if (empty($specialty->slug)) {
                $specialty->slug = Str::slug($specialty->name);
            }
        });
    }

    public function photographers(): BelongsToMany
    {
        return $this->belongsToMany(Photographer::class, 'photographer_specialty')
            ->withPivot('experience_level')
            ->withTimestamps();
    }
}
