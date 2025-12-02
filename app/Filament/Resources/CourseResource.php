<?php

namespace App\Filament\Resources;

use BackedEnum;
use App\Models\Course;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')
                ->label('Course Title')
                ->required()
                ->maxLength(255),
            Textarea::make('description')
                ->label('Description')
                ->required()
                ->rows(4),
            TextInput::make('total_lessons')
                ->label('Total Lessons')
                ->numeric()
                ->required(),
            Select::make('difficulty_level')
                ->label('Difficulty Level')
                ->options([
                    'beginner' => 'Beginner',
                    'intermediate' => 'Intermediate',
                    'advanced' => 'Advanced',
                    'expert' => 'Expert',
                ])
                ->required(),
            TextInput::make('xp_reward')
                ->label('XP Reward')
                ->numeric()
                ->required(),
            TextInput::make('thumbnail_url')
                ->label('Thumbnail URL')
                ->url()
                ->nullable(),
            Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('enrollments'))
            ->columns([
                TextColumn::make('title')
                    ->label('Course Title')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('difficulty_level')
                    ->label('Difficulty')
                    ->color(fn (string $state): string => match ($state) {
                        'beginner' => 'success',
                        'intermediate' => 'warning',
                        'advanced' => 'danger',
                        'expert' => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('total_lessons')
                    ->label('Lessons')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('xp_reward')
                    ->label('XP Reward')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('enrollments_count')
                    ->label('Enrolled Students')
                    ->numeric()
                    ->counts('enrollments'),
                TextColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('difficulty_level')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                        'expert' => 'Expert',
                    ]),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }



    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\CourseResource\Pages\ListCourses::route('/'),
            'create' => \App\Filament\Resources\CourseResource\Pages\CreateCourse::route('/create'),
            'view' => \App\Filament\Resources\CourseResource\Pages\ViewCourse::route('/{record}/view'),
            'edit' => \App\Filament\Resources\CourseResource\Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
