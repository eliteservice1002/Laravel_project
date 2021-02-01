<?php

namespace App\Domains\Core\Providers;

use App\Domains\Core\Nova\Dashboards\Inventory;
use App\Domains\Core\Nova\Dashboards\Main;
use App\Domains\Tenants\Models\TenantUser;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    public function register(): void
    {
        Nova::ignoreMigrations();
    }

    public function boot(): void
    {
        Nova::$resetsPasswords = true;
        // $this->routes();

        Nova::serving(function (ServingNova $event) {
            $this->registerExceptionHandler();
            $this->authorization();
            $this->tools();
            $this->dashboards();

            if (in_array(app()->getLocale(), ['ar'])) {
                Nova::style('nova-rtl', asset('css/nova/rtl.css'));
                Nova::script('nova-rtl', asset('js/nova/rtl.js'));
            }
        });
    }

    public function authorization(): void
    {
        Gate::define('viewNova', fn ($user) => $user instanceof TenantUser);

        Nova::auth(function ($request) {
            return app()->environment('local')
                || Gate::check('viewNova', [$request->user()]);
        });
    }

    public function tools(): void
    {
        Nova::tools([
            \ChrisWare\NovaBreadcrumbs\NovaBreadcrumbs::make(),
            \Mirovit\NovaNotifications\NovaNotifications::make(),
        ]);
    }

    public function dashboards(): void
    {
        Nova::dashboards([
            new Main(),
            new Inventory(),
        ]);
    }

    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes();
    }
}
