<?php

namespace Rapide\LaravelApmEvents\Contracts;

interface Schema
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
     * @param $params
     * @return bool
     */
    public function validate($params): bool;
}
