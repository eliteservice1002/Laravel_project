<?php

namespace App\Domains\Core\Http\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SetLocale
{
    public const SESSION_KEY = 'locale';

    private $supportedLocales = ['ar', 'en'];

    public function __construct(
        protected Repository $config,
    ) {
    }

    public function handle(Request $request, Closure $next)
    {
        $session = $request->getSession();

        $locale = $request->header('Content-Language');

        if ($request->has('lang')) {
            $locale = strtolower($request->get('lang'));
        } elseif ($session && $session->has(self::SESSION_KEY)) {
            $locale = $session->get(self::SESSION_KEY);
        }

        if (in_array($locale, $this->supportedLocales)) {
            app()->setLocale($locale);
            if ($session) {
                $session->put(self::SESSION_KEY, $locale);
            }
        }

        $fallbackLocale = Arr::first($this->supportedLocales, fn ($l) => $l !== $locale);
        $this->config->set('app.fallback_locale', $fallbackLocale);

        return $next($request);
    }
}
