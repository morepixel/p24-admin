<?php

namespace App\Filament\Resources;

use App\Models\Report;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CanceledReportResource\Pages;
use Filament\Tables;
use Filament\Tables\Table;

class CanceledReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-x-circle';

    protected static ?string $navigationLabel = 'Stornierte Reports (19)';

    protected static ?string $modelLabel = 'Stornierter Report';

    protected static ?string $pluralModelLabel = 'Stornierte Reports';

    protected static ?int $navigationSort = 7;

    public static function getNavigationGroup(): ?string
    {
        return 'Reports';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 19);
    }

    public static function table(Table $table): Table
    {
        return ReportResource::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCanceledReports::route('/'),
        ];
    }
}
