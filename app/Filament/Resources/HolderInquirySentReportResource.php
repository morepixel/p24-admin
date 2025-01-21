<?php

namespace App\Filament\Resources;

use App\Models\Report;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\HolderInquirySentReportResource\Pages;
use Filament\Tables;
use Filament\Tables\Table;

class HolderInquirySentReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    
    protected static ?string $navigationLabel = 'Halterabfrage abgeschickt (3)';
    
    protected static ?string $modelLabel = 'Halterabfrage abgeschickt';
    
    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return 'Reports';
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 3);
    }

    public static function table(Table $table): Table
    {
        return ReportResource::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHolderInquirySentReports::route('/'),
        ];
    }
}
