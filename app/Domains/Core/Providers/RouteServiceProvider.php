<?php

namespace App\Domains\Core\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Laravel\Nova\Nova;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->configureRateLimiting();

        // TODO(ibrasho): Use default locale
        URL::defaults(['locale' => 'ar']);

        Route::pattern('locale', 'ar|en');

        $this->routes(function () {
            // Back Office routes.
            Route::name('nova.')
                ->prefix(Nova::path())
                ->group(function () {
                    Route::middleware('web')
                        ->group(base_path('routes/web/backoffice.php'));
                    Route::middleware('api')
                        ->group(base_path('routes/api/backoffice.php'));
                });

            // Store routes.
            Route::name('store.')
                ->group(function () {
                    Route::middleware('web')
                        ->group(base_path('routes/web/store.php'));
                    Route::middleware('api')
                        ->group(base_path('routes/api/store.php'));
                });
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)
                ->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
