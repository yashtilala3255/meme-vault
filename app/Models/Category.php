<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Category.php
class Category extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'description', 'order'];
    
    public function templates()
    {
        return $this->hasMany(Template::class);
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
}