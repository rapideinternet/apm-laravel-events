<?php

namespace Rapide\LaravelApmEvents\Commands;

use Exception;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;
use Rapide\LaravelApmEvents\Contracts\Factories\ClientFactoryContract;
use Rapide\LaravelApmEvents\Contracts\Repositories\IndexRepositoryContract;
use Rapide\LaravelApmEvents\Contracts\SchemaContract;
use Rapide\LaravelApmEvents\Contracts\SchemaManagerContract;

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
     * @param LoggerInterface $log
     * @return mixed
     */
    public function handle(
        ClientFactoryContract $clientFactory,
        SchemaManagerContract $schemaManager
    ) {
        $this->info('initiating..');
        $this->info('<comment>Connecting to ES Server:</comment> ' . config('apm-events.hosts')[0]);


        if ($this->confirm('Are you sure you want to delete all documents, indices and templates of your application?')) {

            $client = $clientFactory->getClient();

            foreach ($schemaManager->getSchemas() as $schema) {
                /** @var SchemaContract $schema */
                $eventName = $schema->getEventName();

                $indexName = $this->indexRepository->buildIndexName($eventName);

                if ($client->indices()->exists(['index' => $indexName])) {
                    try {
                        $params = ['index' => $indexName];

                        $client->indices()->delete($params);
                    } catch (Exception $e) {
                        $this->error($e->getMessage());
                    }
                    $this->info($indexName . " deleted");
                } else {
                    $this->info($indexName . " doesnt exists, skipping");
                }
            }
            $this->info("Reset Success!");

            $this->call('apm-events:create_schema');

        } else {
            $this->info("Operation aborted");
        }
    }
}
