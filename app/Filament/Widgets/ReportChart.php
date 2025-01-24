<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportChart extends ChartWidget
{
    protected static ?string $heading = 'Reports pro Monat';
    protected static ?string $description = 'Anzahl der Reports in den letzten 12 Monaten';

    protected int $height = 300;

    protected function getData(): array
    {
        $data = DB::connection('reports')
            ->table('reports')
            ->selectRaw('DATE_FORMAT(createdAt, "%Y-%m") as date, COUNT(*) as aggregate')
            ->whereNull('deleted_at')
            ->whereBetween('createdAt', [
                now()->subMonths(11)->startOfMonth(),
                now()->endOfMonth(),
            ])
            ->groupByRaw('DATE_FORMAT(createdAt, "%Y-%m")')
            ->orderBy('date')
            ->get()
            ->map(function ($row) {
                return new TrendValue(
                    date: $row->date,
                    aggregate: $row->aggregate
                );
            });

        return [
            'datasets' => [
                [
                    'label' => 'Reports',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#10B981', // Grün
                    'backgroundColor' => '#10B98120', // Transparentes Grün
                    'fill' => true,
                    'tension' => 0.3, // Leicht geschwungene Linien
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => Carbon::createFromFormat('Y-m', $value->date)->format('M Y')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
