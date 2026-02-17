<?php

// app/Filament/Widgets/StatsOverview.php
namespace App\Filament\Widgets;

use App\Models\Template;
use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Templates', Template::count())
                ->description('Active: ' . Template::where('is_active', true)->count())
                ->descriptionIcon('heroicon-m-photo')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
            
            Stat::make('Total Downloads', number_format(Template::sum('download_count')))
                ->description('Last 30 days')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
            
            Stat::make('Total Views', number_format(Template::sum('view_count')))
                ->description('All time')
                ->descriptionIcon('heroicon-m-eye')
                ->color('warning'),
            
            Stat::make('Categories', Category::count())
                ->description('Active categories')
                ->descriptionIcon('heroicon-m-folder')
                ->color('info'),
        ];
    }
}