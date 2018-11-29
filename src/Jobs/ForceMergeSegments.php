<?php

namespace Rapide\LaravelApmEvents\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class ForceMergeSegments implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function handle()
    {
        $index_name = $this->params['index_name'];

        $client = new Client(['timeout' => 2.0]);

        $host = config('apm-events.hosts')[0];
        $uri = 'http://' . $host . ':9200/' . $index_name . "/_forcemerge?max_num_segments=1";

        $r = $client->request('POST', $uri);
    }
}
