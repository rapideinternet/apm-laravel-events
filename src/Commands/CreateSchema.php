<?php

namespace Rapide\LaravelApmEvents\Commands;

use Illuminate\Console\Command;
use Rapide\LaravelApmEvents\ClientFactory;
use Rapide\LaravelApmEvents\Contracts\Decorators\SchemaMappingDecorator;
use Rapide\LaravelApmEvents\Contracts\Repositories\IndexRepository;
use Rapide\LaravelApmEvents\Jobs\CreateIndexSchema;
use Rapide\LaravelApmEvents\Jobs\CreateIndexTemplate;
use Rapide\LaravelApmEvents\Schemas\Schema;

class CreateSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apm-events:create_schema';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create ElasticSearch Schema for the events';
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
    public function handle(ClientFactory $clientFactory, SchemaMappingDecorator $schemaMappingDecorator)
    {
        $this->info('Creating the Schema for the apm-events events');
        $this->info('<comment>Connecting to ES Server:</comment> ' . config('apm-events.hosts')[0]);

        foreach (config('apm-events.event_schemas') as $schema_class) {
            /** @var Schema $cur_schema */
            $cur_schema = new $schema_class;
            $event_schema = $cur_schema->getEventName();
            $properties = $cur_schema->getMappings();

            $indexName = $this->indexRepository->buildIndexName($event_schema);
            $properties = $schemaMappingDecorator->decorate($properties);

            $client = $clientFactory->getClient();

            $mappings = array(
                'index' => $indexName,
                'body' => array(
                    'settings' => array(
                        'number_of_shards' => config('apm-events.number_of_shards'),
                        'number_of_replicas' => config('apm-events.number_of_replicas')
                    ),
                    'mappings' => [$event_schema => ['properties' => $properties]]
                )
            );


            if ($client->indices()->exists(['index' => $indexName])) {
                $this->info($indexName . ' already exists. no need to create');
            } else {
                $this->info('Building the schema: ' . $indexName);
                dispatch(new CreateIndexSchema(['mappings' => $mappings]));
                dispatch(new CreateIndexTemplate(['mappings' => $mappings]));
            }
        }

        $this->info('Done');
    }
}
