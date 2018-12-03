<?php namespace Rapide\LaravelApmEvents;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Rapide\LaravelApmEvents\Subscribers\EventSubscriber;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

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


        $this->app->singleton(Contracts\ApmEventsContract::class, ApmEvents::class);
        $this->app->singleton('apm-events', Contracts\ApmEventsContract::class);

        $this->app->singleton(Contracts\SchemaManagerContract::class, function ($app) {
            return new SchemaManager($app);
        });

        $this->registerDecorators();
        $this->registerFactories();
        $this->registerRepositories();
        $this->registerSchemas();
        $this->registerCommands();
        $this->registerEventSubscriber();

    }

    protected function registerEventSubscriber()
    {
        if (config('apm-events.event_subscriber') === true) {
            $this->app->singleton(Subscribers\EventSubscriber::class, function () {
                $apm = app(ApmEvents::class);

                return new EventSubscriber($apm);
            });

            app(Dispatcher::class)->subscribe(EventSubscriber::class);
        }

    }

    protected function registerDecorators()
    {
        $this->app->bind(Contracts\Decorators\EventDecoratorContract::class,
            Decorators\EventDecorator::class);
        $this->app->bind(Contracts\Decorators\SchemaMappingDecoratorContract::class,
            Decorators\SchemaMappingDecorator::class);
    }

    protected function registerFactories()
    {
        $this->app->bind(Contracts\Factories\ClientFactoryContract::class,
            Factories\ClientFactory::class);
    }

    protected function registerRepositories()
    {

        $this->app->bind(Contracts\Repositories\EventRepositoryContract::class,
            Repositories\EventRepository::class);
        $this->app->bind(Contracts\Repositories\IndexRepositoryContract::class,
            Repositories\IndexRepository::class);
    }

    protected function registerSchemas()
    {
        $this->app->resolving(Contracts\SchemaManagerContract::class, function ($schemaManager) {
            foreach (config('apm-events.event_schemas') as $schema) {
                $schemaManager->register($schema);
            }
        });
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
            Contracts\ApmEventsContract::class .
            'apm-events',
            Contracts\SchemaManagerContract::class,
            Contracts\Repositories\EventRepositoryContract::class,
            Contracts\Repositories\IndexRepositoryContract::class,
            Contracts\Decorators\EventDecoratorContract::class,
            Contracts\Decorators\SchemaMappingDecoratorContract::class,
            Commands\CreateSchema::class,
            Commands\ResetCommand::class
        ];
    }
}
