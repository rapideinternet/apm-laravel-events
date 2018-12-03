<?php

namespace Rapide\LaravelApmEvents\Schemas;

class LogoutSchema extends Schema
{
    protected $eventName = 'logout';

    protected $mapping = [
        'user_id' => [
            "type" => "integer",
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
