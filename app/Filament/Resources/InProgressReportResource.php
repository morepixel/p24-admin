<?php

namespace App\Filament\Resources;

use App\Models\Report;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\InProgressReportResource\Pages;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;

class InProgressReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';
    
    protected static ?string $navigationLabel = 'Neuer Vorgang ohne Vollmacht (1)';
    
    protected static ?string $modelLabel = 'Neuer Vorgang ohne Vollmacht';
    
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return 'Reports';
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 1);
    }

    public static function table(Table $table): Table
    {
        return ReportResource::table($table);
    }

    public static function form(Form $form): Form
    {
        return ReportResource::form($form);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInProgressReports::route('/'),
            'edit' => Pages\EditInProgressReport::route('/{record}/edit'),
        ];
    }
}
