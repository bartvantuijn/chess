<?php

namespace App\Filament\Resources\OpeningResource\Pages;

use App\Filament\Resources\OpeningResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageOpenings extends ManageRecords
{
    protected static string $resource = OpeningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
