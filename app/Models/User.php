<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // Relations
    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function gestionnaire()
    {
        return $this->hasOne(Gestionnaire::class);
    }

    // Helpers rôles
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isGestionnaire(): bool
    {
        return $this->role === 'gestionnaire';
    }
}
