<?php

namespace App\Filament\Resources;

use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use App\Filament\Resources\CompletedReportResource\Pages;

class CompletedReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    
    protected static ?string $navigationLabel = 'Neuer Vorgang mit Vollmacht (2)';
    
    protected static ?string $modelLabel = 'Neuer Vorgang mit Vollmacht';
    
    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return 'Reports';
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 2);
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
                Tables\Filters\TrashedFilter::make(),
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
                        Forms\Components\TextInput::make('halterName')
                            ->label('Name')
                            ->required(),
                        Forms\Components\TextInput::make('halterStreet')
                            ->label('Straße')
                            ->required(),
                        Forms\Components\TextInput::make('halterCity')
                            ->label('Stadt')
                            ->required(),
                        Forms\Components\TextInput::make('halterZip')
                            ->label('PLZ')
                            ->required(),
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

    public static function form(Form $form): Form
    {
        return ReportResource::form($form);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompletedReports::route('/'),
            'edit' => Pages\EditCompletedReport::route('/{record}/edit'),
        ];
    }
}
