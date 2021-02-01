<?php

namespace App\Domains\Core\Providers;

use App\Domains\Core\Services\Livewire\LivewireComponentsFinder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Livewire\Component;
use Livewire\LivewireManager;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class DomainsServiceProvider extends ServiceProvider
{
    protected Collection $domains;

    public function register(): void
    {
        parent::register();

        $this->domains = collect((new Finder())->in(app_path('Domains'))->depth('< 1')->directories())
            ->map(fn (SplFileInfo $path) => $path->getFilename());

        $this->app->singleton(\Livewire\LivewireComponentsFinder::class, function () {
            $manifestPath = config('livewire.manifest_path') ?: (app(LivewireManager::class)->isOnVapor()
                ? '/tmp/storage/bootstrap/cache/livewire-components.php'
                : app()->bootstrapPath('cache/livewire-components.php'));

            return new LivewireComponentsFinder(new Filesystem(), $manifestPath, $this->livewireComponents()->all());
        });
    }

    public function boot(Schedule $schedule): void
    {
        $this->morphMap();
        $this->observers();
        $this->subscribers();
        $this->console();
        $this->schedule($schedule);
        $this->novaResources();
    }

    protected function morphMap(): void
    {
        foreach ($this->domains as $domain) {
            try {
                $this->getClassesFrom(app_path("Domains/{$domain}/Models"))
                    ->filter(fn ($class) => ! (new ReflectionClass($class))->isAbstract())
                    ->map(fn ($model) => [Str::snake(Str::studly(class_basename($model))) => $model])
                    ->each(Relation::class.'::morphMap');
            } catch (ReflectionException $e) {
            }
        }
    }

    protected function observers(): void
    {
        foreach ($this->domains as $domain) {
            $this->getClassesFrom(app_path("Domains/{$domain}/Observers"))
                ->filter(fn ($class) => ! (new ReflectionClass($class))->isAbstract())
                ->each(function (string $observer) {
                    $model = Str::replaceLast('Observers\\', 'Models\\', Str::beforeLast($observer, 'Observer'));

                    if (in_array(Model::class, class_parents($model))) {
                        call_user_func($model.'::observe', [$observer]);
                    }
                });
        }
    }

    protected function subscribers(): void
    {
        foreach ($this->domains as $domain) {
            $this->getClassesFrom(app_path("Domains/{$domain}/Subscribers"))
                ->filter(fn ($class) => ! (new ReflectionClass($class))->isAbstract())
                ->each(function (string $subscriber) {
                    Event::subscribe($subscriber);
                });
        }
    }

    protected function console(): void
    {
        foreach ($this->domains as $domain) {
            $this->getClassesFrom(app_path("Domains/{$domain}/Console/Commands"))
                ->filter(fn ($class) => ! (new ReflectionClass($class))->isAbstract())
                ->each(fn (string $command) => $this->commands($command));
        }
    }

    protected function schedule(Schedule $schedule): void
    {
    }

    protected function novaResources(): void
    {
        foreach ($this->domains as $domain) {
            $resourcesPath = app_path("Domains/{$domain}/Nova");

            if (is_dir($resourcesPath)) {
                Nova::serving(fn (ServingNova $event) => Nova::resourcesIn($resourcesPath));
            }
        }
    }

    protected function livewireComponents(): Collection
    {
        return collect(['Core'])
            ->map(function (string $domain) {
                $componentsPath = app_path("Domains/{$domain}/Http/Livewire");

                return $this->getClassesFrom($componentsPath)->values();
            })
            ->flatten()
            ->filter(function ($class): bool {
                return is_subclass_of($class, Component::class)
                    && ! (new ReflectionClass($class))->isAbstract();
            });
    }

    protected function getClassesFrom(string $path): Collection
    {
        if ( ! is_dir($path)) {
            return collect([]);
        }

        return collect((new Finder())->in($path)->depth('< 1')->files())
            ->map(fn (SplFileInfo $filePath) => $this->getClassFrom($filePath->getPathname()));
    }

    protected function getClassFrom($path): string
    {
        $classFilePath = Str::after($path, app_path().DIRECTORY_SEPARATOR);

        return app()->getNamespace().str_replace(['/', '.php'], ['\\', ''], $classFilePath);
    }
}
