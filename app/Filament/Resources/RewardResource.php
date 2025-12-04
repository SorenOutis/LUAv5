<?php

namespace App\Filament\Resources;

use BackedEnum;
use UnitEnum;
use App\Models\Achievement;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RewardResource extends Resource
{
    protected static ?string $model = Achievement::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Rewards';
    protected static ?string $label = 'Reward';
    protected static ?string $pluralLabel = 'Rewards';
    protected static string|UnitEnum|null $navigationGroup = 'Gamification';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->required()
                ->label('Reward Name')
                ->maxLength(255),

            Textarea::make('description')
                ->required()
                ->rows(3)
                ->label('Description'),

            TextInput::make('icon')
                ->required()
                ->label('Icon (Emoji)')
                ->maxLength(10)
                ->hint('Enter an emoji character'),

            Select::make('category')
                ->required()
                ->options([
                    'Learning' => 'Learning',
                    'Streak' => 'Streak',
                    'Milestones' => 'Milestones',
                    'Levels' => 'Levels',
                    'XP' => 'XP',
                    'Social' => 'Social',
                    'Special' => 'Special',
                ])
                ->label('Category')
                ->preload(),

            Select::make('difficulty')
                ->required()
                ->options([
                    'Easy' => 'Easy',
                    'Medium' => 'Medium',
                    'Hard' => 'Hard',
                    'Legendary' => 'Legendary',
                ])
                ->label('Difficulty/Rarity'),

            TextInput::make('xp_reward')
                ->numeric()
                ->required()
                ->minValue(0)
                ->label('XP Reward'),

            Toggle::make('is_active')
                ->default(true)
                ->label('Active')
                ->helperText('Deactivate to hide from users'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('icon')
                    ->label('Icon')
                    ->width('80px'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Learning' => 'blue',
                        'Streak' => 'red',
                        'Milestones' => 'purple',
                        'Levels' => 'green',
                        'XP' => 'yellow',
                        'Social' => 'pink',
                        'Special' => 'cyan',
                        default => 'gray',
                    }),

                TextColumn::make('difficulty')
                    ->sortable()
                    ->badge()
                    ->label('Rarity')
                    ->color(fn(string $state): string => match ($state) {
                        'Easy' => 'green',
                        'Medium' => 'blue',
                        'Hard' => 'orange',
                        'Legendary' => 'purple',
                        default => 'gray',
                    }),

                TextColumn::make('xp_reward')
                    ->label('XP')
                    ->sortable()
                    ->suffix(' XP'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Created'),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'Learning' => 'Learning',
                        'Streak' => 'Streak',
                        'Milestones' => 'Milestones',
                        'Levels' => 'Levels',
                        'XP' => 'XP',
                        'Social' => 'Social',
                        'Special' => 'Special',
                    ])
                    ->preload(),

                SelectFilter::make('difficulty')
                    ->options([
                        'Easy' => 'Easy',
                        'Medium' => 'Medium',
                        'Hard' => 'Hard',
                        'Legendary' => 'Legendary',
                    ])
                    ->preload(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\RewardResource\Pages\ListRewards::route('/'),
            'create' => \App\Filament\Resources\RewardResource\Pages\CreateReward::route('/create'),
            'edit' => \App\Filament\Resources\RewardResource\Pages\EditReward::route('/{record}/edit'),
        ];
    }
}
