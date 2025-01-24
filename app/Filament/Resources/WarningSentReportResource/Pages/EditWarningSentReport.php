<?php

namespace App\Filament\Resources\WarningSentReportResource\Pages;

use App\Filament\Resources\WarningSentReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWarningSentReport extends EditRecord
{
    protected static string $resource = WarningSentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
