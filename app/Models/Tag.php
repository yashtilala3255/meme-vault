<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];
    
    public function templates(): BelongsToMany
    {
        // ✅ Remove ->withTimestamps()
        return $this->belongsToMany(Template::class, 'template_tag');
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
}