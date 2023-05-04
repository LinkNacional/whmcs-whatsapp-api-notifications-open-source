<?php

namespace Lkn\HookNotification\Custom\HooksData;

use Lkn\HookNotification\Domains\Platform\Abstracts\HookDataParser;

/**
 * This class should only serve only for holding the hook data.
 *
 * @since 2.0.0
 */
final class OrderPending extends HookDataParser
{
    public function __construct(
        public readonly int $clientId,
        public readonly int $orderId,
        public readonly int $invoiceId,
        public readonly string $orderIdAndProduct,
        public readonly string $invoiceIdAndFirstItem
    ) {
        //
    }
}
