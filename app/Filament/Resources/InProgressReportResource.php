<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InProgressReportResource\Pages;
use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Infolist;

class InProgressReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'In Bearbeitung';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', Report::STATUS_IN_PROGRESS)->count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('status', [
                Report::STATUS_IN_PROGRESS,
                Report::STATUS_COMPLETED,
                4, // Halterabfrage zurück
            ])
            ->with(['address', 'images']); // Eager load both relationships
    }

    public static function form(Form $form): Form
    {
        return ReportResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('createdAt')
                    ->label('Erstellt')
                    ->date('d.m.Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fullPlateCode')
                    ->label('Kennzeichen')
                    ->searchable(['plateCode1', 'plateCode2', 'plateCode3'])
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('plateCode1', $direction)
                            ->orderBy('plateCode2', $direction)
                            ->orderBy('plateCode3', $direction);
                    }),
                Tables\Columns\TextColumn::make('companyName')
                    ->label('Firmenname')
                    ->searchable(),
                Tables\Columns\IconColumn::make('has_images')
                    ->label('Bilder')
                    ->icon('heroicon-o-camera')
                    ->boolean()
                    ->state(fn (Model $record): bool => $record->images()->count() > 0)
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->alignCenter()
                    ->action(
                        Tables\Actions\Action::make('view_images')
                            ->label('Bilder anzeigen')
                            ->modalHeading('Bilder')
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false)
                            ->modalContent(function ($record): HtmlString {
                                $images = $record->images;
                                if ($images->isEmpty()) {
                                    return new HtmlString('<div class="p-4">Keine Bilder vorhanden</div>');
                                }
                                
                                $html = '<div class="grid grid-cols-2 gap-4 p-4">';
                                foreach ($images as $image) {
                                    $html .= sprintf(
                                        '<img src="%s" alt="Bild" class="w-full h-auto rounded-lg shadow-lg">',
                                        (string)$image->url
                                    );
                                }
                                $html .= '</div>';
                                
                                return new HtmlString($html);
                            })
                            ->modalWidth('md')
                    ),
                Tables\Columns\TextColumn::make('firstname')
                    ->label('Vorname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lastname')
                    ->label('Nachname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-Mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'gray',
                        '1' => 'warning',
                        '2' => 'success',
                        '3' => 'info',
                        '4' => 'success',
                        '5' => 'warning',
                        '6' => 'success',
                        '18' => 'danger',
                        '19' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (Report $record): string => $record->status_label),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'gray',
                        '1' => 'warning',
                        '2' => 'success',
                        '3' => 'info',
                        '4' => 'success',
                        '5' => 'warning',
                        '6' => 'success',
                        '18' => 'danger',
                        '19' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (Report $record): string => $record->status_label),
            ])
            ->defaultSort('createdAt', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->form([
                        Forms\Components\TextInput::make('plateCode1')
                            ->label('Kennzeichen 1')
                            ->required(),
                        Forms\Components\TextInput::make('plateCode2')
                            ->label('Kennzeichen 2'),
                        Forms\Components\TextInput::make('plateCode3')
                            ->label('Kennzeichen 3'),
                        Forms\Components\DatePicker::make('halterDatum')
                            ->label('Halterabfrage zurück')
                            ->format('Y-m-d'),
                        Forms\Components\TextInput::make('halterName')
                            ->label('Name'),
                        Forms\Components\TextInput::make('halterStrasse')
                            ->label('Straße'),
                        Forms\Components\TextInput::make('halterPLZ')
                            ->label('PLZ'),
                        Forms\Components\TextInput::make('halterOrt')
                            ->label('Ort'),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(Report::STATUS_LABELS)
                            ->required(),
                        Forms\Components\Select::make('lawyerapprovalstatus')
                            ->label('Anwalt Status')
                            ->options(Report::LAWYER_APPROVAL_STATUS_LABELS)
                            ->required(),
                    ]),
                Tables\Actions\Action::make('cancel')
                    ->label('Stornieren')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->action(function (Report $record) {
                        $record->update(['status' => 19]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInProgressReports::route('/'),
            'edit' => Pages\EditInProgressReport::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Vorgang')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('id')
                                    ->label('Vorgangsnummer'),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        '0' => 'gray',
                                        '1' => 'warning',
                                        '2' => 'success',
                                        '3' => 'info',
                                        '4' => 'success',
                                        '5' => 'warning',
                                        '6' => 'success',
                                        '18' => 'danger',
                                        '19' => 'danger',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn (int $state): string => "Status {$state}"),
                                TextEntry::make('createdAt')
                                    ->label('Erstellt')
                                    ->date('d.m.Y'),
                            ]),
                    ]),
                Section::make('Kennzeichen')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('plateCode1')
                                    ->label('Kennzeichen 1'),
                                TextEntry::make('plateCode2')
                                    ->label('Kennzeichen 2'),
                                TextEntry::make('plateCode3')
                                    ->label('Kennzeichen 3'),
                            ]),
                    ]),
                Section::make('Mandant')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('companyname')
                                    ->label('Firmenname'),
                                TextEntry::make('firstname')
                                    ->label('Vorname'),
                                TextEntry::make('lastname')
                                    ->label('Nachname'),
                                TextEntry::make('email')
                                    ->label('E-Mail'),
                            ]),
                    ]),
            ]);
    }
}
