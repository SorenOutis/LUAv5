<?php

namespace App\Filament\Resources\ProgressMetricResource\Pages;

use App\Filament\Resources\ProgressMetricResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProgressMetric extends EditRecord
{
    protected static string $resource = ProgressMetricResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
