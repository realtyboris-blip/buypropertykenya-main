<x-filament-panels::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}
        
        <div class="mt-6 flex items-center justify-end gap-3">
            <button type="submit" class="filament-button filament-button-primary inline-flex items-center justify-center gap-1 rounded-lg px-4 py-2 text-sm font-semibold transition duration-200 shadow-sm bg-primary-600 text-white hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16V4H4z M4 4l16 16 M20 4l-16 16"></path>
                </svg>
                Save Settings
            </button>
        </div>
    </form>
</x-filament-panels::page>