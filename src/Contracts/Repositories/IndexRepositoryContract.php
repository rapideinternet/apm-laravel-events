<?php

namespace Rapide\LaravelApmEvents\Contracts\Repositories;

interface IndexRepositoryContract
{
    public function buildIndexName($eventName): string;
}
