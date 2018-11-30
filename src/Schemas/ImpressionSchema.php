<?php

namespace Rapide\LaravelApmEvents\Schemas;

class ImpressionSchema extends Schema
{

    protected $eventName = 'impression';

    protected $mapping = [
        'element' =>
            [
                'type' => 'string',
                'index' => 'not_analyzed'
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
