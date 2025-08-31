<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameResource\Pages;
use App\Models\Game;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GameResource extends Resource
{
    protected static ?string $model = Game::class;

    protected static ?string $navigationIcon = 'fas-trophy';

    public static function getModelLabel(): string
    {
        return __('Game');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Games');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //Tables\Columns\Layout\Split::make([
                Tables\Columns\TextColumn::make('opponent')
                    ->label(__('Opponent'))
                    ->getStateUsing(fn (Game $record): string => $record->getOpponent()),
                Tables\Columns\TextColumn::make('gameable_type')
                    ->label(__('Type'))
                    ->formatStateUsing(fn (string $state): string => __(class_basename($state))),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __(str_replace('_', ' ', ucfirst($state)))),
                Tables\Columns\TextColumn::make('turn')
                    ->label(__('Turn'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __(ucfirst($state))),
                Tables\Columns\TextColumn::make('pgn')
                    ->label(__('PGN'))
                    ->wrap(),
                //])->from('md')
            ])
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id())->orWhereHas('gameable', function ($query) {
                $query->where('id', auth()->id());
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageGames::route('/'),
        ];
    }
}
