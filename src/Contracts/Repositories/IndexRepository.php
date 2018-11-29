<?php

namespace Rapide\LaravelApmEvents\Contracts\Repositories;

interface IndexRepository
{
    public function buildIndexName($eventName);
}
