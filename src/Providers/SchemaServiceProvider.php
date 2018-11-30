<?php namespace Rapide\LaravelApmEvents\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Rapide\LaravelApmEvents\Contracts\SchemaManagerContract;

class SchemaServiceProvider extends LaravelServiceProvider
{
    protected $defer = true;

    /**
     * Register schemas into your application
     *
     * @var array
     */
    protected $schemas = [];

    public function getSchemas(): array
    {
        return $this->schemas;
    }

    public function register()
    {
        $this->app->resolving(SchemaManagerContract::class, function ($schemaManager) {
            foreach ($this->getSchemas() as $schema) {
                $schemaManager->register($schema);
            }
        });
    }

    public function provides()
    {
        return [
            SchemaManagerContract::class
        ];
    }
}
