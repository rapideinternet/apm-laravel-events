<?php

namespace Rapide\LaravelApmEvents\Contracts;

use Rapide\LaravelApmEvents\Schemas\Schema;

interface SchemaManagerContract
{
    public function register($schema);

    public function createSchema(string $schema): Schema;

    public function schemaExists($eventName): bool;

    public function getSchema($eventName): Schema;

    public function getSchemas(): array;
}
