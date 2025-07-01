<?php

namespace Lkn\HookNotification\Core\Notification\Domain;

use Lkn\HookNotification\Core\Notification\Domain\AbstractNotification;

/**
 * Used for notifications that runs on cron hooks.
 */
abstract class AbstractCronNotification extends AbstractNotification
{
    /**
     * @return array<mixed>
     */
    abstract public function getPayload(): array;
}
