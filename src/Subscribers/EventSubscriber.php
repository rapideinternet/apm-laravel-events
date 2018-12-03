<?php

namespace Rapide\LaravelApmEvents\Subscribers;

use Rapide\LaravelApmEvents\ApmEvents;
use Rapide\LaravelApmEvents\Schemas\Schema;
use Rapide\LaravelApmEvents\ShouldFireApm;

class EventSubscriber
{
    /**
     * @var ApmEvents
     */
    protected $apmEvents;

    /**
     * EventSubscriber constructor.
     * @param ApmEvents $apmEvents
     */
    public function __construct(ApmEvents $apmEvents)
    {
        $this->apmEvents = $apmEvents;
    }

    /**
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen('*', static::class . '@handle');
    }

    /**
     * @param string $eventName
     * @param $payload
     */
    public function handle(string $eventName, $payload)
    {
        if (!$this->shouldFireApm($eventName)) {
            return;
        }

        $this->handleEvent($payload[0]);
    }

    /**
     * @param ShouldFireApm $event
     */
    public function handleEvent(ShouldFireApm $event)
    {
        $content = $event->toApmSchema();

        if ($content instanceof Schema) {
            return $this->sendSchema($content);
        }

        if (is_array($content)) {
            foreach ($content as $schema) {
                if ($schema instanceof Schema) {
                    $this->sendSchema($schema);
                }
            }
        }
    }

    /**
     * @param Schema $schema
     * @return mixed
     */
    protected function sendSchema(Schema $schema)
    {
        return $this->apmEvents->event($schema)->insert();
    }

    /**
     * @param $event
     * @return bool
     */
    protected function shouldFireApm($event): bool
    {
        if (!class_exists($event)) {
            return false;
        }
        return is_subclass_of($event, ShouldFireApm::class);
    }
}
