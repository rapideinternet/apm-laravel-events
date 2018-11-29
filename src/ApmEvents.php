<?php namespace Rapide\LaravelApmEvents;

use Rapide\LaravelApmEvents\Contracts\Repositories\EventRepository;

class ApmEvents
{
    /**
     * @var EventRepository
     */
    protected $eventRepository;

    private $current_event;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function event($event_name)
    {
        $this->current_event = $event_name;

        //Find event type

        return $this;
    }

    public function insert($element, $params)
    {
        $params['element'] = $element;
        return $this->eventRepository->create($this->current_event, $params);
    }

    public function get()
    {
        return $this->eventRepository->all($this->current_event);
    }

    public function first()
    {

    }

    public function pluck($name)
    {

    }

    public function select()
    {

    }

    public function addSelect($attribute)
    {

    }

    public function distinct()
    {

    }

    public function lists($attribute, $keyColumn = null)
    {

    }

    public function where($attribute, $operator, $value)
    {

    }

    public function orWhere($attribute, $operator, $value)
    {

    }

    public function whereBetween($attribute, array $values)
    {

    }

    public function whereNotBetween($attribute, array $values)
    {

    }

    public function whereIn($attribute, array $values)
    {

    }

    public function whereNotIn($attribute, array $values)
    {

    }

    public function whereNull($attribute)
    {

    }

    public function orderBy($attribute, $order)
    {

    }

    public function groupBy($attribute)
    {

    }

    public function having($attribute, $operator, $value)
    {

    }

    public function skip($num)
    {

    }

    public function take($num)
    {

    }

    public function count()
    {

    }

    public function delete()
    {
        $this->eventRepository->delete($this->current_event);
    }
}
