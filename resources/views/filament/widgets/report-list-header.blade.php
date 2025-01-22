<nav class="flex space-x-2 border-b border-gray-200 dark:border-gray-700">
    <div class="flex -mb-px space-x-4 overflow-x-auto">
        @foreach ($getOptions() as $value => $label)
            <button
                wire:click="setFilterFormDataField('{{ $getName() }}', '{{ $value }}')"
                @class([
                    'px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap transition-colors duration-200',
                    'border-primary-600 text-primary-600' => $getState() === $value,
                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $getState() !== $value,
                ])
            >
                {{ $label }}
            </button>
        @endforeach
    </div>
</nav>
