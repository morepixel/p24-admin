<?php

namespace App\Filament\Resources;

use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\ReportResource\Pages;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Vorgänge';

    protected static ?string $modelLabel = 'Vorgang';

    protected static ?string $pluralModelLabel = 'Vorgänge';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'id';

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
            'create' => Pages\CreateReport::route('/create'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'id',
            'companyName',
            'firstname',
            'lastname',
            'email',
            'plateCode1',
            'plateCode2',
            'plateCode3',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        /** @var Report $record */
        return "#{$record->id} - {$record->companyName}";
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Report $record */
        return [
            'Name' => $record->firstname . ' ' . $record->lastname,
            'Email' => $record->email,
            'Status' => match ($record->status) {
                0 => 'Neu',
                1 => 'In Bearbeitung',
                2 => 'Abgeschlossen',
                3 => 'Storniert',
                18 => 'Gelöscht',
                default => "Status {$record->status}",
            },
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Grunddaten')
                    ->schema([
                        Forms\Components\TextInput::make('id')
                            ->label('ID')
                            ->disabled(),
                        Forms\Components\TextInput::make('companyName')
                            ->label('Firmenname'),
                        Forms\Components\TextInput::make('firstname')
                            ->label('Vorname'),
                        Forms\Components\TextInput::make('lastname')
                            ->label('Nachname'),
                        Forms\Components\TextInput::make('email')
                            ->label('E-Mail')
                            ->email(),
                        Forms\Components\DateTimePicker::make('date')
                            ->label('Datum'),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                '0' => 'Neu',
                                '1' => 'In Bearbeitung',
                                '2' => 'Abgeschlossen',
                                '3' => 'Storniert',
                                '18' => 'Gelöscht',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Kennzeichen')
                    ->schema([
                        Forms\Components\TextInput::make('plateCode1')
                            ->label('Kennzeichen 1'),
                        Forms\Components\TextInput::make('plateCode2')
                            ->label('Kennzeichen 2'),
                        Forms\Components\TextInput::make('plateCode3')
                            ->label('Kennzeichen 3'),
                    ])->columns(3),

                Forms\Components\Section::make('Adresse')
                    ->schema([
                        Forms\Components\TextInput::make('street')
                            ->label('Straße'),
                        Forms\Components\TextInput::make('zip')
                            ->label('PLZ'),
                        Forms\Components\TextInput::make('city')
                            ->label('Stadt'),
                        Forms\Components\TextInput::make('country')
                            ->label('Land'),
                    ])->columns(2),

                Forms\Components\Section::make('Halter')
                    ->schema([
                        Forms\Components\TextInput::make('halterName')
                            ->label('Name'),
                        Forms\Components\TextInput::make('halterStrasse')
                            ->label('Straße'),
                        Forms\Components\TextInput::make('halterPLZ')
                            ->label('PLZ'),
                        Forms\Components\TextInput::make('halterOrt')
                            ->label('Ort'),
                        Forms\Components\Select::make('halterGeschlecht')
                            ->label('Geschlecht')
                            ->options([
                                'm' => 'Männlich',
                                'w' => 'Weiblich',
                                'd' => 'Divers',
                            ]),
                        Forms\Components\DatePicker::make('halterDatum')
                            ->label('Datum'),
                    ])->columns(2),

                Forms\Components\Section::make('Fahrer')
                    ->schema([
                        Forms\Components\TextInput::make('fahrerName')
                            ->label('Name'),
                        Forms\Components\TextInput::make('fahrerStrasse')
                            ->label('Straße'),
                        Forms\Components\TextInput::make('fahrerPLZ')
                            ->label('PLZ'),
                        Forms\Components\TextInput::make('fahrerOrt')
                            ->label('Ort'),
                        Forms\Components\Select::make('fahrerGeschlecht')
                            ->label('Geschlecht')
                            ->options([
                                'm' => 'Männlich',
                                'w' => 'Weiblich',
                                'd' => 'Divers',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Zusätzliche Informationen')
                    ->schema([
                        Forms\Components\TextInput::make('kennnummer')
                            ->label('Kennnummer'),
                        Forms\Components\TextInput::make('zahlungsziel')
                            ->label('Zahlungsziel'),
                        Forms\Components\TextInput::make('lawyerDetails')
                            ->label('Anwalt Details'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notizen')
                            ->rows(3),
                    ])->columns(2),

                Forms\Components\Section::make('System Informationen')
                    ->schema([
                        Forms\Components\Toggle::make('paymentStatus')
                            ->label('Bezahlt'),
                        Forms\Components\Toggle::make('adminEmailSent')
                            ->label('Admin E-Mail gesendet'),
                        Forms\Components\Toggle::make('paidKBA')
                            ->label('KBA bezahlt'),
                        Forms\Components\Toggle::make('alreadyInSystem')
                            ->label('Bereits im System'),
                        Forms\Components\TextInput::make('uploadStatus')
                            ->label('Upload Status'),
                        Forms\Components\TextInput::make('sentStatus')
                            ->label('Sende Status'),
                        Forms\Components\TextInput::make('lawyerApprovalStatus')
                            ->label('Anwalt Genehmigungsstatus'),
                        Forms\Components\TextInput::make('reportResponse')
                            ->label('Report Antwort'),
                        Forms\Components\TextInput::make('order')
                            ->label('Reihenfolge'),
                        Forms\Components\TextInput::make('lat')
                            ->label('Breitengrad'),
                        Forms\Components\TextInput::make('lng')
                            ->label('Längengrad'),
                        Forms\Components\TextInput::make('kbaFile')
                            ->label('KBA Datei'),
                        Forms\Components\TextInput::make('ueFIle')
                            ->label('UE Datei'),
                        Forms\Components\DateTimePicker::make('ueFileUploadedAt')
                            ->label('UE Datei hochgeladen am'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('companyName')
                    ->label('Firmenname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('firstname')
                    ->label('Vorname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lastname')
                    ->label('Nachname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-Mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Datum')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        0 => 'gray',
                        1 => 'warning',
                        2 => 'success',
                        3 => 'danger',
                        18 => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        0 => 'Neu',
                        1 => 'In Bearbeitung',
                        2 => 'Abgeschlossen',
                        3 => 'Storniert',
                        18 => 'Gelöscht',
                        default => "Status {$state}",
                    }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 0 ? 'primary' : 'gray';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
