<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Artisan;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static string $view = 'filament.pages.dashboard';
    
    public function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
        ];
    }
    
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\PopularTemplates::class,
            \App\Filament\Widgets\RecentTemplates::class,
        ];
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Action::make('fetch_memes')
                ->label('Fetch New Memes')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Fetch Meme Templates')
                ->modalDescription('This will fetch new meme templates from Imgflip API.')
                ->modalSubmitActionLabel('Fetch Memes')
                ->action(function () {
                    Artisan::call('memes:fetch-imgflip', ['--limit' => 20]);
                    
                    $this->notify('success', 'Memes fetched successfully!');
                }),
                
            Action::make('view_site')
                ->label('View Website')
                ->icon('heroicon-o-globe-alt')
                ->color('info')
                ->url('/', shouldOpenInNewTab: true),
        ];
    }
}