<?php

namespace App\Filament\Resources;

use App\Models\Report;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\WarningCreatedReportResource\Pages;
use Filament\Tables;
use Filament\Tables\Table;

class WarningCreatedReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = 'Abmahnung erzeugt (5)';
    
    protected static ?string $modelLabel = 'Abmahnung erzeugt';
    
    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return 'Reports';
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 5);
    }

    public static function table(Table $table): Table
    {
        return ReportResource::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWarningCreatedReports::route('/'),
        ];
    }
}
