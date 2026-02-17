<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Template;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class Analytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.analytics';
    
    protected static ?string $navigationGroup = 'System';
    
    protected static ?int $navigationSort = 2;
    
    protected static ?string $title = 'Analytics & Insights';

    public function getViewData(): array
    {
        return [
            'totalTemplates' => Template::count(),
            'activeTemplates' => Template::where('is_active', true)->count(),
            'featuredTemplates' => Template::where('is_featured', true)->count(),
            'totalDownloads' => Template::sum('download_count'),
            'totalViews' => Template::sum('view_count'),
            'totalCategories' => Category::count(),
            'totalTags' => Tag::count(),
            
            // Top performers
            'topDownloaded' => Template::orderBy('download_count', 'desc')->take(5)->get(),
            'topViewed' => Template::orderBy('view_count', 'desc')->take(5)->get(),
            'recentTemplates' => Template::latest()->take(10)->get(),
            
            // Category stats
            'categoryStats' => Category::withCount('templates')
                ->orderBy('templates_count', 'desc')
                ->get(),
            
            // Daily stats (last 30 days)
            'dailyStats' => Template::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get(),
            
            // Engagement metrics
            'avgDownloadsPerTemplate' => round(Template::avg('download_count'), 2),
            'avgViewsPerTemplate' => round(Template::avg('view_count'), 2),
            'engagementRate' => Template::sum('download_count') > 0 
                ? round((Template::sum('download_count') / Template::sum('view_count')) * 100, 2) 
                : 0,
        ];
    }
}