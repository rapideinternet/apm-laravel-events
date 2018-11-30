<?php

namespace Rapide\LaravelApmEvents\Contracts\Factories;

use Elasticsearch\Client;

interface ClientFactoryContract
{
    /**
     * @return Client
     */
    public function getClient(): Client;
}
