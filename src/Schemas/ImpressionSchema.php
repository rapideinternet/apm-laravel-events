<?php

namespace Rapide\LaravelApmEvents\Schemas;

class ImpressionSchema implements Schema
{

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return 'impression';
    }

    /**
     * @return array
     */
    public function getMappings(): array
    {
        return ['element' => ["type" => "string", "index" => "not_analyzed"]];
    }
}
