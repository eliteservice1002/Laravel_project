<?php

namespace App\Domains\Core\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    protected $except = [
        // The names of the cookies that should not be encrypted.
    ];
}
