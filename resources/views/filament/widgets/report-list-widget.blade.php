<x-filament::widget>
    <div class="space-y-2">
        <nav class="flex space-x-2 border-b border-gray-200 dark:border-gray-700">
            <div class="flex -mb-px space-x-4 overflow-x-auto">
                @foreach ($tabs as $key => $label)
                    <button
                        wire:click="updateTab('{{ $key }}')"
                        @class([
                            'px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap transition-colors duration-200',
                            'border-primary-600 text-primary-600' => $activeTab === $key,
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== $key,
                        ])
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </nav>

        <div class="filament-tables-container rounded-xl border border-gray-300 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            {{ $this->table }}
        </div>
    </div>
</x-filament::widget>
