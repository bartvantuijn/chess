<p align="center">
    <img src="public/images/logo.svg" width="250" alt="Venton Logo">
</p>

An online chess platform built with [Laravel][laravel] and [Filament][filament], featuring multiplayer games, computer opponents, openings, leveling, and leaderboards.

---

> Please note that **Chess** is under active development

## Demo

An example is available at [chess.bartvantuijn.nl](https://chess.bartvantuijn.nl)

## Installation

Clone this repository and install dependencies:

```bash
git clone https://github.com/bartvantuijn/chess.git
cd chess
composer install --no-scripts
npm install && npm run build
```

## Configuration

Copy the example environment file and update values to match your setup:

```bash
cp .env.example .env
php artisan key:generate
```

Run migrations:

```bash
php artisan migrate
```

Start the app:

```bash
php artisan serve
```

Access the application:

```
http://127.0.0.1:8000
```

If you want seeded demo data, run:

```bash
php artisan migrate:fresh --seed
```

The seeder will create demo computers, openings and a test user:

- **Email**: test@example.com
- **Password**: password

---

### License

Chess is licensed under the _Functional Source License, Version 1.1_. It's free to use for
internal and non-commercial purposes, but it's not allowed to use a release for commercial purposes (competing use). See our [full license][license] for more details.

### Contributing

This project is under active development. Contributions are welcome.

[laravel]: https://laravel.com
[filament]: https://filamentphp.com
[license]: LICENSE.md
