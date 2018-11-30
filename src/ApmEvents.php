<?php namespace Rapide\LaravelApmEvents;

use Rapide\LaravelApmEvents\Contracts\ApmEventsContract;
use Rapide\LaravelApmEvents\Contracts\Repositories\EventRepositoryContract;
use Rapide\LaravelApmEvents\Contracts\SchemaContract;
use Rapide\LaravelApmEvents\Contracts\SchemaManagerContract;
use Rapide\LaravelApmEvents\Exceptions\InvalidSchemaException;

class ApmEvents implements ApmEventsContract
{
    /**
     * @var EventRepositoryContract
     */
    protected $eventRepository;
    /**
     * @var SchemaManager
     */
    protected $schemaManager;
    /**
     * @var SchemaContract
     */
    protected $schema;

    public function __construct(EventRepositoryContract $eventRepository, SchemaManagerContract $schemaManager)
    {
        $this->eventRepository = $eventRepository;
        $this->schemaManager = $schemaManager;
    }

    public function event($event)
    {
        if ($event instanceof SchemaContract) {
            $this->schema = $event;
        } else {
            if (!$this->schemaManager->schemaExists($event)) {
                throw new InvalidSchemaException('No schema exists for event [' . $event . ']');
            }

            $this->schema = $this->schemaManager->getSchema($event);
        }

        return $this;
    }

    public function insert($params = null)
    {
        if ($params !== null) {
            $this->schema->setParameters($params);
        }

        $this->validateSchema();

        return $this->eventRepository->create($this->schema);
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

    public function validateSchema()
    {
        if (!$this->schema->validate()) {
            throw new InvalidSchemaException('Missing schema parameters');
        }

        return true;
    }
}
