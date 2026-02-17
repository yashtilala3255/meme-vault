<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class Admin extends Authenticatable implements FilamentUser
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'pin_code', // ✅ Add this
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'pin_code', // ✅ Add this
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active;
    }
    
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }
}