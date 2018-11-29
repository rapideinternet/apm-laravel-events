<?php

namespace Rapide\LaravelApmEvents\Schemas;

class ClickSchema extends BaseSchema
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
        return [
            'element' => [
                "type" => "string",
                "index" => "not_analyzed"
            ]
        ];
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
