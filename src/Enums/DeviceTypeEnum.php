<?php

namespace Rapide\LaravelApmEvents\Enums;

use MyCLabs\Enum\Enum;

class DeviceTypeEnum extends Enum
{
    const MOBILE = 'mobile';
    const DESKTOP = 'desktop';
    const TABLET = 'tablet';
    const ROBOT = 'robot';
    const OTHER = 'other';
}
