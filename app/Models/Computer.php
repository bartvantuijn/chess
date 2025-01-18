<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Computer extends Model
{
    use HasFactory;

    public function games(): MorphMany
    {
        return $this->morphMany(Game::class, 'gameable');
    }

    public function isDisabled(): bool
    {
        if (!auth()->check() || $this->id == self::min('id')) {
            return false;
        }

        return auth()->user()->games()->whereHasMorph('gameable', [self::class], function ($query) {
            $query->where('id', $this->id - 1);
        })->doesntExist();
    }
}
