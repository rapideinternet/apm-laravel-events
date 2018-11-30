<?php

if (!function_exists('apmevent')) {
    /**
     * Dispatch an apm event to elasticsearch.
     *
     * @param $schema
     * @param null $params
     * @return mixed
     */
    function apmevent($schema, $params = null)
    {
        return app('apm-events')->event($schema)->insert($params);
    }
}
