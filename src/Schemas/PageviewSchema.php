<?php

namespace Rapide\LaravelApmEvents\Schemas;

class PageviewSchema implements Schema
{

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return 'pageview';
    }

    /**
     * @return array
     */
    public function getMappings(): array
    {
        return ['page' => ["type" => "string", "index" => "not_analyzed"]];
    }
}
