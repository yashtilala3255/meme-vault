<?php

namespace App\Filament\Resources\TemplateResource\Pages;

use App\Filament\Resources\TemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

class ViewTemplate extends ViewRecord
{
    protected static string $resource = TemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Template Preview')
                    ->schema([
                        Components\ImageEntry::make('image_path')
                            ->label('Template Image')
                            ->disk('public')
                            ->height(400)
                            ->columnSpanFull(),
                    ]),
                    
                Components\Section::make('Template Information')
                    ->schema([
                        Components\TextEntry::make('name')
                            ->size('lg')
                            ->weight('bold'),
                            
                        Components\TextEntry::make('slug')
                            ->icon('heroicon-o-link')
                            ->copyable(),
                            
                        Components\TextEntry::make('category.name')
                            ->badge()
                            ->color('primary'),
                            
                        Components\TextEntry::make('tags.name')
                            ->badge()
                            ->separator(',')
                            ->color('info'),
                    ])->columns(2),
                    
                Components\Section::make('Statistics')
                    ->schema([
                        Components\TextEntry::make('download_count')
                            ->label('Total Downloads')
                            ->icon('heroicon-o-arrow-down-tray')
                            ->badge()
                            ->color('success')
                            ->formatStateUsing(fn ($state) => number_format($state)),
                            
                        Components\TextEntry::make('view_count')
                            ->label('Total Views')
                            ->icon('heroicon-o-eye')
                            ->badge()
                            ->color('info')
                            ->formatStateUsing(fn ($state) => number_format($state)),
                            
                        Components\TextEntry::make('width')
                            ->suffix('px')
                            ->icon('heroicon-o-arrows-pointing-out'),
                            
                        Components\TextEntry::make('height')
                            ->suffix('px')
                            ->icon('heroicon-o-arrows-pointing-out'),
                    ])->columns(4),
                    
                Components\Section::make('Status')
                    ->schema([
                        Components\IconEntry::make('is_featured')
                            ->label('Featured')
                            ->boolean()
                            ->trueIcon('heroicon-o-star')
                            ->falseIcon('heroicon-o-star')
                            ->trueColor('warning')
                            ->falseColor('gray'),
                            
                        Components\IconEntry::make('is_active')
                            ->label('Active')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                            
                        Components\TextEntry::make('created_at')
                            ->label('Created')
                            ->dateTime()
                            ->since(),
                            
                        Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime()
                            ->since(),
                    ])->columns(4),
            ]);
    }
}