<?php

namespace Lkn\HookNotification\Custom\HooksData;

use Lkn\HookNotification\Domains\Platform\Abstracts\HookDataParser;

/**
 * This class should only serve only for holding the hook data.
 *
 * @since 2.0.0
 */
final class QuoteClientUnregistered extends HookDataParser
{
    public function __construct(
        public readonly array $raw,
        public readonly int $quoteId,
        public readonly string $quoteStatus,
        public readonly string $quoteSubject,
        public readonly int $clientId,
        public readonly string $quoteEmail,
        public readonly string $phoneNumber,
        public readonly string $firstName,
    ) {
        //
    }
}
