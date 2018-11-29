<?php

namespace Rapide\LaravelApmEvents\Schemas;

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
}
