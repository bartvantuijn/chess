<?php

namespace App\Filament\Widgets;

use App\Models\Computer;
use App\Models\Game;
use App\Models\Opening;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;

class Chessboard extends Widget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 1;

    protected $listeners = [
        'saveGame' => 'saveGame',
    ];

    public function saveGame(int $depth, ?array $computer, ?array $opening, ?array $game, string $pgn, string $status): void
    {
        $user = auth()->user();

        if (!$user) {
            return;
        }

        if ($game) {
            $game = Game::find($game['id']);

            $game->update([
                'status' => $status,
                'turn' => $game->getOpponentTurn(),
                'pgn' => !empty($pgn) ? $pgn : null,
            ]);
        }

        if ($computer) {
            if ($status !== 'completed') {
                return;
            }

            $computer = Computer::find($computer['id']);

            $game = $user->games()->make([
                'status' => $status,
                'pgn' => !empty($pgn) ? $pgn : null,
            ]);

            $game->gameable()->associate($computer);
            $game->save();
            $user->addExperience($depth);
        }

        if ($opening) {
            if ($status !== 'completed') {
                return;
            }

            $opening = Opening::find($opening['id']);

            $game = $user->games()->make([
                'status' => 'completed',
                'pgn' => !empty($pgn) ? $pgn : null,
            ]);

            $game->gameable()->associate($opening);
            $game->save();
            $user->addExperience($depth);
        }

        $this->dispatch('refresh');
    }

    public function render(): View
    {
        return view('filament.widgets.chessboard');
    }
}
