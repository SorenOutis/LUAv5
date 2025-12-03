<?php

namespace App\Filament\Resources\RankingTierResource\Pages;

use App\Filament\Resources\RankingTierResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRankingTiers extends ListRecords
{
    protected static string $resource = RankingTierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
