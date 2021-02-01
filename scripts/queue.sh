#!/usr/bin/env sh

php artisan queue:work --queue=high,default,low
