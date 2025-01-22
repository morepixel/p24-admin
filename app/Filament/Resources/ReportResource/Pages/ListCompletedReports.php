<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Report;
use Illuminate\Database\Eloquent\Builder;

class ListCompletedReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return Report::query()->where('status', Report::STATUS_COMPLETED);
    }

    public function getTitle(): string 
    {
        return 'Abgeschlossene Vorgänge';
    }
}
