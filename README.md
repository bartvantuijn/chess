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
composer install
npm install && npm run build
```

## Configuration

Copy the example environment file and update values to match your setup:

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database and mail configuration before continuing.

Run migrations:

```bash
php artisan migrate
```

Create an initial admin user:

```bash
php artisan make:filament-user
```

Start the app:

```bash
php artisan serve
```

---

### License

Noton is licensed under the _Functional Source License, Version 1.1, MIT Future License_. It's free to use for
internal and non-commercial purposes, but it's not allowed to use a release for commercial purposes (competing use). See our [full license][license] for more details.

### Contributing

This project is under active development. Contributions are welcome.

[laravel]: https://laravel.com
[filament]: https://filamentphp.com
[license]: LICENSE.md
