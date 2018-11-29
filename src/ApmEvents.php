<?php namespace Rapide\LaravelApmEvents;

use Rapide\LaravelApmEvents\Contracts\Repositories\EventRepository;
use Rapide\LaravelApmEvents\Contracts\SchemaManager;
use Rapide\LaravelApmEvents\Exceptions\InvalidSchemaException;
use Rapide\LaravelApmEvents\Schemas\BaseSchema;

class ApmEvents
{
    /**
     * @var EventRepository
     */
    protected $eventRepository;
    /**
     * @var SchemaManager
     */
    protected $schemaManager;
    /**
     * @var BaseSchema
     */
    protected $schema;

    public function __construct(EventRepository $eventRepository, SchemaManager $schemaManager)
    {
        $this->eventRepository = $eventRepository;
        $this->schemaManager = $schemaManager;
    }

    public function event($eventName)
    {
        if (!$this->schemaManager->schemaExists($eventName)) {
            throw new InvalidSchemaException('No schema exists for event [' . $eventName . ']');
        }

        $this->schema = $this->schemaManager->getSchema($eventName);

        return $this;
    }

    public function insert($params)
    {
        $this->validateSchema($params);

        return $this->eventRepository->create($this->schema, $params);
    }

    public function get()
    {
        return $this->eventRepository->all($this->schema);
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
        $this->eventRepository->delete($this->schema);
    }

    public function validateSchema($params)
    {
        if (!$this->schema->validate($params)) {
            throw new InvalidSchemaException('Missing schema parameters');
        }

        return true;
    }
}
