<?php

namespace App\Filament\Widgets;

use App\Models\Computer;
use App\Models\Game;
use App\Models\Opening;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class Playground extends Widget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

    protected $listeners = [
        'refresh' => '$refresh',
        'resetPlayground' => 'resetPlayground',
        'selectGame' => 'selectGame',
    ];

    public ?Computer $computer = null;

    public Collection $computers;

    public ?Opening $opening = null;

    public Collection $openings;

    public ?Game $game = null;

    public Collection $games;

    public function mount(): void
    {
        $this->computers = Computer::all();
        $this->openings = Opening::all();

        $this->games = Game::where(function ($query): void {
            $query->where('user_id', auth()->id())->orWhereHas('gameable', function ($query): void {
                $query->where('id', auth()->id());
            });
        })->where('status', '!=', 'completed')->get();
    }

    public function resetPlayground(): void
    {
        $this->computer = null;
        $this->opening = null;
        $this->game = null;
        $this->dispatch('playgroundReset');
    }

    public function selectComputer(string $name): void
    {
        $this->opening = null;
        $this->game = null;
        $this->computer = $this->computers->firstWhere('name', $name);
        $this->dispatch('computerSelected', computer: $this->computer);
    }

    public function selectOpening(string $name): void
    {
        $this->computer = null;
        $this->game = null;
        $this->opening = $this->openings->firstWhere('name', $name);
        $this->dispatch('openingSelected', opening: $this->opening);
    }

    public function selectGame(int $id): void
    {
        $this->computer = null;
        $this->opening = null;
        $this->game = $this->games->firstWhere('id', $id);
        $this->dispatch('gameSelected', game: $this->game, turn: $this->game->getUserTurn());
    }

    public function render(): View
    {
        return view('filament.widgets.playground');
    }
}
