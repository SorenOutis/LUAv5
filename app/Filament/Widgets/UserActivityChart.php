<?php

namespace App\Filament\Widgets;

use App\Models\DailyLoginBonus;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UserActivityChart extends ChartWidget
{
    protected ?string $heading = 'Daily Active Users (Last 30 Days)';

    protected function getData(): array
    {
        $data = DailyLoginBonus::selectRaw('bonus_date as date, COUNT(DISTINCT user_id) as active_users')
            ->where('bonus_date', '>=', now()->subDays(30)->toDateString())
            ->groupBy('bonus_date')
            ->orderBy('bonus_date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Active Users',
                    'data' => $data->pluck('active_users')->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
