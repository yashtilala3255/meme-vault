<?php

// app/Filament/Pages/BulkImport.php
namespace App\Filament\Pages;

use App\Models\Template;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class BulkImport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'filament.pages.bulk-import';
    
    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->label('Default Category')
                    ->options(Category::pluck('name', 'id'))
                    ->required(),
                
                Forms\Components\FileUpload::make('images')
                    ->label('Upload Multiple Templates')
                    ->image()
                    ->multiple()
                    ->directory('templates')
                    ->maxFiles(50)
                    ->helperText('Upload up to 50 images at once'),
                
                Forms\Components\Toggle::make('is_active')
                    ->label('Activate Immediately')
                    ->default(true),
                
                Forms\Components\Toggle::make('is_featured')
                    ->label('Mark as Featured')
                    ->default(false),
            ])
            ->statePath('data');
    }

    public function import(): void
    {
        $data = $this->form->getState();
        
        $imported = 0;
        foreach ($data['images'] as $imagePath) {
            $filename = pathinfo($imagePath, PATHINFO_FILENAME);
            $name = Str::title(str_replace(['-', '_'], ' ', $filename));
            
            Template::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'category_id' => $data['category_id'],
                'image_path' => $imagePath,
                'width' => 1200,
                'height' => 1200,
                'is_active' => $data['is_active'],
                'is_featured' => $data['is_featured'],
            ]);
            
            $imported++;
        }
        
        Notification::make()
            ->success()
            ->title("Successfully imported {$imported} templates!")
            ->send();
        
        $this->form->fill();
    }
}