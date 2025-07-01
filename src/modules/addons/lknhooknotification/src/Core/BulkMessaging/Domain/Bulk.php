<?php

namespace Lkn\HookNotification\Core\BulkMessaging\Domain;

use DateTime;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;

final class Bulk
{
    public function __construct(
        public readonly int $id,
        public readonly BulkStatus $status,
        public readonly string $title,
        public readonly ?string $description,
        public readonly Platforms $platform,
        public readonly DateTime $startAt,
        public readonly int $maxConcurrency,
        public readonly array $filters,
        public readonly float $progress,
        public readonly DateTime $createdAt,
        public readonly ?DateTime $completedAt,
        public readonly string $template,
        public readonly ?array $platformPayload,
    ) {
    }
}
