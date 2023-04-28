<?php

namespace Lkn\HookNotification\Custom\HooksData;

use Lkn\HookNotification\Domains\Platform\Abstracts\HookDataParser;

/**
 * This class should only serve only for holding the hook data.
 *
 * @since 2.0.0
 */
final class Invoice extends HookDataParser
{
    public function __construct(
        public readonly int $invoiceId,
        public readonly int $clientId,
        public readonly string $currencyPrefix,
        public readonly int $invoiceTotal,
        public readonly string $invoiceDueDate
    ) {
        //
    }
}
