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

Generate application key:

```sh
php artisan key:generate
```

Optimize application

```sh
php artisan optimize
```
Create alias for sail command

```sh
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

Run Sail:

```sh
sail up -d
```

### Open new terminal console

Create alias for sail command

```sh
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

Run database migrations and database seeder, :

```sh
sail php artisan migrate --seed --force
```

### Deployed. 

Visit: POST: [http://localhost/api/v1/login](http://localhost/api/v1/login) in Postman


## Running tests

To run the API Kids Are Us tests

```sh
cp .env.example .env.testing
```

and run:

```
sail php artisan optimize --env=testing
``` 

```
sail php artisan migrate:fresh --seed --env=testing
```

```
sail php artisan test --env=testing
```

or to run parallel tests:

```
sail php artisan test -p --env=testing
```