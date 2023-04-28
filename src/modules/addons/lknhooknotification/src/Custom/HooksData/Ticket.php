<?php

namespace Lkn\HookNotification\Custom\HooksData;

use Lkn\HookNotification\Domains\Platform\Abstracts\HookDataParser;

/**
 * This class should only serve only for holding the hook data.
 *
 * @since 2.0.0
 */
final class Ticket extends HookDataParser
{
    public function __construct(
        public readonly array $raw,
        public readonly string $ticketId,
        public readonly string $clientAccess,
        public readonly int $clientId
    ) {
        //
    }
}
