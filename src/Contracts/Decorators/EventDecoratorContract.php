<?php

namespace Rapide\LaravelApmEvents\Contracts\Decorators;

interface EventDecoratorContract
{
    public function decorate($eventData);
}
