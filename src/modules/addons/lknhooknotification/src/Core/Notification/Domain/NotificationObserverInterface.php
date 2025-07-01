<?php

namespace Lkn\HookNotification\Core\Notification\Domain;

use Lkn\HookNotification\Core\Platforms\Common\AbstractPlatform;

interface NotificationObserverInterface
{
    /**
     * Fired when a notification is sent.
     *
     * @param  AbstractNotification $notification
     * @param  NotificationTemplate $template
     * @param  AbstractPlatform     $platform
     * @return void
     */
    public function onNotificationSent(
        AbstractNotification $notification,
        NotificationTemplate $template,
        AbstractPlatform $platform
    ): void;
}
