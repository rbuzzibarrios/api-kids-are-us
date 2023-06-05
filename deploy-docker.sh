composer install --prefer-dist --no-progress --no-suggest
php artisan key:generate --force
php artisan optimize
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
sail up -d
sail php artisan migrate --seed --force