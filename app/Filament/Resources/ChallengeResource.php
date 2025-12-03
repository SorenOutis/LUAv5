<?php

namespace App\Filament\Resources;

use BackedEnum;
use UnitEnum;
use App\Models\Challenge;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChallengeResource extends Resource
{
    protected static ?string $model = Challenge::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Challenges';
    protected static string|UnitEnum|null $navigationGroup = 'Gamification';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')->required(),
            Textarea::make('description')->rows(4),
            TextInput::make('difficulty'),
            TextInput::make('points')->numeric()->default(0),
            Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('difficulty'),
                TextColumn::make('points')->numeric(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('start_date')->date(),
            ])
            ->filters([])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\ChallengeResource\Pages\ListChallenges::route('/'),
            'create' => \App\Filament\Resources\ChallengeResource\Pages\CreateChallenge::route('/create'),
            'edit' => \App\Filament\Resources\ChallengeResource\Pages\EditChallenge::route('/{record}/edit'),
        ];
    }
}
