<?php

namespace App\Filament\Resources;

use App\Models\Report;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CompletedReportResource\Pages;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;

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
        return ReportResource::table($table);
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
