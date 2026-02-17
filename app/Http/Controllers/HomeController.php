<?php

// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredTemplates = Template::where('is_featured', true)
            ->where('is_active', true)
            ->with('category')
            ->latest()
            ->take(12)
            ->get();
            
        $categories = Category::withCount('templates')
            ->orderBy('order')
            ->get();
            
        $stats = [
            'total_templates' => Template::where('is_active', true)->count(),
            'total_downloads' => Template::sum('download_count'),
            'total_categories' => Category::count(),
        ];
        
        return view('home', compact('featuredTemplates', 'categories', 'stats'));
    }
}