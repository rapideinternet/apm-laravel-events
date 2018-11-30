<?php

namespace Rapide\LaravelApmEvents\Contracts;

interface SchemaContract extends \ArrayAccess
{
    /**
     * @return string
     */
    public function getEventName(): string;

    /**
     * @return array
     */
    public function getMappings(): array;

    /**
     * @return array
     */
    public function getSettings(): array;

    /**
     * @return bool
     */
    public function validate(): bool;

    /**
     * @param array $array
     * @return $this
     */
    public function setParameters(array $array);

    /**
     * @return array
     */
    public function getParameters(): array;

    public function setParameter($key, $value);
}
