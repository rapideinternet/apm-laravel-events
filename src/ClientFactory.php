<?php

namespace Rapide\LaravelApmEvents;

use Elasticsearch\ClientBuilder;
use Illuminate\Log\Logger;
use Psr\Log\LoggerInterface;

class ClientFactory
{

    /**
     * @var ClientBuilder
     */
    protected $clientBuilder;
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * ClientFactory constructor.
     * @param ClientBuilder $clientBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(ClientBuilder $clientBuilder, LoggerInterface $logger)
    {
        $this->clientBuilder = $clientBuilder;
        $this->logger = $logger;
    }

    /**
     * @return \Elasticsearch\Client
     */
    public function getClient()
    {
        $hosts = config('apm-events.hosts');

        if (config('apm-events.logging', false)) {
            $client = $this->clientBuilder
                ->setHosts($hosts)
                ->setLogger($this->logger)
                ->build();
        } else {
            $client = $this->clientBuilder
                ->setHosts($hosts)
                ->build();
        }

        return $client;
    }
}
