<?php namespace Rapide\LaravelApmEvents;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/apm-events.php';
        $this->publishes([$configPath => config_path('apm-events.php')]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/apm-events.php';
        $this->mergeConfigFrom($configPath, 'apm-events');

        $this->registerRepositories();
        $this->registerSchemas();
        $this->registerCommands();

    }

    protected function registerRepositories()
    {
        $this->app->singleton(Contracts\SchemaManager::class, function ($app) {
            return new SchemaManager($app);
        });

        $this->app->bind(Contracts\Decorators\EventDecorator::class, Decorators\EventDecorator::class);
        $this->app->bind(Contracts\Decorators\SchemaMappingDecorator::class, Decorators\SchemaMappingDecorator::class);

        $this->app->bind(Contracts\Repositories\EventRepository::class, Repositories\EventRepository::class);
        $this->app->bind(Contracts\Repositories\IndexRepository::class, Repositories\IndexRepository::class);
    }

    protected function registerSchemas()
    {
        $schemaManager = $this->app->make(Contracts\SchemaManager::class);

        foreach (config('apm-events.event_schemas') as $schema) {
            $schemaManager->register($schema);
        }
    }

    protected function registerCommands()
    {
        $this->commands([
            Commands\CreateSchema::class,
            Commands\ResetCommand::class
        ]);
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Contracts\SchemaManager::class,
            Contracts\Repositories\EventRepository::class,
            Contracts\Repositories\IndexRepository::class,
            Contracts\Decorators\EventDecorator::class,
            Contracts\Decorators\SchemaMappingDecorator::class,
            Commands\CreateSchema::class,
            Commands\ResetCommand::class
        ];
    }
}
