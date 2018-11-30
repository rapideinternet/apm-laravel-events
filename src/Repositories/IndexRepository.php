<?php

namespace Rapide\LaravelApmEvents\Repositories;

use Rapide\LaravelApmEvents\Contracts\Repositories\IndexRepositoryContract;

class IndexRepository implements IndexRepositoryContract
{
    /**
     * Build an elasticsearch index name based on an eventname
     *
     * @param $eventName
     * @return string
     */
    public function buildIndexName($eventName): string
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
