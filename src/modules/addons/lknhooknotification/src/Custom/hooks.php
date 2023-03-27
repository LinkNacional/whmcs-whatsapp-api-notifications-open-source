<?php

// Doc WHMCS Hook Reference: https://developers.whmcs.com/hooks/hook-index/

use Lkn\HookNotification\Config\Platforms;
use Lkn\HookNotification\Custom\HooksData\Factories\OrderFactory;
use Lkn\HookNotification\Dispatcher;

add_hook('OrderPaid', 1, function ($vars): void {
    $hookData = OrderFactory::fromHook($vars);

    // You can execute the hook files for every platform.
    Dispatcher::runHook('OrderCreated', $hookData);

    // Or you can run the hook for only one platform.
    // Dispatcher::runHookForPlatform('OrderPaid', Platforms::WHATSAPP, $hookData);
});
