<?php

namespace App\Filament\Resources\AwardPointsResource\Pages;

use App\Filament\Resources\AwardPointsResource;
use Filament\Resources\Pages\ListRecords;

class ListAwardPoints extends ListRecords
{
    protected static string $resource = AwardPointsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return 'Award Points to Users';
    }
}
