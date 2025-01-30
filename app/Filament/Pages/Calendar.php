<?php

namespace App\Filament\Pages;

use App\Models\Report;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\Action;
use App\Filament\Resources\ReportResource;
use App\Filament\Resources\HolderInquirySentReportResource;
use App\Filament\Resources\HolderInquiryReceivedReportResource;
use App\Filament\Resources\InProgressReportResource;
use App\Filament\Resources\CompletedReportResource;
use App\Filament\Resources\CanceledReportResource;

class Calendar extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Kalender';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'Kalender';
    protected static string $view = 'filament.pages.calendar';

    public function table(Table $table): Table
    {
        // In der Entwicklungsumgebung verwenden wir Dezember 2024 als Basis
        $now = app()->environment('production') 
            ? Carbon::now()
            : Carbon::create(2024, 12, 15, 12, 0, 0);
        
        // Für Abmahnungen: 3-4 Wochen alt
        $startAbmahnung = $now->copy()->subWeeks(4);  // 4 Wochen alt
        $endAbmahnung = $now->copy()->subWeeks(3);    // 3 Wochen alt
        
        // Für Mahnungen: 4-5 Wochen alt
        $startMahnung = $now->copy()->subWeeks(5);    // 5 Wochen alt
        $endMahnung = $now->copy()->subWeeks(4);      // 4 Wochen alt

        Log::info('Calendar date ranges', [
            'environment' => app()->environment(),
            'now' => $now->format('Y-m-d H:i:s'),
            'abmahnung_start' => $startAbmahnung->format('Y-m-d H:i:s'),
            'abmahnung_end' => $endAbmahnung->format('Y-m-d H:i:s'),
            'mahnung_start' => $startMahnung->format('Y-m-d H:i:s'),
            'mahnung_end' => $endMahnung->format('Y-m-d H:i:s'),
        ]);

        $query = Report::query()
            ->select([
                'id',
                'plateCode1',
                'plateCode2',
                'plateCode3',
                'firstname',
                'lastname',
                'status',
                'status_changed_at',
                'createdAt',
                'updatedAt',
                'uefileuploadedat',
                'warning_sent_at',
                'reminder_due_at',
                'reminder_sent_at'
            ])
            ->where(function ($query) use ($startAbmahnung, $endAbmahnung, $startMahnung, $endMahnung, $now) {
                // Abmahnungen zwischen 3 und 4 Wochen alt
                $query->where(function ($q) use ($startAbmahnung, $endAbmahnung) {
                    $q->where('status', 6)
                      ->where('status_changed_at', '>', $startAbmahnung)
                      ->where('status_changed_at', '<=', $endAbmahnung);
                })
                // Mahnungen zwischen 4 und 5 Wochen alt
                ->orWhere(function ($q) use ($startMahnung, $endMahnung) {
                    $q->where('status', 14)
                      ->where('status_changed_at', '>', $startMahnung)
                      ->where('status_changed_at', '<=', $endMahnung);
                })
                // Erfolgreiche Abmahnungen ohne UE (nur aus den letzten 4 Wochen)
                ->orWhere(function ($q) use ($now, $startAbmahnung) {
                    $q->where('status', 12)
                      ->where('status_changed_at', '<=', $now)
                      ->where('status_changed_at', '>', $startAbmahnung)
                      ->whereNull('uefileuploadedat');
                });
            });

        // Log the SQL query
        Log::info('Calendar SQL', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        // Log found reports
        $reports = $query->get();
        Log::info('Found reports', [
            'count' => $reports->count(),
            'reports' => $reports->map(function($report) {
                return [
                    'id' => $report->id,
                    'status' => $report->status,
                    'status_changed_at' => $report->status_changed_at,
                    'created_at' => $report->created_at,
                ];
            })
        ]);

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Vorgangsnummer')
                    ->sortable(),
                Tables\Columns\TextColumn::make('plateCode1')
                    ->label('Kennzeichen')
                    ->formatStateUsing(fn ($record) => 
                        $record->plateCode1 . '-' . 
                        $record->plateCode2 . '-' . 
                        $record->plateCode3
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('firstname')
                    ->label('Mandant')
                    ->formatStateUsing(fn ($record) => 
                        $record->firstname . ' ' . $record->lastname
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdAt')
                    ->label('Halterabfrage an Zulassungsstelle versendet')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updatedAt')
                    ->label('Halterabfrage zurück')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('warning_sent_at')
                    ->label('Post Abmahnung')
                    ->date('d.m.Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reminder_due_at')
                    ->label('Termin Mahnung')
                    ->date('d.m.Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reminder_sent_at')
                    ->label('Post Mahnung')
                    ->date('d.m.Y')
                    ->sortable(),
                Tables\Columns\CheckboxColumn::make('has_ue')
                    ->label('Post UE')
                    ->sortable()
                    ->afterStateUpdated(function ($record, $state) {
                        $record->uefileuploadedat = $state ? now() : null;
                        $record->save();
                    }),
            ])
            ->defaultSort('status_changed_at', 'desc')
            ->actions([
                Action::make('setWarningDate')
                    ->label('Post Abmahnung')
                    ->icon('heroicon-m-calendar')
                    ->form([
                        DatePicker::make('warning_sent_at')
                            ->label('Post Abmahnung')
                            ->format('Y-m-d')
                            ->displayFormat('d.m.Y')
                            ->default(fn ($record) => $record->warning_sent_at),
                    ])
                    ->action(function (Report $record, array $data): void {
                        $record->warning_sent_at = $data['warning_sent_at'];
                        $record->save();
                    }),
                Action::make('setReminderDueDate')
                    ->label('Termin Mahnung')
                    ->icon('heroicon-m-calendar')
                    ->form([
                        DatePicker::make('reminder_due_at')
                            ->label('Termin Mahnung')
                            ->format('Y-m-d')
                            ->displayFormat('d.m.Y')
                            ->default(fn ($record) => $record->reminder_due_at),
                    ])
                    ->action(function (Report $record, array $data): void {
                        $record->reminder_due_at = $data['reminder_due_at'];
                        $record->save();
                    }),
                Action::make('setReminderSentDate')
                    ->label('Post Mahnung')
                    ->icon('heroicon-m-calendar')
                    ->form([
                        DatePicker::make('reminder_sent_at')
                            ->label('Post Mahnung')
                            ->format('Y-m-d')
                            ->displayFormat('d.m.Y')
                            ->default(fn ($record) => $record->reminder_sent_at),
                    ])
                    ->action(function (Report $record, array $data): void {
                        $record->reminder_sent_at = $data['reminder_sent_at'];
                        $record->save();
                    }),
                Tables\Actions\Action::make('edit')
                    ->label('Bearbeiten')
                    ->icon('heroicon-m-pencil-square')
                    ->url(function (Report $record): string {
                        return match ($record->status) {
                            3 => HolderInquirySentReportResource::getUrl('edit', ['record' => $record]),
                            4 => HolderInquiryReceivedReportResource::getUrl('edit', ['record' => $record]),
                            1 => InProgressReportResource::getUrl('edit', ['record' => $record]),
                            2 => CompletedReportResource::getUrl('edit', ['record' => $record]),
                            18 => CanceledReportResource::getUrl('edit', ['record' => $record]),
                            default => ReportResource::getUrl('edit', ['record' => $record]),
                        };
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
        ];
    }
}
