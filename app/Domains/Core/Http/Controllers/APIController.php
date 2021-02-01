<?php

namespace App\Domains\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class APIController
{
    public function user(Request $request)
    {
        return $request->user();
    }

    public function fallback(): Response
    {
        return new Response('{}', Response::HTTP_NOT_FOUND);
    }
}
