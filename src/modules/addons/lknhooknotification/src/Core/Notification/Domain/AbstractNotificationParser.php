<?php

namespace Lkn\HookNotification\Core\Notification\Domain;

use Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient;
use Lkn\HookNotification\Core\Shared\Infrastructure\Result;

abstract class AbstractNotificationParser
{
    public function __construct(
        public null|BaseApiClient $baseApiClient = null
    ) {
    }

    abstract public function parse(
        AbstractNotification $notification,
        NotificationTemplate $template,
        ?BaseApiClient $apiClient = null,
    ): array|Result;
}
