<?php

namespace App\Filament\Pages;

use App\Models\Report;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\ReportStats;
use App\Filament\Widgets\ReportChart;
use App\Filament\Widgets\ReportListWidget;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int|array
    {
        return 2;
    }

    public function getWidgets(): array
    {
        return [
            ReportStats::class,
            ReportChart::class,
            ReportListWidget::class,
        ];
    }
}
