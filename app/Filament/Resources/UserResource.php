<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'fas-ranking-star';

    public static function getNavigationLabel(): string
    {
        return __('Ranking');
    }

    public static function getModelLabel(): string
    {
        return __('Player');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Players');
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
                    Tables\Columns\TextColumn::make('rank')
                        ->label(__('Rank'))
                        ->icon('heroicon-o-hashtag')
                        ->getStateUsing(function ($record) {
                            $users = User::orderByDesc('level')->get();
                            return $users->search(fn ($user) => $user->id == $record->id) + 1;
                        }),
                    Tables\Columns\TextColumn::make('name')
                        ->label(__('Name'))
                        ->sortable()
                        ->searchable(),
                    Tables\Columns\TextColumn::make('level')
                        ->label(__('Level'))
                        ->badge()
                        ->color('gray')
                        ->formatStateUsing(fn (string $state): string => "Level {$state}"),
                    Tables\Columns\TextColumn::make('games_count')
                        ->label(__('Games'))
                        ->icon('heroicon-o-arrow-trending-up')
                        ->counts('games'),
                    Tables\Columns\TextColumn::make('computers_count')
                        ->label(__('Computers'))
                        ->icon('heroicon-o-arrow-trending-up')
                        ->counts('computers'),
                    Tables\Columns\TextColumn::make('openings_count')
                        ->label(__('Openings'))
                        ->icon('heroicon-o-arrow-trending-up')
                        ->counts('openings')
                //])->from('md')
            ])
            ->defaultSort('level', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('challenge')
                    ->label(__('Challenge'))
                    ->icon('heroicon-o-user-plus')
                    ->requiresConfirmation()
                    ->disabled(fn ($record) => !auth()->check() || auth()->id() === $record->id)
                    ->action(function ($record) {
                        $game = auth()->user()->games()->make();
                        $game->gameable()->associate($record);
                        $game->save();

                        Notifications\Notification::make()
                            ->title(__('Good luck'))
                            ->success()
                            ->actions([
                                Notifications\Actions\Action::make('play')
                                    ->label(__('Play'))
                                    ->button()
                                    ->url(route('filament.admin.pages.dashboard')),
                            ])
                            ->send();
                    }),
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
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
