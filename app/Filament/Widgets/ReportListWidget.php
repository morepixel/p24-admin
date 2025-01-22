<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class ReportListWidget extends BaseWidget
{
    protected static ?string $heading = 'Wochenplaner';
    
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public ?string $activeTab = 'new_reports';

    protected static string $view = 'filament.widgets.report-list-widget';

    protected function getViewData(): array
    {
        return [
            'tabs' => [
                'new_reports' => 'Neue Vorgänge',
                'warning_created' => 'Abmahnung erzeugt',
                'holder_query_sent' => 'Halterabfrage verschickt (14 Tage)',
                'warning_sent' => 'Abmahnung verschickt (14 Tage)',
                'reminder_sent' => 'Mahnung verschickt (21 Tage)',
                'approval_requested' => 'Bitte um Freigabe',
                'approved' => 'Freigegeben',
            ],
            'activeTab' => $this->activeTab,
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = Report::query();
                
        return match ($this->activeTab) {
            'new_reports' => $query->whereIn('status', [Report::STATUS_IN_PROGRESS, Report::STATUS_COMPLETED]),
            'warning_created' => $query->where('status', Report::STATUS_WARNING_CREATED),
            'holder_query_sent' => $query->where('status', Report::STATUS_HOLDER_QUERY_SENT)
                ->where('updatedAt', '>=', Carbon::now()->subDays(14)),
            'warning_sent' => $query->where('status', Report::STATUS_WARNING_SENT)
                ->where('updatedAt', '>=', Carbon::now()->subDays(14)),
            'reminder_sent' => $query->where('status', Report::STATUS_REMINDER_SENT)
                ->where('updatedAt', '>=', Carbon::now()->subDays(21)),
            'approval_requested' => $query->where('lawyerapprovalstatus', 1),
            'approved' => $query->where('lawyerapprovalstatus', 2),
            default => $query,
        };
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Datum')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plateCode1')
                    ->label('Kennzeichen')
                    ->formatStateUsing(fn ($record) => "{$record->plateCode1}-{$record->plateCode2}-{$record->plateCode3}")
                    ->searchable(),
                Tables\Columns\TextColumn::make('halterName')
                    ->label('Halter')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($record): string => match ($record->status) {
                        Report::STATUS_NEW => 'gray',
                        Report::STATUS_IN_PROGRESS, 
                        Report::STATUS_COMPLETED,
                        Report::STATUS_HOLDER_QUERY_SENT,
                        Report::STATUS_WARNING_CREATED => 'warning',
                        Report::STATUS_WARNING_SENT,
                        Report::STATUS_REMINDER_SENT => 'success',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('date', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
    }

    public function updateTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetTable();
    }
}
