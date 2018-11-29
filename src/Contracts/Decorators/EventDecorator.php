<?php

namespace Rapide\LaravelApmEvents\Contracts\Decorators;

interface EventDecorator
{
    public function decorate($eventData);
}
