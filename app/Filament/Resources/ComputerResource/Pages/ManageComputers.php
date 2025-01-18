<?php

namespace App\Filament\Resources\ComputerResource\Pages;

use App\Filament\Resources\ComputerResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageComputers extends ManageRecords
{
    protected static string $resource = ComputerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
