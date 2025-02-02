<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReportStats extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        return [
            Stat::make('Gesamt Vorgänge', Report::count())
                ->description('Alle Vorgänge')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),
            
            Stat::make('Neue Vorgänge', Report::where('status', 0)->count())
                ->description('Noch nicht bearbeitet')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            
            Stat::make('Abgeschlossene Vorgänge', Report::where('status', 2)->count())
                ->description('Erfolgreich bearbeitet')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
