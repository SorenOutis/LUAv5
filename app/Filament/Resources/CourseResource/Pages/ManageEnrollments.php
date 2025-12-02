<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Models\Course;
use App\Models\CourseEnrollment;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Attributes\Computed;

class ManageEnrollments extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = CourseResource::class;

    #[Computed]
    public function record()
    {
        return Course::findOrFail(request()->route('record'));
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CourseEnrollment::query()->where('course_id', $this->record()->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('approval_status')
                    ->label('Status')
                    ->colors([
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('progress_percentage')
                    ->label('Progress %')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_lessons_count')
                    ->label('Completed Lessons')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('xp_earned')
                    ->label('XP Earned')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime('M j, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested At')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('approval_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->label('Approval Status'),
            ])
            ->actions([
                Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (CourseEnrollment $record) => $record->approval_status === 'pending')
                    ->action(fn (CourseEnrollment $record) => $record->update([
                        'approval_status' => 'approved',
                        'approved_at' => now(),
                    ]))
                    ->requiresConfirmation(),
                Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->visible(fn (CourseEnrollment $record) => $record->approval_status === 'pending')
                    ->action(fn (CourseEnrollment $record) => $record->update([
                        'approval_status' => 'rejected',
                    ]))
                    ->requiresConfirmation(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkAction::make('approve_bulk')
                    ->label('Approve Selected')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->action(fn (Tables\Contracts\HasTable $livewire) => 
                        CourseEnrollment::query()
                            ->whereIn('id', $livewire->selectedTableRecords)
                            ->update([
                                'approval_status' => 'approved',
                                'approved_at' => now(),
                            ])
                    ),
                Actions\DeleteBulkAction::make(),
            ]);
    }
}
