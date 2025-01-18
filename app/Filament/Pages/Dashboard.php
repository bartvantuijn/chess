<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'fas-chess';

    //protected static string $view = 'filament.pages.dashboard';

    public function getColumns(): int | string | array
    {
        return 2;
    }

    public function getTitle(): string
    {
        return __('Play');
    }

    public static function getNavigationLabel(): string
    {
        return __('Play');
    }
}
