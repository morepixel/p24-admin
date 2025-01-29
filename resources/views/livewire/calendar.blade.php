<div class="space-y-6">
    {{-- Kalenderwochen-Navigation --}}
    <div class="flex items-center justify-between bg-white rounded-lg shadow p-4">
        <div class="flex items-center space-x-4">
            <button wire:click="previousWeek" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <span class="sr-only">Vorherige Woche</span>
                &larr; Vorherige Woche
            </button>
            
            <div class="text-lg font-medium text-gray-900">
                @php
                    $date = \Carbon\Carbon::parse($selectedDate);
                    $weekStart = $date->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
                    $weekEnd = $weekStart->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
                @endphp
                
                Kalenderwoche {{ $date->week }}
                <span class="text-sm text-gray-500">
                    ({{ $weekStart->format('d.m.Y') }} - {{ $weekEnd->format('d.m.Y') }})
                </span>
            </div>
            
            <button wire:click="nextWeek" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <span class="sr-only">Nächste Woche</span>
                Nächste Woche &rarr;
            </button>

            <input 
                type="date" 
                wire:model="selectedDate"
                class="px-4 py-2 text-sm border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
            >
        </div>

        <div class="flex items-center space-x-2">
            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-info-100 text-info-800">
                Halterabfrage
            </span>
            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-warning-100 text-warning-800">
                Abmahnung
            </span>
            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-danger-100 text-danger-800">
                Mahnung
            </span>
        </div>
    </div>

    {{-- Tabelle --}}
    {{ $this->table }}
</div>
