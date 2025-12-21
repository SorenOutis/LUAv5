<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Achievement;
use App\Models\Quest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    public static function canView(): bool
    {
        return true;
    }

    protected function getStats(): array
    {
        $avgLevel = UserProfile::selectRaw('AVG(level) as avg_level')->first()?->avg_level ?? 0;

        return [
            Stat::make('Total Users', User::count())
                ->description('Active users in system')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Total Achievements', Achievement::count())
                ->description('Available achievements')
                ->descriptionIcon('heroicon-m-star')
                ->color('info'),
            Stat::make('Total Quests', Quest::count())
                ->description('Available quests')
                ->descriptionIcon('heroicon-m-map')
                ->color('warning'),
            Stat::make('Avg User Level', number_format($avgLevel, 1))
                ->description('Average player level')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
