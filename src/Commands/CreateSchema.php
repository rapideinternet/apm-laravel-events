<?php

namespace Rapide\LaravelApmEvents\Commands;

use Illuminate\Console\Command;
use Rapide\LaravelApmEvents\Contracts\Decorators\SchemaMappingDecoratorContract;
use Rapide\LaravelApmEvents\Contracts\Factories\ClientFactoryContract;
use Rapide\LaravelApmEvents\Contracts\Repositories\IndexRepositoryContract;
use Rapide\LaravelApmEvents\Contracts\SchemaContract;
use Rapide\LaravelApmEvents\Contracts\SchemaManagerContract;
use Rapide\LaravelApmEvents\Jobs\CreateIndexSchema;
use Rapide\LaravelApmEvents\Jobs\CreateIndexTemplate;

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
     * @var IndexRepositoryContract
     */
    protected $indexRepository;

    /**
     * Create a new command instance.
     *
     * @param IndexRepositoryContract $indexRepository
     */

    public function __construct(IndexRepositoryContract $indexRepository)
    {
        parent::__construct();

        $this->indexRepository = $indexRepository;
    }

    /**
     * Execute the console command.
     *
     * @param ClientFactoryContract $clientFactory
     * @param SchemaManagerContract $schemaManager
     * @param SchemaMappingDecoratorContract $schemaMappingDecorator
     * @return mixed
     */
    public function handle(
        ClientFactoryContract $clientFactory,
        SchemaManagerContract $schemaManager,
        SchemaMappingDecoratorContract $schemaMappingDecorator
    ) {
        $this->info('Creating the Schema for the apm-events events');
        $this->info('<comment>Connecting to ES Server:</comment> ' . config('apm-events.hosts')[0]);

        /** @var SchemaContract $schema */
        foreach ($schemaManager->getSchemas() as $schema) {

            $eventSchema = $schema->getEventName();
            $properties = $schema->getMappings();

            $indexName = $this->indexRepository->buildIndexName($eventSchema);
            $properties = $schemaMappingDecorator->decorate($properties);

            $client = $clientFactory->getClient();

            $mappings = array(
                'index' => $indexName,
                'body' => array(
                    'settings' => array(
                        'number_of_shards' => config('apm-events.number_of_shards'),
                        'number_of_replicas' => config('apm-events.number_of_replicas')
                    ),
                    'mappings' => [$eventSchema => ['properties' => $properties]]
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
