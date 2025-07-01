<?php

namespace Lkn\HookNotification\Core\Platforms\Common;

use Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportStatus;

final class PlatformNotificationSendResult
{
    /**
     * @param  NotificationReportStatus $status
     * @param  string|null              $msg
     * @param  string|null              $target This can be a phone number, WhatsApp phone number, email.
     */
    public function __construct(
        public NotificationReportStatus $status,
        public ?string $msg = null,
        public ?string $target = null,
    ) {
    }
}
