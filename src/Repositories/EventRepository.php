<?php namespace Rapide\LaravelApmEvents\Repositories;


use Illuminate\Support\Collection;
use Jenssegers\Agent\Agent;
use Rapide\LaravelApmEvents\Contracts\Decorators\EventDecoratorContract;
use Rapide\LaravelApmEvents\Contracts\Factories\ClientFactoryContract;
use Rapide\LaravelApmEvents\Contracts\Repositories\EventRepositoryContract;
use Rapide\LaravelApmEvents\Contracts\Repositories\IndexRepositoryContract;
use Rapide\LaravelApmEvents\Contracts\SchemaContract;
use Rapide\LaravelApmEvents\Jobs\SaveEvent;

class EventRepository implements EventRepositoryContract
{
    /**
     * @var Agent
     */
    protected $agent;
    /**
     * @var IndexRepositoryContract
     */
    protected $indexRepository;
    /**
     * @var EventDecoratorContract
     */
    protected $eventDecorator;
    /**
     * @var ClientFactoryContract
     */
    protected $clientFactory;


    /**
     * EventRepository constructor.
     * @param Agent $agent
     * @param IndexRepositoryContract $indexRepository
     * @param EventDecoratorContract $eventDecorator
     * @param ClientFactoryContract $clientFactory
     */
    public function __construct(
        Agent $agent,
        IndexRepositoryContract $indexRepository,
        EventDecoratorContract $eventDecorator,
        ClientFactoryContract $clientFactory
    ) {

        $this->agent = $agent;
        $this->indexRepository = $indexRepository;
        $this->eventDecorator = $eventDecorator;
        $this->clientFactory = $clientFactory;
    }

    /**
     * @param SchemaContract $schema
     */
    public function create(SchemaContract $schema)
    {
        $indexName = $this->indexRepository->buildIndexName($schema->getEventName());

        $eventData = $this->eventDecorator->decorate($schema->getParameters());

        $params = [
            'indexname' => $indexName,
            'eventName' => $schema->getEventName(),
            'eventData' => $eventData
        ];

        // dont record event if robot
        if (!$this->agent->isRobot()) {
            dispatch(new SaveEvent($params));
        }

    }

    public function all(SchemaContract $scheme)
    {
    }

    public function read()
    {

    }

    public function update()
    {

    }

    public function delete(SchemaContract $schema)
    {
        $params = [
            'index' => $this->indexRepository->build($schema->getEventName())
        ];

        $client = $this->clientFactory->getClient();

        return $client->indices()->delete($params);
    }

    /**
     * @param $search_result
     * @return Collection
     */
    protected function convertToCollection($search_result): Collection
    {

        $data = $search_result['hits']['hits'];
        $tmp = array();
        if (is_array($data)) {
            foreach ($data as $item) {
                $cur_item = $item['_source'];
                $cur_item['id'] = $item['_id'];
                $tmp[] = $cur_item;
            }
        } else {
            $tmp[] = $data['_source'];
        }
        return new Collection($tmp);
    }
}
