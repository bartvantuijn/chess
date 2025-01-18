<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OpeningResource\Pages;
use App\Filament\Resources\OpeningResource\RelationManagers;
use App\Models\Opening;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OpeningResource extends Resource
{
    protected static ?string $model = Opening::class;

    protected static ?string $navigationIcon = 'fas-chess-board';

    public static function getModelLabel(): string
    {
        return __('Opening');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Openings');
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return "{$record->name} ({$record->orientation})";
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'orientation'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required(),
                Forms\Components\ToggleButtons::make('orientation')
                    ->label(__('Orientation'))
                    ->required()
                    ->grouped()
                    ->default('white')
                    ->options([
                        'white' => __('White'),
                        'black' => __('Black'),
                    ])
                    ->colors([
                        'white' => 'gray',
                        'black' => 'gray',
                    ]),
                Forms\Components\TextInput::make('pgn')
                    ->label(__('PGN')),
                Forms\Components\Textarea::make('message')
                    ->label(ucfirst(__('Message')))
                    ->columnSpan('full')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //Tables\Columns\Layout\Split::make([
                    Tables\Columns\TextColumn::make('name')
                        ->label(__('Name'))
                        ->searchable(),
                    Tables\Columns\TextColumn::make('orientation')
                        ->label(__('Orientation'))
                        ->badge()
                        ->formatStateUsing(fn (string $state): string => __(ucfirst($state))),
                    Tables\Columns\TextColumn::make('pgn')
                        ->label(__('PGN')),
                //])->from('md')
            ])
            ->defaultSort('name')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOpenings::route('/'),
        ];
    }
}
