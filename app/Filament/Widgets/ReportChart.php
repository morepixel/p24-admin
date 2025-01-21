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
    protected static ?string $heading = 'Vorgänge pro Monat';

    protected function getData(): array
    {
        $data = DB::connection('reports')
            ->table('reports')
            ->selectRaw('DATE_FORMAT(createdAt, "%Y-%m") as date, COUNT(*) as aggregate')
            ->whereNull('deleted_at')
            ->whereBetween('createdAt', [
                now()->startOfYear(),
                now()->endOfYear(),
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
                    'label' => 'Vorgänge',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => Carbon::createFromFormat('Y-m', $value->date)->format('M')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
