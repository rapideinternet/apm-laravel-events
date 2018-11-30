<?php

namespace Rapide\LaravelApmEvents\Schemas;

class ClickSchema extends Schema
{
    protected $eventName = 'click';

    protected $mapping = [
        'element' => [
            "type" => "string",
            "index" => "not_analyzed"
        ]
    ];

    protected $settings = [
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
