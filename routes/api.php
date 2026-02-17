<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Template;

// Get all memes
Route::get('/memes', function (Request $request) {
    $templates = Template::where('is_active', true)
        ->with(['category', 'tags'])
        ->when($request->category, function ($query, $category) {
            return $query->whereHas('category', fn($q) => $q->where('slug', $category));
        })
        ->when($request->search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->paginate($request->per_page ?? 24);
    
    return response()->json($templates);
});

// Get single meme
Route::get('/memes/{slug}', function ($slug) {
    $template = Template::where('slug', $slug)
        ->where('is_active', true)
        ->with(['category', 'tags'])
        ->firstOrFail();
    
    return response()->json($template);
});

// Get random meme
Route::get('/memes/random', function () {
    $template = Template::where('is_active', true)
        ->inRandomOrder()
        ->first();
    
    return response()->json($template);
});

// Get categories
Route::get('/categories', function () {
    $categories = \App\Models\Category::withCount('templates')
        ->orderBy('order')
        ->get();
    
    return response()->json($categories);
});