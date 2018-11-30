<?php

namespace Rapide\LaravelApmEvents\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rapide\LaravelApmEvents\Contracts\Factories\ClientFactoryContract;


class ForceMergeSegments implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function handle(ClientFactoryContract $clientFactory)
    {
        $indexName = $this->params['index_name'];

        $client = $clientFactory->getClient();

        $client->indices()->forceMerge([
            'index' => $indexName,
            'max_num_segments' => 1
        ]);
    }
}
