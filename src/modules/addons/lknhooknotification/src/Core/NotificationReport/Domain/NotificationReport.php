<?php

namespace Lkn\HookNotification\Core\NotificationReport\Domain;

use DateTime;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;

final class NotificationReport
{
    public function __construct(
        public readonly int $id,
        public readonly ?int $clientId,
        public readonly ?int $categoryId,
        public readonly ?NotificationReportCategory $category,
        public readonly ?NotificationReportStatus $status,
        public readonly ?string $msg,
        public readonly ?Platforms $platform,
        public readonly string $notificationCode,
        public readonly ?Hooks $notificationHook,
        public readonly DateTime $createdAt,
        public readonly ?string $target,
    ) {
    }
}
