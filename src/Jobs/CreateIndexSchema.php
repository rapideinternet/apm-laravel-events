<?php

namespace Rapide\LaravelApmEvents\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;
use Rapide\LaravelApmEvents\Contracts\Factories\ClientFactoryContract;

class CreateIndexSchema implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function handle(ClientFactoryContract $clientFactory, LoggerInterface $log)
    {
        $client = $clientFactory->getClient();

        try {
            $client->indices()->create($this->params['mappings']);
        } catch (Exception $e) {
            $log->error($e->getMessage());
        }

    }
}
