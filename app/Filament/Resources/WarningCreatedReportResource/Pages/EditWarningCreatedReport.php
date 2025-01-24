<?php

namespace App\Filament\Resources\WarningCreatedReportResource\Pages;

use App\Filament\Resources\WarningCreatedReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWarningCreatedReport extends EditRecord
{
    protected static string $resource = WarningCreatedReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
