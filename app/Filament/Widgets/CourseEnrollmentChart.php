<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use Filament\Widgets\ChartWidget;

class CourseEnrollmentChart extends ChartWidget
{
    protected ?string $heading = 'Top 10 Courses by Enrollment';

    protected function getData(): array
    {
        $courses = Course::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Enrollments',
                    'data' => $courses->pluck('enrollments_count')->toArray(),
                    'backgroundColor' => 'rgba(168, 85, 247, 0.8)',
                    'borderColor' => '#a855f7',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $courses->pluck('title')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
