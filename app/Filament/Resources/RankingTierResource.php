<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RankingTierResource\Pages;
use App\Models\RankingTier;
use BackedEnum;
use UnitEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RankingTierResource extends Resource
{
    protected static ?string $model = RankingTier::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationLabel = 'Ranking Tiers';

    protected static ?string $label = 'Ranking Tier';

    protected static ?string $pluralLabel = 'Ranking Tiers';
    
    protected static string|UnitEnum|null $navigationGroup = 'Gamification';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label('Tier Name')
                ->required()
                ->placeholder('e.g., Radiant, Immortal, Ascendant'),

            TextInput::make('icon')
                ->label('Emoji Icon')
                ->default('📊')
                ->placeholder('e.g., 👑, 💎, ⭐')
                ->maxLength(10),

            ColorPicker::make('color')
                ->label('Tier Color')
                ->default('#999999'),

            TextInput::make('min_rank')
                ->label('Minimum Rank Position')
                ->numeric()
                ->required()
                ->placeholder('e.g., 1'),

            TextInput::make('max_rank')
                ->label('Maximum Rank Position')
                ->numeric()
                ->placeholder('e.g., 5 (leave blank for no limit)'),

            TextInput::make('sort_order')
                ->label('Sort Order')
                ->numeric()
                ->default(0),

            RichEditor::make('description')
                ->label('Description'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tier Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('icon')
                    ->label('Icon')
                    ->getStateUsing(fn($record) => $record->icon . ' ' . $record->name),

                TextColumn::make('min_rank')
                    ->label('Min Rank')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('max_rank')
                    ->label('Max Rank')
                    ->getStateUsing(fn($record) => $record->max_rank ?? '∞')
                    ->sortable(),

                ColorColumn::make('color')
                    ->label('Color'),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRankingTiers::route('/'),
            'create' => Pages\CreateRankingTier::route('/create'),
            'edit' => Pages\EditRankingTier::route('/{record}/edit'),
        ];
    }
}
