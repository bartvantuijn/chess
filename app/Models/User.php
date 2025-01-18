<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function openings(): HasMany
    {
        return $this->hasMany(Game::class)->where('gameable_type', Opening::class)->distinct();
    }

    public function computers(): HasMany
    {
        return $this->hasMany(Game::class)->where('gameable_type', Computer::class)->distinct();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function experienceNeeded(): int
    {
        return 100 * pow(1.1, $this->level);
    }

    public function experienceProgress(): int
    {
        return $this->experience / $this->experienceNeeded() * 100;
    }

    public function addExperience(int $depth): void
    {
        $experience = ($depth * 250) + $this->experience;

        while ($experience > $this->experienceNeeded()) {
            $experience -= $this->experienceNeeded();
            $this->level++;
        }

        $this->experience = $experience;
        $this->coins = $this->coins + ($depth * 10);
        $this->save();
    }
}
