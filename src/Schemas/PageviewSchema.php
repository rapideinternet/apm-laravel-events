<?php

namespace Rapide\LaravelApmEvents\Schemas;

class PageviewSchema extends BaseSchema
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

    /**
     * @return array
     */
    public function getSettings(): array
    {
        return [
            'analysis' => [
                'filter' => [

                ],
                'char_filter' => [

                ],
                'analyzer' => [

                ]
            ]
        ];
    }
}
