<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComputerResource\Pages;
use App\Models\Computer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ComputerResource extends Resource
{
    protected static ?string $model = Computer::class;

    protected static ?string $navigationIcon = 'fas-robot';

    public static function getModelLabel(): string
    {
        return __('Computer');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Computers');
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required(),
                Forms\Components\TextInput::make('avatar')
                    ->label(__('Avatar'))
                    ->required(),
                Forms\Components\TextInput::make('rating')
                    ->label(__('Rating'))
                    ->columnSpan('full')
                    ->required()
                    ->integer(),
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
                    ->label(__('Name')),
                Tables\Columns\TextColumn::make('avatar')
                    ->label(__('Avatar')),
                Tables\Columns\TextColumn::make('rating')
                    ->label(__('Rating'))
                    ->icon('heroicon-o-sparkles'),
                //])->from('md')
            ])
            ->defaultSort('rating')
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
            'index' => Pages\ManageComputers::route('/'),
        ];
    }
}
