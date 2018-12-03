<?php

namespace Rapide\LaravelApmEvents\Schemas;

class LoginSchema extends Schema
{
    protected $eventName = 'login';

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
