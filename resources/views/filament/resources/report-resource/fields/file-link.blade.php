@php
    $state = $getState();
@endphp

@if ($state)
    <div class="flex items-center gap-2">
        <x-filament::link
            :href="Storage::url($state)"
            target="_blank"
            class="inline-flex items-center justify-center gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset filament-link text-sm px-4 py-2 bg-primary-600 text-white shadow hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700"
        >
            <x-heroicon-s-document class="w-5 h-5" />
            {{ basename($state) }}
        </x-filament::link>
    </div>
@else
    <div class="text-gray-500 italic">Keine Datei vorhanden</div>
@endif
