<?php

// app/Filament/Pages/Settings.php
namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Settings';
    protected static string $view = 'filament.pages.settings';
    
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_name' => Setting::get('site_name', 'Meme Hub'),
            'site_description' => Setting::get('site_description', 'Create memes in seconds'),
            'templates_per_page' => Setting::get('templates_per_page', 24),
            'allow_downloads' => Setting::get('allow_downloads', true),
            'watermark_enabled' => Setting::get('watermark_enabled', false),
            'watermark_text' => Setting::get('watermark_text', ''),
            'ga_tracking_id' => Setting::get('ga_tracking_id', ''),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Settings')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->label('Site Name')
                            ->required(),
                        
                        Forms\Components\Textarea::make('site_description')
                            ->label('Site Description')
                            ->rows(3),
                        
                        Forms\Components\TextInput::make('templates_per_page')
                            ->label('Templates Per Page')
                            ->numeric()
                            ->default(24),
                    ]),
                
                Forms\Components\Section::make('Download Settings')
                    ->schema([
                        Forms\Components\Toggle::make('allow_downloads')
                            ->label('Allow Downloads')
                            ->helperText('Enable/disable template downloads'),
                        
                        Forms\Components\Toggle::make('watermark_enabled')
                            ->label('Add Watermark')
                            ->live()
                            ->helperText('Add watermark to downloaded memes'),
                        
                        Forms\Components\TextInput::make('watermark_text')
                            ->label('Watermark Text')
                            ->visible(fn ($get) => $get('watermark_enabled'))
                            ->placeholder('e.g., MemeHub.com'),
                    ]),
                
                Forms\Components\Section::make('Analytics')
                    ->schema([
                        Forms\Components\TextInput::make('ga_tracking_id')
                            ->label('Google Analytics ID')
                            ->placeholder('G-XXXXXXXXXX')
                            ->helperText('Optional: Add Google Analytics tracking'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        foreach ($data as $key => $value) {
            $type = is_bool($value) ? 'boolean' : 'text';
            Setting::set($key, $value, $type);
        }
        
        Notification::make()
            ->success()
            ->title('Settings saved successfully')
            ->send();
    }
}