<?php

namespace Lkn\HookNotification\Core\Notification\Infrastructure\Observers;

use Lkn\HookNotification\Core\Notification\Domain\AbstractNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationObserverInterface;
use Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate;
use Lkn\HookNotification\Core\Platforms\Chatwoot\Application\ChatwootNotificationListenerService;
use Lkn\HookNotification\Core\Platforms\Common\AbstractPlatform;
use Lkn\HookNotification\Core\Platforms\Common\Infrastructure\PlatformFactory;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;

final class ChatwootNotificationObserver implements NotificationObserverInterface
{
    public function onNotificationSent(
        AbstractNotification $notification,
        NotificationTemplate $template,
        AbstractPlatform $platform
    ): void {
        /** @var ChatwootPlatform $chatwootPlatform */
        $chatwootPlatform = (new PlatformFactory)->make(Platforms::CHATWOOT);

        if ($chatwootPlatform->platformSettings->listenSendAsPrivateNote) {
            (new ChatwootNotificationListenerService($chatwootPlatform))->run($notification);
        }
    }
}
