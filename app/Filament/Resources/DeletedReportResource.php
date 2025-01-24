<?php

namespace App\Filament\Resources;

use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use App\Filament\Resources\DeletedReportResource\Pages;
use App\Filament\Resources\ReportResource;

class DeletedReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-trash';
    
    protected static ?string $navigationLabel = 'Gelöschte Reports (18)';
    
    protected static ?string $modelLabel = 'Gelöschter Report';
    
    protected static ?int $navigationSort = 8;

    public static function getNavigationGroup(): ?string
    {
        return 'Reports';
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 18);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                Tables\Columns\TextColumn::make('kennzeichen')
                    ->label('Kennzeichen')
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
                Tables\Columns\TextColumn::make('firstname')
                    ->label('Vorname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lastname')
                    ->label('Nachname')
                    ->searchable(),
            ])
            ->defaultSort('createdAt', 'desc')
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->url(fn (Report $record): string => route('filament.admin.resources.in-progress-reports.edit', ['record' => $record]))
                    ->icon('heroicon-m-pencil-square'),
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
            'index' => Pages\ListDeletedReports::route('/'),
            'edit' => Pages\EditDeletedReport::route('/{record}/edit'),
        ];
    }
}
