<?php

namespace App\Domains\Core\Providers;

use App\Domains\Tenants\Services\StoreSwitcher;
use App\Domains\Tenants\Services\TenantSwitcher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;
use Spatie\NovaTranslatable\Translatable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TenantSwitcher::class);
        $this->app->singleton(StoreSwitcher::class);

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return str_replace('Models', 'Models\\Factories', $modelName).'Factory';
        });
    }

    public function boot(): void
    {
        Translatable::defaultLocales(['ar', 'en']);
    }
}
