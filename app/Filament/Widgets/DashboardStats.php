<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        $query = Report::query();

        if ($user->isAssistant()) {
            $query->whereIn('status', [0, 1]); // Nur neue und in Bearbeitung
        }

        return [
            Stat::make('Neue Vorgänge', $query->where('status', 0)->count())
                ->description('Noch nicht bearbeitet')
                ->descriptionIcon('heroicon-m-clock')
                ->color('gray'),
            
            Stat::make('In Bearbeitung', $query->where('status', 1)->count())
                ->description('Aktiv in Bearbeitung')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),
            
            Stat::make('Abgeschlossen', $query->where('status', 2)->count())
                ->description('Erfolgreich abgeschlossen')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
