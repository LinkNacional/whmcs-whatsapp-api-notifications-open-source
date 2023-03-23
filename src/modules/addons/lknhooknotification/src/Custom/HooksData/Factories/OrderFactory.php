<?php

namespace Lkn\HookNotification\Custom\HooksData\Factories;

use Lkn\HookNotification\Custom\HooksData\Order;

final class OrderFactory
{
    /**
     * You should call this method in the add_hook callback.
     *
     * @since 1.0.0
     *
     * @param array $raw raw array coming from the callback provided to add_hook().
     *
     * @return \Lkn\HookNotification\Custom\HooksData\Order
     */
    public static function fromHook(array $raw): Order
    {
        /**
         * If you need to do a very custom handling, you should do this here
         * and, in your hook file, you should only call $hookData->veryCustom.
         */
        $orderId = (int) $raw['orderId'];
        $clientId = (int) $raw['userId'];
        $invoiceId = (int) $raw['invoiceId'];

        return new Order($raw, $orderId, $invoiceId, $clientId);
    }
}
