<?php

namespace Rapide\LaravelApmEvents\Commands;

use Illuminate\Console\Command;
use Rapide\LaravelApmEvents\Indices\IndexNameBuilder;
use Rapide\LaravelApmEvents\Jobs\CreateIndexSchema;
use Rapide\LaravelApmEvents\ClientFactory;

class ResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apm-events:reset';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete all ElasticSearch documents, indices and templates.';
    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $idxbuilder;

    public function __construct()
    {
        parent::__construct();
        $this->idxbuilder = new IndexNameBuilder;
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('initiating..');
        $this->info('<comment>Connecting to ES Server:</comment> ' . config('apm-events.hosts')[0]);


        if($this->confirm('Are you sure you want to delete all documents, indices and templates of your application?'))
        {

            $client = ClientFactory::getClient();

            foreach(config('apm-events.event_schemas') as $schema_class)
            {

                $cur_schema = new $schema_class;
                $event_schema = $cur_schema->getEventName();
                $properties = $cur_schema->getMappings();

                $indexname =  $this->idxbuilder->build($event_schema);

                if($client->indices()->exists(['index' => $indexname]))
                {
                    try{
                        $params = ['index' => $indexname];
                        $response = $client->indices()->delete($params);

                    }catch(\Exception $e){
                        $this->error($e->getMessage());
                    }
                    $this->info( $indexname . " deleted");
                }
                else
                    $this->info( $indexname . " doesnt exists, skipping");
            }
            $this->info("Reset Success!");

            $this->call('apm-events:create_schema');

        }
        else
            $this->info("Operation aborted");
    }
}
