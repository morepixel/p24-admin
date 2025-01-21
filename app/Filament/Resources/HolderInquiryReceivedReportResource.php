<?php

namespace App\Filament\Resources;

use App\Models\Report;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\HolderInquiryReceivedReportResource\Pages;
use Filament\Tables;
use Filament\Tables\Table;

class HolderInquiryReceivedReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    
    protected static ?string $navigationLabel = 'Halterabfrage zurück (4)';
    
    protected static ?string $modelLabel = 'Halterabfrage zurück';
    
    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return 'Reports';
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 4);
    }

    public static function table(Table $table): Table
    {
        return ReportResource::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHolderInquiryReceivedReports::route('/'),
        ];
    }
}
