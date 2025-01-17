<?php

namespace App\Filament\Pages;

use App\Models\Report;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int|array
    {
        return 2;
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\ReportStats::class,
            \App\Filament\Widgets\ReportChart::class,
        ];
    }
}
