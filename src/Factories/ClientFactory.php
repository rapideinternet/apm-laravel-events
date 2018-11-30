<?php

namespace Rapide\LaravelApmEvents\Factories;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Log\Logger;
use Psr\Log\LoggerInterface;
use Rapide\LaravelApmEvents\Contracts\Factories\ClientFactoryContract;

class ClientFactory implements ClientFactoryContract
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
     * @return Client
     */
    public function getClient(): Client
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
