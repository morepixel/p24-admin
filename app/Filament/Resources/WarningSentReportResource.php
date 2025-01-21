<?php

namespace App\Filament\Resources;

use App\Models\Report;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\WarningSentReportResource\Pages;
use Filament\Tables;
use Filament\Tables\Table;

class WarningSentReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    
    protected static ?string $navigationLabel = 'Abmahnung verschickt (6)';
    
    protected static ?string $modelLabel = 'Abmahnung verschickt';
    
    protected static ?int $navigationSort = 6;

    public static function getNavigationGroup(): ?string
    {
        return 'Reports';
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 6);
    }

    public static function table(Table $table): Table
    {
        return ReportResource::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWarningSentReports::route('/'),
        ];
    }
}
