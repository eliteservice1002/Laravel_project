#!/usr/bin/env sh

php artisan tenants:each db:wipe || true

php artisan db:wipe

php artisan migrate --path=database/migrations/core --seed

php artisan tenants:each 'migrate --seed'
