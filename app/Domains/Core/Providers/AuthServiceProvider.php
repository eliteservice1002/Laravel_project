<?php

namespace App\Domains\Core\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::guessPolicyNamesUsing(function ($class) {
            $classDirname = str_replace('/', '\\', dirname(str_replace('\\', '/', $class)));

            $classDirnameSegments = explode('\\', $classDirname);
            if ($classDirnameSegments[1] === 'Models') {
                $classDirnameSegments[1] = 'Policies';
            }

            $possibleClassNames = Collection::times(
                count($classDirnameSegments),
                function ($index) use ($class, $classDirnameSegments) {
                    $classDirname = implode('\\', array_slice($classDirnameSegments, 0, $index));

                    return $classDirname.'\\Policies\\'.class_basename($class).'Policy';
                }
            );

            $policies = $possibleClassNames->reverse()->values()->first(function ($class) {
                return class_exists($class);
            }) ?: [$classDirname.'\\Policies\\'.class_basename($class).'Policy'];

            return Arr::wrap($policies);
        });
    }
}
