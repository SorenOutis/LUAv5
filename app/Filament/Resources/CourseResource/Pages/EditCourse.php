<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('enrollments')
                ->label('View Enrollments')
                ->icon('heroicon-o-users')
                ->url(fn () => route('filament.admin.resources.courses.enrollments', ['record' => $this->record])),
            Actions\DeleteAction::make(),
        ];
    }
}
