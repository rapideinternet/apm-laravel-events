<?php namespace Rapide\LaravelApmEvents\Repositories;


use Illuminate\Support\Collection;
use Jenssegers\Agent\Agent;
use Rapide\LaravelApmEvents\Decorators\EventDecorator;
use Rapide\LaravelApmEvents\Jobs\SaveEvent;


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
    public function create($eventName, $eventData)
    {
        $indexname = $this->indexRepository->buildIndexName($eventName);

        $eventData = $this->eventDecorator->decorate($eventData);

        $params = [
            'indexname' => $indexname,
            'eventName' => $eventName,
            'eventData' => $eventData
        ];

        // dont record event if robot
        if (!$this->agent->isRobot()) {
            dispatch(new SaveEvent($params));
        }

    }

    public function all($event_name)
    {
    }

    public function read()
    {

    }

    public function update()
    {

    }

    public function delete($eventName)
    {
        $params = [
            'index' => $this->indexRepository->build($eventName)
        ];

        //$response = $this->client->indices()->delete($params);
    }

    /**
     * @param $search_result
     * @return Collection
     */
    protected function convert_to_collection($search_result): Collection
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
