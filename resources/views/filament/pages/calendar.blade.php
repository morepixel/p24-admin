<x-filament::page>
    <div class="space-y-6">
        <div class="flex items-center justify-between bg-white rounded-lg shadow p-4">
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-warning-100 text-warning-800">
                    Abmahnung (3-4 Wochen alt)
                </span>
                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-danger-100 text-danger-800">
                    Mahnung (4-5 Wochen alt)
                </span>
                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-success-100 text-success-800">
                    Erfolgreiche Abmahnung ohne UE
                </span>
            </div>
        </div>

        {{ $this->table }}
    </div>
</x-filament::page>
