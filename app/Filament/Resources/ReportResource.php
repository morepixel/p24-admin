<?php

namespace App\Filament\Resources;

use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = 'Vorgänge';

    protected static ?string $navigationLabel = 'Vorgänge';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Grunddaten')
                    ->schema([
                        Forms\Components\TextInput::make('companyName')
                            ->label('Firmenname')
                            ->required()
                            ->maxLength(128),
                        Forms\Components\TextInput::make('firstname')
                            ->label('Vorname')
                            ->required(),
                        Forms\Components\TextInput::make('lastname')
                            ->label('Nachname')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('E-Mail')
                            ->email()
                            ->required(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Kennzeichen')
                    ->schema([
                        Forms\Components\TextInput::make('plateCode1')
                            ->label('Kennzeichen 1')
                            ->required(),
                        Forms\Components\TextInput::make('plateCode2')
                            ->label('Kennzeichen 2')
                            ->required(),
                        Forms\Components\TextInput::make('plateCode3')
                            ->label('Kennzeichen 3')
                            ->required(),
                        Forms\Components\DateTimePicker::make('date')
                            ->label('Datum')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Adresse')
                    ->schema([
                        Forms\Components\TextInput::make('street')
                            ->label('Straße')
                            ->required(),
                        Forms\Components\TextInput::make('zip')
                            ->label('PLZ')
                            ->required(),
                        Forms\Components\TextInput::make('city')
                            ->label('Stadt')
                            ->required(),
                        Forms\Components\TextInput::make('country')
                            ->label('Land')
                            ->required()
                            ->default('Deutschland'),
                        Forms\Components\TextInput::make('lat')
                            ->label('Breitengrad')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('lng')
                            ->label('Längengrad')
                            ->numeric()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Zusatzinformationen')
                    ->schema([
                        Forms\Components\Textarea::make('lawyerDetails')
                            ->label('Anwaltsnotizen')
                            ->maxLength(2000),
                        Forms\Components\TextInput::make('halterName')
                            ->label('Halter Name')
                            ->maxLength(128),
                        Forms\Components\TextInput::make('halterDatum')
                            ->label('Halter Datum')
                            ->maxLength(128),
                        Forms\Components\TextInput::make('zahlungsziel')
                            ->label('Zahlungsziel')
                            ->maxLength(128),
                        Forms\Components\TextInput::make('kennnummer')
                            ->label('Kennnummer')
                            ->maxLength(128),
                        Forms\Components\TextInput::make('halterPLZ')
                            ->label('Halter PLZ')
                            ->maxLength(128),
                        Forms\Components\TextInput::make('halterOrt')
                            ->label('Halter Ort')
                            ->maxLength(128),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                    })
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 0)->count();
    }

    protected static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('userId', Auth::id());
    }
}
