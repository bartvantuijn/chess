<?php

namespace Database\Seeders;

use App\Models\Computer;
use App\Models\Opening;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Computers
        $computers = [

            [
                'name' => 'Menno',
                'avatar' => 'menno.png',
                'rating' => 250,
                'message' => 'Zet je stukken verstandig; ik investeer in overwinningen!'
            ],
            [
                'name' => 'Boyd',
                'avatar' => 'boyd.png',
                'rating' => 250,
                'message' => 'Dit spel is als mijn acties: onverwacht en briljant.'
            ],
            [
                'name' => 'Tim',
                'avatar' => 'tim.png',
                'rating' => 500,
                'message' => 'Je meubels zijn misschien strak, maar mijn schaakspel nog strakker!'
            ],
            [
                'name' => 'Bart',
                'avatar' => 'bart.png',
                'rating' => 500,
                'message' => 'Schaak en structuur, ik excelleer in beide. Durf jij tegen mij te spelen?'
            ],
            [
                'name' => 'Hikaru',
                'avatar' => 'hikaru.png',
                'rating' => 750,
                'message' => 'Ben je klaar voor wat schaakmagie?'
            ],
            [
                'name' => 'Mittens',
                'avatar' => 'mittens.png',
                'rating' => 750,
                'message' => 'Je zult mijn klauwen voelen!'
            ],
        ];

        collect($computers)->each(fn ($data) => Computer::factory()->create($data));

        // Openings
        $openings = [
            [
                'name' => 'Sicilian Defense',
                'orientation' => 'black',
                'pgn' => '1. e4 c5',
                'message' => 'Een uitdagende en populaire opening!'
            ],
            [
                'name' => 'Ruy Lopez',
                'orientation' => 'white',
                'pgn' => '1. e4 e5 2. Nf3 Nc6 3. Bb5',
                'message' => 'Klassiek en elegant, een tijdloze keuze.'
            ],
        ];

        collect($openings)->each(fn ($data) => Opening::factory()->create($data));

        // Users
        User::factory()->create([
            'name' => 'Venton',
            'role' => 'admin',
            'email' => 'development@venton.systems',
            'password' => Hash::make('GQ2VPZnPwFCc-zADF4Ht8M9tN.'),
        ]);
    }
}
