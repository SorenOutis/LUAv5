<?php

namespace App\Filament\Resources;

use BackedEnum;
use App\Models\ProgressMetric;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProgressMetricResource extends Resource
{
    protected static ?string $model = ProgressMetric::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Progress Metrics';
    protected static ?string $label = 'Progress Metric';
    protected static ?string $pluralLabel = 'Progress Metrics';
    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->required()
                ->label('Metric Name')
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
                    'Assessments' => 'Assessments',
                    'Courses' => 'Courses',
                    'Consistency' => 'Consistency',
                    'Achievements' => 'Achievements',
                ])
                ->label('Category')
                ->preload(),
            
            TextInput::make('unit')
                ->required()
                ->label('Unit (e.g., "assignments", "hours")')
                ->maxLength(255),
            
            TextInput::make('current')
                ->numeric()
                ->required()
                ->minValue(0)
                ->default(0)
                ->label('Current Value'),
            
            TextInput::make('target')
                ->numeric()
                ->required()
                ->minValue(1)
                ->default(100)
                ->label('Target Value'),
            
            TextInput::make('sort_order')
                ->numeric()
                ->default(0)
                ->label('Sort Order'),
            
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
                    ->color(fn (string $state): string => match ($state) {
                        'Learning' => 'blue',
                        'Assessments' => 'green',
                        'Courses' => 'purple',
                        'Consistency' => 'red',
                        'Achievements' => 'yellow',
                        default => 'gray',
                    }),
                
                TextColumn::make('current')
                    ->label('Current')
                    ->sortable(),
                
                TextColumn::make('target')
                    ->label('Target')
                    ->sortable(),
                
                TextColumn::make('unit')
                    ->label('Unit')
                    ->sortable(),
                
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
                        'Assessments' => 'Assessments',
                        'Courses' => 'Courses',
                        'Consistency' => 'Consistency',
                        'Achievements' => 'Achievements',
                    ])
                    ->preload(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\ProgressMetricResource\Pages\ListProgressMetrics::route('/'),
            'create' => \App\Filament\Resources\ProgressMetricResource\Pages\CreateProgressMetric::route('/create'),
            'edit' => \App\Filament\Resources\ProgressMetricResource\Pages\EditProgressMetric::route('/{record}/edit'),
        ];
    }
}
