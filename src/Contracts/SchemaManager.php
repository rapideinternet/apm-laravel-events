<?php

namespace Rapide\LaravelApmEvents\Contracts;

use Rapide\LaravelApmEvents\Schemas\BaseSchema;

interface SchemaManager
{
    public function register($schema);

    public function createSchema(string $schema): BaseSchema;

    public function schemaExists($eventName): bool;

    public function getSchema($eventName): BaseSchema;

    public function getSchemas(): array;
}
