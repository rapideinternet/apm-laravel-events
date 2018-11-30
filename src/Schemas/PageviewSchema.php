<?php

namespace Rapide\LaravelApmEvents\Schemas;

class PageviewSchema extends Schema
{
    protected $eventName = 'pageview';

    protected $mapping = [
        'page' =>
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
