<?php

namespace Lkn\HookNotification\Core\Platforms\EvolutionApi\Domain;

use Lkn\HookNotification\Core\Notification\Domain\AbstractNotification;
use Lkn\HookNotification\Core\Notification\Domain\AbstractNotificationParser;
use Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate;
use Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient;
use Lkn\HookNotification\Core\Shared\Infrastructure\Result;

/**
 * This should return the platform-api-specific paylod based om
 *  NotificationTemplate->platformPayload.
 */
final class EvolutionApiNotificationParser extends AbstractNotificationParser
{
    public function parse(
        AbstractNotification $notification,
        NotificationTemplate $template,
        ?BaseApiClient $apiClient = null,
    ): array|Result {
        return [];
    }
}
