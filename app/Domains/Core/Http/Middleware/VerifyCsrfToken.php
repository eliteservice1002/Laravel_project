<?php

namespace App\Domains\Core\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        // The URIs that should be excluded from CSRF verification.
    ];
}
