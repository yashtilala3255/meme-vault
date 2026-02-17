<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Template extends Model
{
    protected $fillable = [
        'name', 
        'slug', 
        'category_id', 
        'image_path', 
        'width', 
        'height', 
        'text_areas', 
        'download_count', 
        'view_count', 
        'is_featured', 
         'is_premium',      // ✅ Add
        'premium_tier',    // ✅ Add
        'is_active'
    ];
    
    protected $casts = [
        'text_areas' => 'array',
        'is_featured' => 'boolean',
        'is_premium' => 'boolean',  // ✅ Add
        'is_active' => 'boolean',
    ];
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    public function requiresPremium(): bool
    {
        return $this->is_premium && $this->premium_tier !== 'free';
    }

    public function premiumInfo()
    {
        return $this->hasOne(PremiumTemplate::class);
    }
    
    public function tags(): BelongsToMany
    {
        // ✅ Remove ->withTimestamps()
        return $this->belongsToMany(Tag::class, 'template_tag');
    }
    public function getImageUrlAttribute(): string
{
    // If image_path is set, serve from local storage
    if ($this->image_path) {
        return \Illuminate\Support\Facades\Storage::url($this->image_path);
    }

    // Fallback to external URL if stored
    return $this->attributes['image_url'] ?? '';
}
    
    public function incrementDownloads()
    {
        $this->increment('download_count');
    }
    
    public function incrementViews()
    {
        $this->increment('view_count');
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
}