<?php

namespace Rapide\LaravelApmEvents\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;
use Rapide\LaravelApmEvents\ClientFactory;

class SaveEvent implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function handle(ClientFactory $clientFactory, LoggerInterface $log)
    {
        $params = [
            'index' => $this->params['indexname'],
            'type' => $this->params['eventName'],
            'body' => $this->params['eventData'],
            'client' => [
                'timeout' => config('apm-events.query_timeout'),
                'connect_timeout' => config('apm-events.connect_timeout')
            ]
        ];

        try {
            $client = $clientFactory->getClient();
            $client->index($params);
        } catch (Exception $e) {
            $log->error('Save Event: ' . $e->getMessage());
        }
    }
}
