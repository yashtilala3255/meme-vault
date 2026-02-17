<?php

namespace App\Filament\Widgets;

use App\Models\Template;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTemplates extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Template::query()->latest()->limit(10)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Preview')
                    ->disk('public')
                    ->square()
                    ->width(60)
                    ->height(60),
                    
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->weight('bold')
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('category.name')
                    ->badge()
                    ->color('primary'),
                    
                Tables\Columns\TextColumn::make('download_count')
                    ->label('Downloads')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($state) => number_format($state)),
                    
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                // ✅ Changed from ViewAction to EditAction
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->tooltip('Edit Template')
                    ->url(fn (Template $record): string => route('filament.admin.resources.templates.edit', ['record' => $record])),
                    
                Tables\Actions\Action::make('view_site')
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->tooltip('View on Website')
                    ->color('info')
                    ->url(fn (Template $record): string => route('templates.show', $record))
                    ->openUrlInNewTab(),
            ]);
    }
}