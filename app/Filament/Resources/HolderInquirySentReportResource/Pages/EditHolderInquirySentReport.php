<?php

namespace App\Filament\Resources\HolderInquirySentReportResource\Pages;

use App\Filament\Resources\HolderInquirySentReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHolderInquirySentReport extends EditRecord
{
    protected static string $resource = HolderInquirySentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
