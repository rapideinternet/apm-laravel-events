<?php

namespace Rapide\LaravelApmEvents\Contracts\Repositories;

use Rapide\LaravelApmEvents\Contracts\SchemaContract;

interface EventRepositoryContract
{
    /**
     * @param SchemaContract $schema
     * @return mixed
     */
    public function create(SchemaContract $schema);

    /**
     * @param SchemaContract $schema
     * @return mixed
     */
    public function all(SchemaContract $schema);

    /**
     * @param SchemaContract $schema
     * @return mixed
     */
    public function delete(SchemaContract $schema);
}
