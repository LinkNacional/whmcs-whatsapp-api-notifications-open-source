<?php

// Doc WHMCS Hook Reference: https://developers.whmcs.com/hooks/hook-index/

use Lkn\HookNotification\Config\Platforms;
use Lkn\HookNotification\Custom\HooksData\Factories\OrderPaidFactory;
use Lkn\HookNotification\Dispatcher;

require_once __DIR__ . '/../../vendor/autoload.php';

add_hook('OrderPaid', 1, function ($vars): void {
    $hookData = OrderPaidFactory::fromHook($vars);

    // You can execute the hook files for every platform.
    Dispatcher::runHook('OrderPaid', $hookData);

    // Or you can run the hook for only one platform.
    // Dispatcher::runHookForPlatform('OrderPaid', Platforms::WHATSAPP, $hookData);
});
