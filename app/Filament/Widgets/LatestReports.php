<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LatestReports extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Report::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('companyName')
                    ->label('Firmenname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plateCode1')
                    ->label('Kennzeichen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Datum')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'gray',
                        '1' => 'warning',
                        '2' => 'success',
                        '3' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '0' => 'Neu',
                        '1' => 'In Bearbeitung',
                        '2' => 'Abgeschlossen',
                        '3' => 'Storniert',
                        default => $state,
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Anzeigen')
                    ->url(fn (Report $record): string => route('filament.admin.resources.reports.edit', $record))
                    ->icon('heroicon-m-eye'),
            ]);
    }
}
