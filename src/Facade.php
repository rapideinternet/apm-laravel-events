<?php namespace Rapide\LaravelApmEvents;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return ApmEvents::class;
    }
}
