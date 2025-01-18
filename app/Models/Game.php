<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Game extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gameable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUserTurn(): string
    {
        if ($this->user_id === auth()->id()) {
            return 'black';
        } else {
            return 'white';
        }
    }

    public function getOpponent(): string
    {
        if ($this->user_id === auth()->id()) {
            return $this->gameable->name;
        } else {
            return $this->user->name;
        }
    }

    public function getOpponentTurn(): string
    {
        if ($this->user_id === auth()->id()) {
            return 'white';
        } else {
            return 'black';
        }
    }

    public function isDisabled(): bool
    {
        return $this->turn !== $this->getUserTurn();
    }
}
