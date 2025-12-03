<?php

namespace App\Filament\Resources\RankingTierResource\Pages;

use App\Filament\Resources\RankingTierResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRankingTier extends EditRecord
{
    protected static string $resource = RankingTierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
