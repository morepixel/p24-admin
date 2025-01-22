<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use App\Models\Report;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Card;
use Illuminate\Database\Eloquent\Model;

class EditReport extends EditRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Konvertiere numerische Status-Werte in Strings für das Select-Feld
        if (isset($data['status'])) {
            $data['status'] = (string) $data['status'];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Konvertiere String-Status zurück in numerische Werte
        if (isset($data['status'])) {
            $data['status'] = (int) $data['status'];
        }

        return $data;
    }

    public function getRecord(): Report
    {
        return parent::getRecord();
    }

    public static function canDelete(Model $record): bool
    {
        return true;
    }
}
