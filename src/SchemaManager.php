<?php

namespace Rapide\LaravelApmEvents;


use Illuminate\Contracts\Container\Container;
use Rapide\LaravelApmEvents\Contracts\SchemaManagerContract;
use Rapide\LaravelApmEvents\Schemas\Schema;

class SchemaManager implements SchemaManagerContract
{
    protected $schemas = [];
    /**
     * @var Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function register($schema)
    {
        $callable = $this->createSchema($schema);
        $this->schemas[$callable->getEventName()] = $callable;
    }

    public function createSchema(string $schema): Schema
    {
        return $this->container->make($schema);
    }

    public function schemaExists($eventName): bool
    {
        return isset($this->schemas[$eventName]);
    }

    public function getSchema($eventName): Schema
    {
        return $this->schemas[$eventName];
    }

    /**
     * @return array
     */
    public function getSchemas(): array
    {
        return $this->schemas;
    }
}
