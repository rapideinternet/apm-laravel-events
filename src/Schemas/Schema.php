<?php

namespace Rapide\LaravelApmEvents\Schemas;

use Rapide\LaravelApmEvents\Exceptions\InvalidSchemaException;

abstract class Schema implements \Rapide\LaravelApmEvents\Contracts\SchemaContract
{
    protected $eventName;

    protected $mapping = [];

    protected $settings = [];

    protected $parameters = [];

    /**
     * BaseSchema constructor.
     * @param array $parameters
     */
    public function __construct($parameters = [])
    {
        $this->setParameters(array_merge($this->parameters, $parameters));
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        if ($this->eventName === null) {
            throw new InvalidSchemaException('No valid name for schema');
        }

        return $this->eventName;
    }

    /**
     * @return array
     */
    public function getMappings(): array
    {
        return $this->mapping;
    }

    /**
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return count($this->getMappings()) == count(array_intersect_key($this->getMappings(), $this->parameters));
    }

    /**
     * @param array $array
     * @return $this
     */
    public function setParameters(array $array)
    {
        $this->parameters = $array;

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setParameter($key, $value)
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->parameters[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->parameters[$offset] ?? null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->parameters[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->parameters[$offset]);
    }
}
