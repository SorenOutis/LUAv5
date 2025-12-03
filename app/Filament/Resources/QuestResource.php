<?php

namespace App\Filament\Resources;

use BackedEnum;
use App\Models\Quest;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuestResource extends Resource
{
    protected static ?string $model = Quest::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')
                ->required()
                ->label('Quest Title'),
            Textarea::make('description')
                ->required()
                ->rows(4)
                ->label('Description'),
            TextInput::make('xp')
                ->numeric()
                ->required()
                ->label('Experience Points (XP)'),
            Select::make('difficulty')
                ->required()
                ->options([
                    'Easy' => 'Easy',
                    'Medium' => 'Medium',
                    'Hard' => 'Hard',
                    'Legendary' => 'Legendary',
                ])
                ->label('Difficulty'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('description')->limit(50),
                TextColumn::make('xp')->label('XP')->sortable(),
                TextColumn::make('difficulty')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\QuestResource\Pages\ListQuests::route('/'),
            'create' => \App\Filament\Resources\QuestResource\Pages\CreateQuest::route('/create'),
            'edit' => \App\Filament\Resources\QuestResource\Pages\EditQuest::route('/{record}/edit'),
        ];
    }
}
