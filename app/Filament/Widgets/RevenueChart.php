<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Umsatz pro Monat';
    protected static ?string $description = 'Umsatz der letzten 12 Monate (220,27€ pro Report)';

    protected int $height = 300;
    protected static ?int $sort = 2;

    protected const REVENUE_PER_REPORT = 220.27;

    protected function getData(): array
    {
        $data = DB::connection('reports')
            ->table('reports')
            ->selectRaw('DATE_FORMAT(createdAt, "%Y-%m") as date, COUNT(*) as count')
            ->whereNull('deleted_at')
            ->whereBetween('createdAt', [
                now()->subMonths(11)->startOfMonth(),
                now()->endOfMonth(),
            ])
            ->groupByRaw('DATE_FORMAT(createdAt, "%Y-%m")')
            ->orderBy('date')
            ->get();

        $chartData = collect();
        foreach ($data as $row) {
            $revenue = round($row->count * self::REVENUE_PER_REPORT, 2);
            $chartData->push([
                'date' => $row->date,
                'value' => $revenue,
                'count' => $row->count
            ]);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Umsatz',
                    'data' => $chartData->pluck('value'),
                    'borderColor' => '#6366F1',
                    'backgroundColor' => '#6366F120',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $chartData->map(fn ($item) => Carbon::createFromFormat('Y-m', $item['date'])->format('M Y')),
            'extraData' => $chartData->map(fn ($item) => [
                'count' => $item['count'],
                'revenue' => $item['value']
            ])->toArray(),
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
                        'callback' => "function(value) { 
                            return new Intl.NumberFormat('de-DE', { 
                                style: 'currency', 
                                currency: 'EUR',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(value);
                        }",
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context) {
                            const extraData = context.chart.data.extraData[context.dataIndex];
                            const revenue = new Intl.NumberFormat('de-DE', { 
                                style: 'currency', 
                                currency: 'EUR',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(extraData.revenue);
                            return [
                                revenue + ' Umsatz',
                                extraData.count + ' Reports'
                            ];
                        }",
                    ],
                ],
            ],
        ];
    }
}
