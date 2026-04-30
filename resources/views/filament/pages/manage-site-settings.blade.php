<x-filament-panels::page>
    <form wire:submit="save">
        <div class="mb-6 flex justify-start">
            <x-filament::button type="submit">
                Save Settings
            </x-filament::button>
        </div>

        {{ $this->form }}

        <div class="mt-6 flex justify-start">
            <x-filament::button type="submit">
                Save Settings
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
