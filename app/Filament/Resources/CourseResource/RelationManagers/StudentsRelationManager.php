<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('pivot.approval_status')
                    ->label('Status')
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('pivot.progress_percentage')
                    ->label('Progress (%)')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state . '%'),
                Tables\Columns\TextColumn::make('pivot.completed_lessons_count')
                    ->label('Lessons Completed')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pivot.xp_earned')
                    ->label('XP Earned')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pivot.approved_at')
                    ->label('Approved Date')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pivot.completed_at')
                    ->label('Completed Date')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('pivot.approval_status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Enroll Student')
                    ->form([
                        \Filament\Forms\Components\Select::make('user_id')
                            ->label('Student')
                            ->required()
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                return \App\Models\User::where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(function ($value) {
                                return \App\Models\User::find($value)?->name;
                            }),
                    ]),
            ])
            ->actions([
                EditAction::make()
                    ->label('Edit Enrollment')
                    ->form([
                        \Filament\Forms\Components\Select::make('approval_status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('progress_percentage')
                            ->label('Progress (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        \Filament\Forms\Components\TextInput::make('completed_lessons_count')
                            ->label('Completed Lessons')
                            ->numeric()
                            ->minValue(0),
                        \Filament\Forms\Components\TextInput::make('xp_earned')
                            ->label('XP Earned')
                            ->numeric()
                            ->minValue(0),
                    ]),
                DetachAction::make()
                    ->label('Remove Student'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                DetachBulkAction::make()
                        ->label('Remove Students'),
                ]),
            ]);
    }
}