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
                'message' => 'Elke zet is een investering in de toekomst. Durf jij de uitdaging aan?',
            ],
            [
                'name' => 'Boyd',
                'avatar' => 'boyd.png',
                'rating' => 250,
                'message' => 'Mijn stijl is chaotisch, maar mijn winst is zeker. Ben je voorbereid?',
            ],
            [
                'name' => 'Tim',
                'avatar' => 'tim.png',
                'rating' => 500,
                'message' => 'Ik hou van strakke lijnen, ook op het schaakbord. Kan jij mijn perfectie evenaren?',
            ],
            [
                'name' => 'Bart',
                'avatar' => 'bart.png',
                'rating' => 500,
                'message' => 'Schaak en structuur, ik excelleer in beide. Durf jij tegen mij te spelen?',
            ],
            [
                'name' => 'Hikaru',
                'avatar' => 'hikaru.png',
                'rating' => 750,
                'message' => 'Ben je klaar voor wat schaakmagie?',
            ],
            [
                'name' => 'Mittens',
                'avatar' => 'mittens.png',
                'rating' => 750,
                'message' => 'Je zult mijn klauwen voelen!',
            ],
        ];

        collect($computers)->each(fn ($data) => Computer::factory()->create($data));

        // Openings
        $openings = [
            [
                'name' => 'Sicilian Defense',
                'orientation' => 'black',
                'pgn' => '1. e4 c5',
                'message' => 'Een uitdagende en populaire opening!',
            ],
            [
                'name' => 'French Defense',
                'orientation' => 'black',
                'pgn' => '1. e4 e6',
                'message' => 'Een solide verdediging met veel strategie.',
            ],
            [
                'name' => 'Ruy Lopez',
                'orientation' => 'white',
                'pgn' => '1. e4 e5 2. Nf3 Nc6 3. Bb5',
                'message' => 'Klassiek en elegant, een tijdloze keuze.',
            ],
            [
                'name' => 'Caro-Kann Defense',
                'orientation' => 'black',
                'pgn' => '1. e4 c6',
                'message' => 'Bekend om zijn solide structuur.',
            ],
            [
                'name' => 'Italian Game',
                'orientation' => 'white',
                'pgn' => '1. e4 e5 2. Nf3 Nc6 3. Bc4',
                'message' => 'Een klassieke en agressieve opening.',
            ],
            [
                'name' => 'Sicilian Defense: Closed',
                'orientation' => 'white',
                'pgn' => '1. e4 c5 2. Nc3',
                'message' => 'Een rustige maar sterke variant.',
            ],
            [
                'name' => 'Scandinavian Defense',
                'orientation' => 'black',
                'pgn' => '1. e4 d5',
                'message' => 'Een direct en agressief antwoord.',
            ],
            [
                'name' => 'Pirc Defense',
                'orientation' => 'black',
                'pgn' => '1. e4 d6 2. d4 Nf6',
                'message' => 'Flexibel met veel tegenaanvalmogelijkheden.',
            ],
            [
                'name' => 'Sicilian Defense: Alapin Variation',
                'orientation' => 'white',
                'pgn' => '1. e4 c5 2. c3',
                'message' => 'Een rustige aanpak tegen de Sicilian.',
            ],
            [
                'name' => 'Alekhine\'s Defense',
                'orientation' => 'black',
                'pgn' => '1. e4 Nf6',
                'message' => 'Provocerend en creatief spel.',
            ],
            [
                'name' => 'King\'s Gambit',
                'orientation' => 'white',
                'pgn' => '1. e4 e5 2. f4',
                'message' => 'Een gedurfde en explosieve opening.',
            ],
            [
                'name' => 'Scotch Game',
                'orientation' => 'white',
                'pgn' => '1. e4 e5 2. Nf3 Nc6 3. d4',
                'message' => 'Open en snel in het middenspel.',
            ],
            [
                'name' => 'Vienna Game',
                'orientation' => 'white',
                'pgn' => '1. e4 e5 2. Nc3',
                'message' => 'Subtiel en vol verrassingen.',
            ],
            [
                'name' => 'Queen\'s Gambit',
                'orientation' => 'white',
                'pgn' => '1. d4 d5 2. c4',
                'message' => 'Een van de meest bekende en strategische openingen.',
            ],
            [
                'name' => 'Slav Defense',
                'orientation' => 'black',
                'pgn' => '1. d4 d5 2. c4 c6',
                'message' => 'Een solide verdediging tegen het Damegambiet.',
            ],
            [
                'name' => 'King\'s Indian Defense',
                'orientation' => 'black',
                'pgn' => '1. d4 Nf6 2. c4 g6',
                'message' => 'Een dynamische verdediging met veel flexibiliteit.',
            ],
            [
                'name' => 'Nimzo-Indian Defense',
                'orientation' => 'black',
                'pgn' => '1. d4 Nf6 2. c4 e6 3. Nc3 Bb4',
                'message' => 'Een verfijnde verdediging met strategische diepte.',
            ],
            [
                'name' => 'Queen\'s Indian Defense',
                'orientation' => 'black',
                'pgn' => '1. d4 Nf6 2. c4 e6 3. Nf3 b6',
                'message' => 'Een strategisch en positioneel alternatief.',
            ],
            [
                'name' => 'Catalan Opening',
                'orientation' => 'white',
                'pgn' => '1. d4 Nf6 2. c4 e6 3. g3',
                'message' => 'Bekend om zijn lange-diagonaal controle.',
            ],
            [
                'name' => 'Bogo-Indian Defense',
                'orientation' => 'black',
                'pgn' => '1. d4 Nf6 2. c4 e6 3. Nf3 Bb4+',
                'message' => 'Een minder bekende maar solide keuze.',
            ],
            [
                'name' => 'Grünfeld Defense',
                'orientation' => 'black',
                'pgn' => '1. d4 Nf6 2. c4 g6 3. Nc3 d5',
                'message' => 'Een dynamische verdediging met tegenspel.',
            ],
            [
                'name' => 'Dutch Defense',
                'orientation' => 'black',
                'pgn' => '1. d4 f5',
                'message' => 'Een agressieve verdediging gericht op initiatief.',
            ],
            [
                'name' => 'Trompowsky Attack',
                'orientation' => 'white',
                'pgn' => '1. d4 Nf6 2. Bg5',
                'message' => 'Een vroege aanval met tactische mogelijkheden.',
            ],
            [
                'name' => 'Benko Gambit',
                'orientation' => 'black',
                'pgn' => '1. d4 Nf6 2. c4 c5 3. d5 b5',
                'message' => 'Een positioneel gambiet met druk op de damevleugel.',
            ],
            [
                'name' => 'London System',
                'orientation' => 'white',
                'pgn' => '1. d4 d5 2. Nf3 Nf6 3. Bf4',
                'message' => 'Solide en gemakkelijk te leren.',
            ],
            [
                'name' => 'Benoni Defense: Modern Variation',
                'orientation' => 'black',
                'pgn' => '1. d4 Nf6 2. c4 c5 3. d5 e6',
                'message' => 'Een dynamische verdediging met asymmetrisch spel.',
            ],
            [
                'name' => 'Réti Opening',
                'orientation' => 'white',
                'pgn' => '1. Nf3',
                'message' => 'Flexibel en positioneel sterk.',
            ],
            [
                'name' => 'English Opening',
                'orientation' => 'white',
                'pgn' => '1. c4',
                'message' => 'Een positionele keuze gericht op de damevleugel.',
            ],
            [
                'name' => 'Bird\'s Opening',
                'orientation' => 'white',
                'pgn' => '1. f4',
                'message' => 'Een agressieve flankaanval.',
            ],
            [
                'name' => 'King\'s Indian Attack',
                'orientation' => 'white',
                'pgn' => '1. Nf3 d5 2. g3',
                'message' => 'Een flexibel systeem tegen veel opstellingen.',
            ],
            [
                'name' => 'King\'s Fianchetto Opening',
                'orientation' => 'white',
                'pgn' => '1. g3',
                'message' => 'Een rustige, hypermoderne opening.',
            ],
            [
                'name' => 'Nimzowitsch-Larsen Attack',
                'orientation' => 'white',
                'pgn' => '1. b3',
                'message' => 'Een onorthodoxe aanval op de lange diagonaal.',
            ],
            [
                'name' => 'Polish Opening',
                'orientation' => 'white',
                'pgn' => '1. b4',
                'message' => 'Een agressieve en verrassende flankaanval.',
            ],
            [
                'name' => 'Grob Opening',
                'orientation' => 'white',
                'pgn' => '1. g4',
                'message' => 'Een unieke en provocerende keuze.',
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
