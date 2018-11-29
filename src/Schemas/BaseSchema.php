<?php

namespace Rapide\LaravelApmEvents\Schemas;

abstract class BaseSchema implements \Rapide\LaravelApmEvents\Contracts\Schema
{
    public function validate($params): bool
    {
        return count($this->getMappings()) == count(array_intersect_key($this->getMappings(), $params));
    }

}
