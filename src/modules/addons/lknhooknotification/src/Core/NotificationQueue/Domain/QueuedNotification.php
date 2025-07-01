<?php

namespace Lkn\HookNotification\Core\NotificationQueue\Domain;

final class QueuedNotification
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $bulkId,
        public readonly ?string $bulkTpl,
        public readonly QueuedNotificationStatus $status,
        public readonly ?string $notificationCode,
        public readonly ?int $clientId,
        public readonly ?array $clientData = null,
        public readonly ?array $reportData = null,
    ) {
    }
}
