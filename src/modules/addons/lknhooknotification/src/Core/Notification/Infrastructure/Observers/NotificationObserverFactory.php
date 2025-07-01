<?php

namespace Lkn\HookNotification\Core\Notification\Infrastructure\Observers;

use Lkn\HookNotification\Core\Shared\Infrastructure\Singleton;

final class NotificationObserverFactory extends Singleton {
    /**
     * @return array<\Lkn\HookNotification\Core\Notification\Domain\NotificationObserverInterface>
     */
    public static function make(): array
    {
        return [new ChatwootNotificationObserver()];
    }
}
