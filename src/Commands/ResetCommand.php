<?php

namespace Rapide\LaravelApmEvents\Commands;

use Illuminate\Console\Command;
use Rapide\LaravelApmEvents\ClientFactory;
use Rapide\LaravelApmEvents\Indices\IndexNameBuilder;
use Rapide\LaravelApmEvents\Repositories\IndexRepository;
use Rapide\LaravelApmEvents\Schemas\BaseSchema;

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
     * @var IndexRepository
     */
    protected $indexRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct(IndexRepository $indexRepository)
    {
        parent::__construct();
        $this->indexRepository = $indexRepository;
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


        if ($this->confirm('Are you sure you want to delete all documents, indices and templates of your application?')) {

            $client = ClientFactory::getClient();

            foreach (config('apm-events.event_schemas') as $schema_class) {
                /** @var BaseSchema $cur_schema */
                $cur_schema = new $schema_class;
                $event_schema = $cur_schema->getEventName();
                $properties = $cur_schema->getMappings();

                $indexname = $this->indexRepository->buildIndexName($event_schema);

                if ($client->indices()->exists(['index' => $indexname])) {
                    try {
                        $params = ['index' => $indexname];
                        $response = $client->indices()->delete($params);

                    } catch (\Exception $e) {
                        $this->error($e->getMessage());
                    }
                    $this->info($indexname . " deleted");
                } else {
                    $this->info($indexname . " doesnt exists, skipping");
                }
            }
            $this->info("Reset Success!");

            $this->call('apm-events:create_schema');

        } else {
            $this->info("Operation aborted");
        }
    }
}
