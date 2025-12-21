<?php

namespace App\Filament\Widgets;

use App\Models\UserProfile;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UserProgressionChart extends ChartWidget
{
    protected ?string $heading = 'User Level Distribution';

    protected function getData(): array
    {
        $data = UserProfile::selectRaw('level, COUNT(*) as count')
            ->groupBy('level')
            ->orderBy('level')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Players per Level',
                    'data' => $data->pluck('count')->toArray(),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->pluck('level')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
