<?php

namespace Rapide\LaravelApmEvents\Contracts\Repositories;

use Rapide\LaravelApmEvents\Schemas\BaseSchema;

interface EventRepository
{
    /**
     * @param $eventName
     * @param $eventData
     */
    public function create(BaseSchema $schema, $eventData);

    /**
     * @param $event_name
     * @return mixed
     */
    public function all(BaseSchema $schema);

    public function read();

    public function update();

    /**
     * @param $eventName
     * @return mixed
     */
    public function delete(BaseSchema $schema);
}
