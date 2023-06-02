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

[My Collection **Kids Are Us** in POSTMAN](https://orange-crescent-248105.postman.co/workspace/My-Workspace~820c0ff7-7dfe-48d6-8071-82ed5c480805/collection/7088065-7e4f91e7-b8fa-425e-b360-bb0bc09d6dbb?action=share&creator=7088065)

## Running tests

To run the API Kids Are Us tests

```sh
cp .env.example .env.testing
```

and run:

```sh
sail php artisan optimize --env=testing
``` 

```sh
sail php artisan migrate:fresh --seed --env=testing
```

```sh
sail php artisan test --env=testing
```

or to run parallel tests:

```sh
sail php artisan test -p --env=testing
```