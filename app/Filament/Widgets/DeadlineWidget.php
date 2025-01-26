<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class DeadlineWidget extends BaseWidget
{
    protected static ?int $sort = -2; // Ganz oben anzeigen
    protected int $defaultPaginationPageOption = 10;
    protected static ?string $heading = 'Überfällige Vorgänge';

    // Widget auf volle Breite setzen
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Report::query()
                    ->whereNotNull('status_changed_at')
                    ->where(function (Builder $query) {
                        $query->where(function (Builder $q) {
                            $q->where('status', 'holder_inquiry_sent')
                                ->where('status_changed_at', '<=', now()->subDays(14));
                        })->orWhere(function (Builder $q) {
                            $q->where('status', 'warning_sent')
                                ->where('status_changed_at', '<=', now()->subDays(14));
                        })->orWhere(function (Builder $q) {
                            $q->where('status', 'invoice_sent')
                                ->where('status_changed_at', '<=', now()->subDays(21));
                        });
                    })
                    ->orderBy('status_changed_at', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'holder_inquiry_sent' => 'warning',
                        'warning_sent' => 'danger',
                        'invoice_sent' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status_changed_at')
                    ->label('Status seit')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('days_overdue')
                    ->label('Überfällig')
                    ->state(function (Report $record): string {
                        $deadline = match ($record->status) {
                            'holder_inquiry_sent' => 14,
                            'warning_sent' => 14,
                            'invoice_sent' => 21,
                            default => 0,
                        };
                        $daysOverdue = Carbon::parse($record->status_changed_at)
                            ->diffInDays(now()) - $deadline;
                        return $daysOverdue > 0 ? $daysOverdue . ' Tage' : '-';
                    })
                    ->color(fn (Report $record): string => 
                        Carbon::parse($record->status_changed_at)->diffInDays(now()) > 
                        match ($record->status) {
                            'holder_inquiry_sent' => 14,
                            'warning_sent' => 14,
                            'invoice_sent' => 21,
                            default => 0,
                        } ? 'danger' : 'gray'
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Bearbeiten')
                    ->url(fn (Report $record): string => route('filament.admin.resources.reports.edit', ['record' => $record]))
                    ->icon('heroicon-m-pencil-square')
                    ->color('primary'),
            ])
            ->paginated([5, 10, 25, 50])
            ->defaultSort('status_changed_at', 'asc');
    }

    protected function getTableHeading(): string
    {
        return 'Überfällige Vorgänge';
    }
}
