<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'subscription_tier',
        'premium_expires_at',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'premium_expires_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
{
    return $this->hasOne(Subscription::class)
        ->where('status', 'active')
        ->where('expires_at', '>', now())
        ->latest();
}

    public function downloads()
    {
        return $this->hasMany(UserDownload::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function isPremium(): bool
{
    if (!$this->subscription_tier) return false;
    
    return in_array($this->subscription_tier, ['premium', 'business'])
        && ($this->premium_expires_at === null || $this->premium_expires_at->isFuture());
}

    public function isBusiness(): bool
{
    if (!$this->subscription_tier) return false;
    
    return $this->subscription_tier === 'business'
        && ($this->premium_expires_at === null || $this->premium_expires_at->isFuture());
}

    public function canAccessTemplate(Template $template): bool
    {
        if (!$template->is_premium) {
            return true;
        }

        if ($template->premium_tier === 'premium' && $this->isPremium()) {
            return true;
        }

        if ($template->premium_tier === 'business' && $this->isBusiness()) {
            return true;
        }

        return false;
    }

    public function canRemoveWatermark(): bool
    {
        return $this->isPremium();
    }

   public function hasPrioritySupport(): bool
{
    return $this->isPremium();
}
}