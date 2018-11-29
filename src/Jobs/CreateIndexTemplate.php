<?php

namespace Rapide\LaravelApmEvents\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;
use Rapide\LaravelApmEvents\ClientFactory;

class CreateIndexTemplate implements ShouldQueue
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

        // get the index name and unset it
        $template_name = $params['index'];
        unset($params['index']);

        // strip off the dates
        $template_name = substr($template_name, 0, -7);

        $params['name'] = 'apm-events-' . md5($template_name);
        $params['body']['template'] = $template_name . "*";

        $client = $clientFactory->getClient();

        try {
            $client->indices()->putTemplate($params);
        } catch (\Exception $e) {
            $log->error($e->getMessage());
        }

    }
}
