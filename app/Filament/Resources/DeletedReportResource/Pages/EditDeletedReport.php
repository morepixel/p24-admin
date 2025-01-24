<?php

namespace App\Filament\Resources\DeletedReportResource\Pages;

use App\Filament\Resources\DeletedReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeletedReport extends EditRecord
{
    protected static string $resource = DeletedReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
