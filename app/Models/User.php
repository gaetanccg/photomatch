<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function photographer(): HasOne
    {
        return $this->hasOne(Photographer::class);
    }

    public function photoProjects(): HasMany
    {
        return $this->hasMany(PhotoProject::class, 'client_id');
    }

    public function isPhotographer(): bool
    {
        return $this->role === 'photographer';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
