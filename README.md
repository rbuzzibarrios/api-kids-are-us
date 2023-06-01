# API Kids Are Us

## Installation

Clone the repo locally:

```sh
git clone https://github.com/rbuzzibarrios/api-kids-are-us.git
cd api-kids-are-us
```

Setup configuration:

```sh
cp .env.example .env
```

Install PHP dependencies:

```sh
composer install --prefer-dist --no-progress --no-suggest
```

[comment]: <> (Install NPM dependencies:)

[comment]: <> (```sh)

[comment]: <> (npm install)

[comment]: <> (```)

[comment]: <> (or)

[comment]: <> (```sh)

[comment]: <> (yarn install)

[comment]: <> (```)

Generate application key:

```sh
sail php artisan key:generate
```

```sh
sail php artisan optimize
```

Create an MySQL database. You can also use another database (SQLite, Postgres), simply update your configuration accordingly.

Run database migrations and database seeder:

```sh
sail php artisan migrate:fresh --seed
```

Start the server:

[comment]: <> (```sh)

[comment]: <> (npm run dev)

[comment]: <> (```)

[comment]: <> (or)

[comment]: <> (```sh)

[comment]: <> (yarn dev)

[comment]: <> (```)

Run artisan server:

```sh
sail up
```

## Running tests

To run the Booking Event tests

```sh
cp .env.example .env.testing
```

and run:
```
php artisan optimize --env=testing && php artisan migrate:fresh --seed && php artisan test
```
or to run parallel tests:

```
php artisan optimize --env=testing && php artisan migrate:fresh --seed && php artisan test -p
```