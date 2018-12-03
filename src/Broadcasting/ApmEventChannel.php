<?php


namespace Rapide\LaravelApmEvents\ApmEventChannel;


use Illuminate\Notifications\Notification;
use Rapide\LaravelApmEvents\ApmEvents;
use Rapide\LaravelApmEvents\Exceptions\ChannelException;
use Rapide\LaravelApmEvents\Schemas\Schema;

class ApmEventChannel
{
    /**
     * @var ApmEvents
     */
    protected $apmEvents;

    /**
     * ApmEventChannel constructor.
     * @param ApmEvents $apmEvents
     */
    public function __construct(ApmEvents $apmEvents)
    {
        $this->apmEvents = $apmEvents;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toApmEvent($notifiable);

        if (!($message instanceof Schema)) {
            throw new ChannelException('The toApmEvent must return a Schema');
        }

        $this->apmEvents->event($message)->insert();
    }

}
