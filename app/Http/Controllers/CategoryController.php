<?php

// app/Http/Controllers/CategoryController.php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Category $category, Request $request)
    {
        $query = $category->templates()->where('is_active', true)->with('tags');
        
        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('download_count', 'desc');
                break;
            case 'trending':
                $query->orderBy('view_count', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $templates = $query->paginate(24);
        
        return view('categories.show', compact('category', 'templates'));
    }
}
