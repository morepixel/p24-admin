<?php

namespace App\Filament\Resources;

use App\Models\Report;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DeletedReportResource\Pages;
use Filament\Tables;
use Filament\Tables\Table;

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
        return ReportResource::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeletedReports::route('/'),
        ];
    }
}
