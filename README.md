
# Nomoo Platform

## Setup

Please ensure you have the following dependencies installed:

* PHP 8.0
  
```shell
brew install php
pecl install redis
```

* Postgres (>= 12)
* Redis (>= 5)
* Composer 2

If you are using [Laravel Valet](https://laravel.com/docs/8.x/valet), run to set up the domain:

```shell
valet tld lvh.me # Update TLD to lvh.me which resolves to localhost
valet link nomoo --secure # Map nomoo.lvh.me to the project 
```

To set up the database, run the following scripts:

```shell
./scripts/refresh-db.sh
```
