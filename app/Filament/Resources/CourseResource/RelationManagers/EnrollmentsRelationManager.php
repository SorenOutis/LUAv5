<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\CourseEnrollment;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('approval_status')
                    ->label('Status')
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_lessons_count')
                    ->label('Lessons Completed')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('xp_earned')
                    ->label('XP Earned')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approved_at')
                    ->label('Approved Date')
                    ->dateTime('M j, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed Date')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('approval_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (CourseEnrollment $record) => $record->approval_status === 'pending')
                    ->action(fn (CourseEnrollment $record) => $record->update([
                        'approval_status' => 'approved',
                        'approved_at' => now(),
                    ]))
                    ->requiresConfirmation(),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->visible(fn (CourseEnrollment $record) => $record->approval_status === 'pending')
                    ->action(fn (CourseEnrollment $record) => $record->update([
                        'approval_status' => 'rejected',
                    ]))
                    ->requiresConfirmation(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                DeleteBulkAction::make(),
                ]),
            ]);
    }
}