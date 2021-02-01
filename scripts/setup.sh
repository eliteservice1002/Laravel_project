#!/usr/bin/env sh

createuser nomoo_user
createdb nomoo --owner=nomoo_user

php artisan migrate --seed
php artisan passport:install
