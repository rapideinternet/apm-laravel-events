<?php

namespace Rapide\LaravelApmEvents\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;
use Rapide\LaravelApmEvents\ClientFactory;

class CreateIndexSchema implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function handle(ClientFactory $clientFactory, LoggerInterface $log)
    {
        $params = $this->params['mappings'];
        $client = $clientFactory->getClient();
        
        try {
            $client->indices()->create($params);
        } catch (\Exception $e) {
            $log->error($e->getMessage());
        }

    }
}
