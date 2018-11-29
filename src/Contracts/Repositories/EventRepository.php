<?php

namespace Rapide\LaravelApmEvents\Contracts\Repositories;

interface EventRepository
{
    /**
     * @param $eventName
     * @param $eventData
     */
    public function create($eventName, $eventData);

    /**
     * @param $event_name
     * @return mixed
     */
    public function all($event_name);

    public function read();

    public function update();

    /**
     * @param $eventName
     * @return mixed
     */
    public function delete($eventName);
}
