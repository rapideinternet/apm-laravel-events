<?php namespace Rapide\LaravelApmEvents\Decorators;


class SchemaMappingDecorator implements \Rapide\LaravelApmEvents\Contracts\Decorators\SchemaMappingDecorator
{
    public function decorate($mapping)
    {

        if (!isset($mapping['timestamp'])) {
            $mapping['timestamp'] = ['type' => 'date', 'index' => 'not_analyzed'];
        }

        if (!isset($mapping['ip'])) {
            $mapping['ip'] = ['type' => "string", 'index' => "not_analyzed"];
        }

        if (!isset($mapping['user_agent'])) {
            $mapping['user_agent'] = ['type' => "string"];
        }

        if (!isset($mapping['app_id'])) {
            $mapping['app_id'] = ['type' => 'string', 'index' => 'not_analyzed'];
        }

        if (!isset($mapping['app_name'])) {
            $mapping['app_name'] = ['type' => 'string', 'index' => 'not_analyzed'];
        }

        if (!isset($mapping['target'])) {
            $mapping['target'] = ['type' => 'string', 'index' => 'not_analyzed'];
        }

        if (!isset($mapping['device'])) {
            $mapping['device'] = ['type' => 'string', 'index' => 'not_analyzed'];
        }

        if (!isset($mapping['language'])) {
            $mapping['language'] = ['type' => 'string', 'index' => 'not_analyzed'];
        }

        if (!isset($mapping['platform'])) {
            $mapping['platform'] = ['type' => 'string', 'index' => 'not_analyzed'];
        }

        if (!isset($mapping['browser'])) {
            $mapping['browser'] = ['type' => 'string', 'index' => 'not_analyzed'];
        }

        if (!isset($mapping['session_id'])) {
            $mapping['session'] = ['type' => 'string', 'index' => 'not_analyzed'];
        }

        return $mapping;
    }

}
