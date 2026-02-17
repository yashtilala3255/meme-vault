<?php

namespace App\Filament\Widgets;

use App\Models\Template;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PopularTemplates extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Template::query()
                    ->where('is_active', true)
                    ->orderBy('download_count', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex()
                    ->badge()
                    ->color(fn ($rowLoop) => match(true) {
                        $rowLoop->index === 0 => 'warning',
                        $rowLoop->index === 1 => 'gray',
                        $rowLoop->index === 2 => 'orange',
                        default => 'primary',
                    }),
                    
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Preview')
                    ->disk('public')
                    ->square()
                    ->width(60)
                    ->height(60),
                    
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->weight('bold')
                    ->limit(40),
                    
                Tables\Columns\TextColumn::make('category.name')
                    ->badge()
                    ->color('primary'),
                    
                Tables\Columns\TextColumn::make('download_count')
                    ->label('Downloads')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($state) => number_format($state))
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('view_count')
                    ->label('Views')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => number_format($state))
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