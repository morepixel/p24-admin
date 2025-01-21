<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListDeletedReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->where('status', 18);
    }

    public function getTitle(): string 
    {
        return 'Gelöschte Vorgänge';
    }
}
