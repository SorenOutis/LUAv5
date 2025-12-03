<?php

namespace App\Filament\Resources;

use BackedEnum;
use UnitEnum;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Users';
    protected static string|UnitEnum|null $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label('Full Name')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->label('Email Address')
                ->email()
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            TextInput::make('password')
                ->label('Password')
                ->password()
                ->required(fn ($context) => $context === 'create')
                ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                ->dehydrated(fn ($state) => filled($state)),
            Textarea::make('profile.bio')
                ->label('Bio')
                ->rows(3)
                ->maxLength(500),
            TextInput::make('profile.total_xp')
                ->label('Total XP')
                ->numeric()
                ->default(0),
            TextInput::make('profile.level')
                ->label('Level')
                ->numeric()
                ->default(1)
                ->minValue(1)
                ->maxValue(100),
            TextInput::make('profile.streak_days')
                ->label('Streak Days')
                ->numeric()
                ->default(0)
                ->minValue(0),
                CheckboxList::make('roles')
                ->relationship('roles', 'name')
                ->searchable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('profile.level')
                    ->label('Level')
                    ->numeric()
                    ->sortable()
                    ->default('—'),
                TextColumn::make('profile.total_xp')
                    ->label('Total XP')
                    ->numeric()
                    ->sortable()
                    ->default('—'),
                TextColumn::make('achievements_count')
                    ->label('Achievements')
                    ->counts('achievements')
                    ->color('success'),
                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M d, Y')
                    ->sortable(),
                
            ])
            ->filters([])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\UserResource\Pages\ListUsers::route('/'),
            'create' => \App\Filament\Resources\UserResource\Pages\CreateUser::route('/create'),
            'edit' => \App\Filament\Resources\UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
