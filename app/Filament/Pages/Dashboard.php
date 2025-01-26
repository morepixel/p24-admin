<?php

namespace App\Filament\Pages;

use App\Models\Report;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\ReportStats;
use App\Filament\Widgets\ReportChart;
use App\Filament\Widgets\ReportListWidget;
use App\Filament\Widgets\RevenueChart;
use App\Filament\Widgets\DeadlineWidget;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'lg' => 3,
        ];
    }

    public function getWidgets(): array
    {
        return [
            DeadlineWidget::class,
            ReportStats::class,
            ReportChart::class,
            RevenueChart::class,
            ReportListWidget::class,
        ];
    }
}
