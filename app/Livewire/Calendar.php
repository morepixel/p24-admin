<?php

namespace App\Livewire;

use App\Filament\Resources\ReportResource;
use App\Models\Report;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Calendar extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    /**
     * Das ausgewählte Datum im Format Y-m-d
     */
    public string $selectedDate;

    public function mount(): void
    {
        // Starte mit dem aktuellen Datum
        $this->selectedDate = now()->format('Y-m-d');
        Log::info('Calendar mounted', [
            'selectedDate' => $this->selectedDate
        ]);
    }

    /**
     * Eine Woche zurück
     */
    public function previousWeek(): void
    {
        $oldDate = $this->selectedDate;
        $this->selectedDate = Carbon::parse($this->selectedDate)
            ->subWeek()
            ->format('Y-m-d');

        Log::info('Navigation: Previous Week', [
            'from' => $oldDate,
            'to' => $this->selectedDate
        ]);
    }

    /**
     * Eine Woche vor
     */
    public function nextWeek(): void
    {
        $oldDate = $this->selectedDate;
        $this->selectedDate = Carbon::parse($this->selectedDate)
            ->addWeek()
            ->format('Y-m-d');

        Log::info('Navigation: Next Week', [
            'from' => $oldDate,
            'to' => $this->selectedDate
        ]);
    }

    /**
     * Berechne Start- und Enddatum der aktuellen Woche
     */
    protected function getWeekRange(): array
    {
        $date = Carbon::parse($this->selectedDate);
        $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

        return [
            'start' => $weekStart,
            'end' => $weekEnd,
        ];
    }

    public function table(Table $table): Table
    {
        $range = $this->getWeekRange();
        
        Log::info('Building table', [
            'selectedDate' => $this->selectedDate,
            'weekStart' => $range['start']->format('Y-m-d'),
            'weekEnd' => $range['end']->format('Y-m-d')
        ]);

        return $table
            ->query(
                Report::query()
                    ->where(function ($query) use ($range) {
                        // Status 6 (Abmahnungen)
                        $query->where(function ($q) use ($range) {
                            $q->where('status', 6)
                              ->whereBetween('status_changed_at', [
                                  $range['start']->format('Y-m-d 00:00:00'),
                                  $range['end']->format('Y-m-d 23:59:59')
                              ]);
                        })
                        // Status 14 (Mahnungen)
                        ->orWhere(function ($q) use ($range) {
                            $q->where('status', 14)
                              ->whereBetween('status_changed_at', [
                                  $range['start']->format('Y-m-d 00:00:00'),
                                  $range['end']->format('Y-m-d 23:59:59')
                              ]);
                        })
                        // Status 12 (Erfolgreiche Abmahnungen ohne UE)
                        ->orWhere(function ($q) use ($range) {
                            $q->where('status', 12)
                              ->whereNull('uefileuploadedat')
                              ->whereBetween('status_changed_at', [
                                  $range['start']->format('Y-m-d 00:00:00'),
                                  $range['end']->format('Y-m-d 23:59:59')
                              ]);
                        });
                    })
                    ->orderBy('status_changed_at', 'desc')
            )
            ->columns(ReportResource::getTableColumns())
            ->filters([
                // Hier können später Filter hinzugefügt werden
            ])
            ->actions([
                // Hier können später Aktionen hinzugefügt werden
            ]);
    }

    public function render()
    {
        return view('livewire.calendar');
    }
}
