<!-- resources/views/filament/pages/bulk-import.blade.php -->
<x-filament-panels::page>
    <form wire:submit="import">
        {{ $this->form }}
        
        <div class="mt-6">
            <x-filament::button type="submit">
                Import Templates
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>