<?php namespace Rapide\LaravelApmEvents\Decorators;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Rapide\LaravelApmEvents\Enums\DeviceTypeEnum;

class EventDecorator implements \Rapide\LaravelApmEvents\Contracts\Decorators\EventDecorator
{
    /**
     * @var Agent
     */
    protected $agent;
    /**
     * @var Request
     */
    protected $request;

    /**
     * EventDecorator constructor.
     * @param Agent $agent
     */
    public function __construct(Agent $agent, Request $request)
    {
        $this->agent = $agent;
        $this->request = $request;
    }

    public function decorate($eventData)
    {
        if (!isset($eventData['timestamp'])) {
            $eventData['timestamp'] = date("c");
        }

        if (!isset($eventData['ip'])) {
            $eventData['ip'] = request()->ip();
        }

        if (!isset($eventData['app_id'])) {
            $eventData['app_id'] = config('apm-events.app_id');
        }

        if (!isset($eventData['app_name'])) {
            $eventData['app_name'] = config('apm-events.app_name');
        }

        if (!isset($eventData['user_agent'])) {
            $eventData['user_agent'] = optional($this->request)->header('User-Agent');
        }

        if (!isset($eventData['device'])) {
            if ($this->agent->isDesktop()) {
                $eventData['device'] = DeviceTypeEnum::DESKTOP;
            } elseif ($this->agent->isTablet()) {
                $eventData['device'] = DeviceTypeEnum::TABLET;
            } elseif ($this->agent->isMobile()) {
                $eventData['device'] = DeviceTypeEnum::MOBILE;
            } elseif ($this->agent->isRobot()) {
                $eventData['device'] = DeviceTypeEnum::ROBOT;
            } else {
                $eventData['device'] = DeviceTypeEnum::OTHER;
            }
        }

        if (!isset($eventData['language'])) {
            $eventData['language'] = array_get($this->agent->languages(), 0, 'unknown');
        }

        if (!isset($eventData['platform'])) {
            $eventData['platform'] = $this->agent->platform();
        }

        if (!isset($eventData['browser'])) {
            $eventData['browser'] = $this->agent->browser();
        }

        if (!isset($eventData['session_id'])) {
            $eventData['session_id'] = optional($this->request->session())->getId();
        }

        return $eventData;
    }

}
