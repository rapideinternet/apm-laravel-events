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


        $this->app->bind('apm-events', function () {
            return new ApmEvents;
        });

        $this->registerRepositories();
        $this->registerCommands();

    }

    protected function registerRepositories()
    {
        $this->app->bind(Contracts\Decorators\EventDecorator::class, Decorators\EventDecorator::class);

        $this->app->bind(Contracts\Repositories\EventRepository::class, Repositories\EventRepository::class);
        $this->app->bind(Contracts\Repositories\IndexRepository::class, Repositories\IndexRepository::class);
    }

    protected function registerCommands()
    {
        $this->commands([
            \Rapide\LaravelApmEvents\Commands\CreateSchema::class,
            \Rapide\LaravelApmEvents\Commands\ResetCommand::class
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
            Contracts\Repositories\EventRepository::class,
            Contracts\Repositories\IndexRepository::class,
            'apm-events',
            'command.rapide.apm-events.create_schema',
            'command.rapide.apm-events.reset'
        ];
    }
}
