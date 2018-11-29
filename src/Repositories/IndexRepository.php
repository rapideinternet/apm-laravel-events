<?php


namespace Rapide\LaravelApmEvents\Repositories;


class IndexRepository implements \Rapide\LaravelApmEvents\Contracts\Repositories\IndexRepository
{
    public function buildIndexName($eventName)
    {
        return sprintf('%s-%s-%s-v%d-%s',
            config('apm-events.prefix'),
            config('apm-events.app_id'),
            $eventName,
            config('apm-events.version'),
            date("Y.m")
        );
    }
}
