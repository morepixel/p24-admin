<?php

namespace App\Filament\Resources\CanceledReportResource\Pages;

use App\Filament\Resources\CanceledReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCanceledReport extends EditRecord
{
    protected static string $resource = CanceledReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
