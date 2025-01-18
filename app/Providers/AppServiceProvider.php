<?php

namespace App\Providers;

use Carbon\Carbon;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('Carbon', Carbon::class);
        $loader->alias('FilamentAsset', FilamentAsset::class);

        // Register custom colors
        $this->app->singleton('colors.primary', function () {
            return '#262626';
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        // Force HTTPS scheme
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Register custom assets
        FilamentAsset::register([
            Js::make('jquery', Vite::asset('resources/js/jquery.js')),
            Js::make('main', Vite::asset('resources/js/main.js'))->loadedOnRequest(),
            Js::make('chessboard', asset('js/chess/chessboard-1.0.0.min.js')),
            Css::make('chessboard', asset('css/chess/chessboard-1.0.0.min.css')),
            Js::make('chess', asset('js/chess/chess.js')),
        ]);

        // Register favicon render hook
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): View => view('components.layouts.favicon'),
        );

        // Register login render hook
        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_END,
            fn (): string => Blade::render('
            @guest
                <x-filament::button tag="a" href="{{ route(\'filament.admin.auth.login\') }}" color="gray">
                    {{ __(\'Login\') }}
                </x-filament::button>
            @endguest
            '),
        );

        // Register statistics render hook
        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): string => Blade::render('
            @auth
                @livewire(\'statistics\')
            @endauth
            '),
        );

        //Register custom script data
        FilamentAsset::registerScriptData([
            'csrf_token' => csrf_token(),
            'user' => auth()->user()
        ]);
    }
}
