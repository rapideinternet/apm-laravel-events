<?php

namespace Rapide\LaravelApmEvents\Schemas;

class ClickSchema implements Schema
{
    /**
     * @return string
     */
    public function getEventName(): string
    {
        return 'click';
    }

    /**
     * @return array
     */
    public function getMappings(): array
    {
        return ['element' => ["type" => "string", "index" => "not_analyzed"]];
    }
}
