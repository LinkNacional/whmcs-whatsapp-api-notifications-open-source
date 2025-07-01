<?php

namespace Lkn\HookNotification\Core\Platforms\Common;

use Lkn\HookNotification\Core\Notification\Domain\AbstractNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate;
use Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient;

abstract class BasePlatformApiClient extends BaseApiClient
{
    abstract protected function sendNotification(
        AbstractNotification $notification,
        NotificationTemplate $template
    );
}
