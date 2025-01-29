<?php

namespace App\Filament\Resources;

use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Support\HtmlString;
use App\Filament\Resources\ReportResource\Pages;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Alle Vorgänge';

    protected static ?string $modelLabel = 'Vorgang';

    protected static ?string $pluralModelLabel = 'Vorgänge';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'id';

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public static function getPluralModelLabel(): string
    {
        return static::$modelLabel ?? (string) str(class_basename(static::getModel()))
            ->beforeLast('Resource')
            ->kebab()
            ->replace('-', ' ')
            ->title();
    }

    public static function getNavigationGroups(): array
    {
        return ['Reports'];
    }

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make('Alle Vorgänge')
                ->icon('heroicon-o-document')
                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.reports.index'))
                ->url(static::getUrl()),
            NavigationItem::make('Neue Vorgänge (0)')
                ->icon('heroicon-o-plus')
                ->badge(static::getModel()::where('status', 0)->count(), color: 'gray')
                ->url(static::getUrl('new')),
            NavigationItem::make('In Bearbeitung (1)')
                ->icon('heroicon-o-arrow-path')
                ->badge(static::getModel()::where('status', 1)->count(), color: 'warning')
                ->url(static::getUrl('inProgress')),
            NavigationItem::make('Abgeschlossen (2)')
                ->icon('heroicon-o-check')
                ->badge(static::getModel()::where('status', 2)->count(), color: 'success')
                ->url(static::getUrl('completed')),
            NavigationItem::make('Storniert (3)')
                ->icon('heroicon-o-x-mark')
                ->badge(static::getModel()::where('status', 3)->count(), color: 'danger')
                ->url(static::getUrl('canceled')),
            NavigationItem::make('Halterabfrage abgeschickt (4)')
                ->icon('heroicon-o-paper-airplane')
                ->badge(static::getModel()::where('status', 4)->count(), color: 'info')
                ->url(static::getUrl('ownerRequestSent')),
            NavigationItem::make('Halterabfrage zurück (5)')
                ->icon('heroicon-o-arrow-down-circle')
                ->badge(static::getModel()::where('status', 5)->count(), color: 'success')
                ->url(static::getUrl('ownerRequestReceived')),
            NavigationItem::make('Abmahnung erzeugt (6)')
                ->icon('heroicon-o-document-plus')
                ->badge(static::getModel()::where('status', 6)->count(), color: 'warning')
                ->url(static::getUrl('warningCreated')),
            NavigationItem::make('Abmahnung verschickt (7)')
                ->icon('heroicon-o-envelope')
                ->badge(static::getModel()::where('status', 7)->count(), color: 'info')
                ->url(static::getUrl('warningSent')),
            NavigationItem::make('Gelöscht (18)')
                ->icon('heroicon-o-trash')
                ->badge(static::getModel()::where('status', 18)->count(), color: 'danger')
                ->url(static::getUrl('deleted')),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'new' => Pages\ListNewReports::route('/new'),
            'inProgress' => Pages\ListInProgressReports::route('/in-progress'),
            'completed' => Pages\ListCompletedReports::route('/completed'),
            'canceled' => Pages\ListCanceledReports::route('/canceled'),
            'ownerRequestSent' => Pages\ListOwnerRequestSentReports::route('/owner-request-sent'),
            'ownerRequestReceived' => Pages\ListOwnerRequestReceivedReports::route('/owner-request-received'),
            'warningCreated' => Pages\ListWarningCreatedReports::route('/warning-created'),
            'warningSent' => Pages\ListWarningSentReports::route('/warning-sent'),
            'deleted' => Pages\ListDeletedReports::route('/deleted'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
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
                4 => 'Halterabfrage abgeschickt',
                5 => 'Halterabfrage zurück',
                6 => 'Abmahnung erzeugt',
                7 => 'Abmahnung verschickt',
                18 => 'Gelöscht',
                default => "Status {$record->status}",
            },
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Section::make('Meldedaten')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('firstname')
                                            ->label('Vorname')
                                            ->maxLength(128),
                                        Forms\Components\TextInput::make('lastname')
                                            ->label('Nachname')
                                            ->maxLength(128),
                                    ]),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('email')
                                            ->label('E-Mail')
                                            ->email()
                                            ->maxLength(255),
                                    ]),
                                Forms\Components\TextInput::make('companyName')
                                    ->label('Firmenname')
                                    ->maxLength(128),
                            ]),

                        Forms\Components\Section::make('Fahrzeugdaten')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('plateCode1')
                                            ->label('Kennzeichen Teil 1')
                                            ->maxLength(3),
                                        Forms\Components\TextInput::make('plateCode2')
                                            ->label('Kennzeichen Teil 2')
                                            ->maxLength(2),
                                        Forms\Components\TextInput::make('plateCode3')
                                            ->label('Kennzeichen Teil 3')
                                            ->maxLength(4),
                                    ]),
                            ]),

                        Forms\Components\Section::make('Halterdaten')
                            ->description('Daten aus der Halterabfrage')
                            ->collapsible()
                            ->persistCollapsed()
                            ->columnSpan(2)
                            ->icon('heroicon-o-user')
                            ->extraAttributes([
                                'style' => 'background-color: rgb(254 249 195);',
                            ])
                            ->schema([
                                Forms\Components\DatePicker::make('halterDatum')
                                    ->label('Halterabfrage zurück')
                                    ->format('Y-m-d'),
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('halterName')
                                            ->label('Name')
                                            ->columnSpan(2),
                                        Forms\Components\TextInput::make('halterGeschlecht')
                                            ->label('Geschlecht'),
                                    ]),
                                Forms\Components\TextInput::make('halterStrasse')
                                    ->label('Straße'),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('halterPLZ')
                                            ->label('PLZ'),
                                        Forms\Components\TextInput::make('halterOrt')
                                            ->label('Ort'),
                                    ]),
                            ]),

                        Forms\Components\Section::make('Status')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options(Report::STATUS_LABELS)
                                    ->required(),
                                Forms\Components\Select::make('lawyerapprovalstatus')
                                    ->label('Anwalt Status')
                                    ->options(Report::LAWYER_APPROVAL_STATUS_LABELS)
                                    ->required(),
                            ]),

                        Forms\Components\Section::make('Adressdaten')
                            ->schema([
                                Forms\Components\TextInput::make('street')
                                    ->label('Straße')
                                    ->maxLength(255),
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('zip')
                                            ->label('PLZ')
                                            ->length(5)
                                            ->numeric()
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('city')
                                            ->label('Stadt')
                                            ->maxLength(128)
                                            ->columnSpan(3),
                                    ])
                                    ->columns(4),
                                Forms\Components\TextInput::make('country')
                                    ->label('Land')
                                    ->default('Deutschland')
                                    ->maxLength(128),
                            ]),

                        Forms\Components\Section::make('Fahrer')
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('fahrername')
                                            ->label('Name')
                                            ->maxLength(128)
                                            ->columnSpan(3),
                                        Forms\Components\Select::make('fahrergeschlecht')
                                            ->label('Geschlecht')
                                            ->options([
                                                'M' => 'Herr',
                                                'W' => 'Frau',
                                                'F' => 'Firma',
                                            ])
                                            ->columnSpan(1),
                                    ])
                                    ->columns(4),
                                Forms\Components\TextInput::make('fahrerstrasse')
                                    ->label('Straße')
                                    ->maxLength(255),
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('fahrerplz')
                                            ->label('PLZ')
                                            ->length(5)
                                            ->numeric()
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('fahrerort')
                                            ->label('Ort')
                                            ->maxLength(128)
                                            ->columnSpan(3),
                                    ])
                                    ->columns(4),
                            ]),

                        Forms\Components\Section::make('Zusätzliche Informationen')
                            ->schema([
                                Forms\Components\Textarea::make('lawyerdetails')
                                    ->label('Anwaltsdetails')
                                    ->maxLength(2000),
                                Forms\Components\DatePicker::make('zahlungsziel')
                                    ->label('Zahlungsziel')
                                    ->format('d.m.Y')
                                    ->displayFormat('d.m.Y'),
                                Forms\Components\TextInput::make('kennnummer')
                                    ->label('Kennnummer')
                                    ->maxLength(128),
                                Forms\Components\FileUpload::make('kbafile')
                                    ->label('KBA Datei')
                                    ->disk('public')
                                    ->directory('kba-files')
                                    ->acceptedFileTypes(['application/pdf']),
                                Forms\Components\FileUpload::make('uefile')
                                    ->label('UE Datei')
                                    ->disk('public')
                                    ->directory('ue-files')
                                    ->acceptedFileTypes(['application/pdf']),
                                Forms\Components\DateTimePicker::make('uefileuploadedat')
                                    ->label('UE Datei hochgeladen am')
                                    ->format('d.m.Y H:i:s')
                                    ->displayFormat('d.m.Y H:i:s'),
                                Forms\Components\Textarea::make('notes')
                                    ->label('Notizen')
                                    ->rows(3)
                                    ->maxLength(65535),
                                Forms\Components\Textarea::make('reportresponse')
                                    ->label('Report Antwort')
                                    ->rows(3)
                                    ->maxLength(65535),
                            ]),

                        Forms\Components\Section::make('Status-Historie')
                            ->schema([
                                Forms\Components\Repeater::make('statusHistory')
                                    ->label('Status-Änderungen')
                                    ->relationship('statusHistory')
                                    ->schema([
                                        Forms\Components\TextInput::make('old_status_label')
                                            ->label('Alter Status')
                                            ->disabled(),
                                        Forms\Components\TextInput::make('new_status_label')
                                            ->label('Neuer Status')
                                            ->disabled(),
                                        Forms\Components\TextInput::make('changed_by')
                                            ->label('Geändert von')
                                            ->disabled(),
                                        Forms\Components\TextInput::make('created_at')
                                            ->label('Datum')
                                            ->disabled(),
                                    ])
                                    ->columns(4)
                                    ->disabled()
                                    ->defaultItems(0)
                                    ->reorderable(false)
                                    ->addable(false)
                                    ->deletable(false),
                            ]),
                    ])
                    ->columnSpan(['lg' => 3]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Section::make('System Informationen')
                            ->schema([
                                Forms\Components\Toggle::make('paymentstatus')
                                    ->label('Bezahlt'),
                                Forms\Components\Toggle::make('adminemailsent')
                                    ->label('Admin E-Mail gesendet'),
                                Forms\Components\Toggle::make('paidkba')
                                    ->label('KBA bezahlt'),
                                Forms\Components\DateTimePicker::make('uefileuploadedat')
                                    ->label('UE File hochgeladen'),
                            ]),

                        Forms\Components\Section::make('Abmahnungen und Mahnungen')
                            ->schema([
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('warning')
                                        ->label('Abmahnung schicken')
                                        ->modalHeading('Abmahnung schicken')
                                        ->modalDescription('Sind Sie sicher, dass Sie eine Abmahnung schicken möchten?')
                                        ->modalSubmitActionLabel('Ja, Abmahnung schicken')
                                        ->modalCancelActionLabel('Abbrechen')
                                        ->color('warning')
                                        ->icon('heroicon-o-exclamation-triangle')
                                        ->url(fn (Report $record): string => route('warning-letter.generate', ['report' => $record]))
                                        ->openUrlInNewTab(),
                                    Forms\Components\Actions\Action::make('firstReminder')
                                        ->label('Erste Mahnung schicken')
                                        ->modalHeading('Erste Mahnung schicken')
                                        ->modalDescription('Sind Sie sicher, dass Sie eine erste Mahnung schicken möchten?')
                                        ->modalSubmitActionLabel('Ja, erste Mahnung schicken')
                                        ->modalCancelActionLabel('Abbrechen')
                                        ->color('warning')
                                        ->icon('heroicon-o-exclamation-triangle')
                                        ->action(function () {
                                            // Logik für erste Mahnung
                                        }),
                                    Forms\Components\Actions\Action::make('secondReminder')
                                        ->label('Zweite Mahnung schicken')
                                        ->modalHeading('Zweite Mahnung schicken')
                                        ->modalDescription('Sind Sie sicher, dass Sie eine zweite Mahnung schicken möchten?')
                                        ->modalSubmitActionLabel('Ja, zweite Mahnung schicken')
                                        ->modalCancelActionLabel('Abbrechen')
                                        ->color('warning')
                                        ->icon('heroicon-o-exclamation-triangle')
                                        ->action(function () {
                                            // Logik für zweite Mahnung
                                        }),
                                    Forms\Components\Actions\Action::make('warningLetter')
                                        ->icon('heroicon-o-envelope')
                                        ->label('Abmahnung verschicken')
                                        ->url(fn (Report $record): string => route('warning-letter.generate', ['report' => $record]))
                                        ->openUrlInNewTab(),
                                ]),
                            ]),

                        Forms\Components\Section::make('Unterlassungserklärung')
                            ->schema([
                                Forms\Components\Group::make([
                                    Forms\Components\ViewField::make('ueFile')
                                        ->label('Unterlassungserklärung')
                                        ->view('filament.resources.report-resource.fields.file-link'),
                                    Forms\Components\FileUpload::make('ueFile')
                                        ->label('Unterlassungserklärung hochladen')
                                        ->visible(fn ($record) => empty($record->ueFile))
                                        ->directory('ue-files')
                                        ->preserveFilenames()
                                        ->acceptedFileTypes(['application/pdf']),
                                ]),
                            ]),

                        Forms\Components\Section::make('Vollmacht')
                            ->schema([
                                Forms\Components\ViewField::make('address.poaFile')
                                    ->label('Vollmacht')
                                    ->view('filament.resources.report-resource.fields.file-link'),
                            ]),

                        Forms\Components\Section::make('Bilder')
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\ViewField::make('images')
                                            ->label('')
                                            ->view('filament.forms.components.image-preview'),
                                    ])
                                    ->columns(1)
                            ])
                            ->collapsible(),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(['lg' => 4]);
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
                                        $image->url
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
                    ->formatStateUsing(fn ($state) => "Status " . $state)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'gray',
                        '1' => 'warning',
                        '2' => 'success',
                        '3' => 'info',
                        '4' => 'success',
                        '18' => 'danger',
                        '19' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'gray',
                        '1' => 'warning',
                        '2' => 'success',
                        '3' => 'info',
                        '4' => 'success',
                        '18' => 'danger',
                        '19' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (Report $record): string => $record->status_label),
            ])
            ->defaultSort('createdAt', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->form([
                        Forms\Components\TextInput::make('plateCode1')
                            ->label('Kennzeichen 1'),
                        Forms\Components\TextInput::make('plateCode2')
                            ->label('Kennzeichen 2'),
                        Forms\Components\TextInput::make('plateCode3')
                            ->label('Kennzeichen 3'),
                        Forms\Components\TextInput::make('halterName')
                            ->label('Name'),
                        Forms\Components\TextInput::make('halterStreet')
                            ->label('Straße'),
                        Forms\Components\TextInput::make('halterCity')
                            ->label('Stadt'),
                        Forms\Components\TextInput::make('halterZip')
                            ->label('PLZ'),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(Report::STATUS_LABELS),
                        Forms\Components\Select::make('lawyerapprovalstatus')
                            ->label('Anwalt Status')
                            ->options(Report::LAWYER_APPROVAL_STATUS_LABELS),
                    ]),
                Tables\Actions\Action::make('stornieren')
                    ->label('Stornieren')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Report stornieren')
                    ->modalDescription('Sind Sie sicher, dass Sie diesen Report stornieren möchten?')
                    ->action(function (Report $record): void {
                        $record->update(['status' => 19]);
                    })
                    ->visible(fn (Report $record): bool => $record->status != 19),
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

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 0)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 0 ? 'primary' : 'gray';
    }

    public static function getNavigationLabel(): string
    {
        return 'Neue Reports (0)';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('view_any_report');
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
