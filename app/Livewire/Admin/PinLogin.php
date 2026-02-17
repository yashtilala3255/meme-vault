<?php

namespace App\Livewire\Admin;

use App\Models\Admin;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PinLogin extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public int $attempts = 0;
    public bool $isLocked = false;

    public function mount(): void
    {
        // Check if already authenticated
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('pin_code')
                    ->label('Enter PIN Code')
                    ->placeholder('Enter your 6-digit PIN')
                    ->required()
                    ->numeric()
                    ->maxLength(6)
                    ->minLength(6)
                    ->disabled($this->isLocked)
                    ->autofocus()
                    ->extraInputAttributes([
                        'autocomplete' => 'off',
                        'inputmode' => 'numeric',
                        'pattern' => '[0-9]*',
                    ])
            ])
            ->statePath('data');
    }

    public function authenticate(): void
    {
        if ($this->isLocked) {
            Notification::make()
                ->danger()
                ->title('Too many attempts')
                ->body('Please wait a few minutes before trying again.')
                ->send();
            return;
        }

        $data = $this->form->getState();

        // Find admin by PIN code
        $admin = Admin::where('pin_code', $data['pin_code'])
            ->where('is_active', true)
            ->first();

        if (!$admin) {
            $this->attempts++;

            // Lock after 5 failed attempts
            if ($this->attempts >= 5) {
                $this->isLocked = true;
                
                Notification::make()
                    ->danger()
                    ->title('Account locked')
                    ->body('Too many failed attempts. Please try again in 5 minutes.')
                    ->persistent()
                    ->send();
                
                return;
            }

            Notification::make()
                ->danger()
                ->title('Invalid PIN')
                ->body('The PIN code you entered is incorrect. ' . (5 - $this->attempts) . ' attempts remaining.')
                ->send();

            $this->form->fill(['pin_code' => '']);
            return;
        }

        // Login the admin
        Auth::guard('admin')->login($admin, remember: true);

        session()->regenerate();

        Notification::make()
            ->success()
            ->title('Welcome back!')
            ->body('You have successfully logged in.')
            ->send();

        redirect()->intended(Filament::getUrl());
    }

    public function render()
    {
        return view('livewire.admin.pin-login');
    }
}