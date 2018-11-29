<?php namespace Rapide\LaravelApmEvents\Repositories;


use Illuminate\Support\Collection;
use Jenssegers\Agent\Agent;
use Rapide\LaravelApmEvents\Decorators\EventDecorator;
use Rapide\LaravelApmEvents\Jobs\SaveEvent;
use Rapide\LaravelApmEvents\Schemas\BaseSchema;


class EventRepository implements \Rapide\LaravelApmEvents\Contracts\Repositories\EventRepository
{
    /**
     * @var Agent
     */
    protected $agent;
    /**
     * @var IndexRepository
     */
    protected $indexRepository;
    /**
     * @var EventDecorator
     */
    protected $eventDecorator;


    /**
     * EventRepository constructor.
     * @param Agent $agent
     * @param IndexRepository $indexRepository
     */
    public function __construct(Agent $agent, IndexRepository $indexRepository, EventDecorator $eventDecorator)
    {

        $this->agent = $agent;
        $this->indexRepository = $indexRepository;
        $this->eventDecorator = $eventDecorator;
    }

    /**
     * @param $eventName
     * @param $eventData
     */
    public function create(BaseSchema $schema, $eventData)
    {
        $indexName = $this->indexRepository->buildIndexName($schema->getEventName());

        $eventData = $this->eventDecorator->decorate($eventData);

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

    public function all(BaseSchema $schema)
    {
    }

    public function read()
    {

    }

    public function update()
    {

    }

    public function delete(BaseSchema $schema)
    {
        $params = [
            'index' => $this->indexRepository->build($schema->getEventName())
        ];

        //0return $this->client->indices()->delete($params);
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
