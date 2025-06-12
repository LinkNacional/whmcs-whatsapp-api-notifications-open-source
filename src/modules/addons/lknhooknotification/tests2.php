<?php

use Lkn\HookNotification\Core\Notification\Application\NotificationFactory;
use Lkn\HookNotification\Core\Notification\Application\Services\NotificationSender;

require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/vendor/autoload.php';


try {
    $notification = NotificationFactory::getInstance()->makeByCode('UserLoginNotification');

    NotificationSender::getInstance()->dispatchNotification(
        $notification,
        [
            'invoiceid' => 21,
            'client_id' => 1,
            'service_id' => 24,
            'report_category_id' => 24,
            'ticket_id'=> 6,
            'ticketid'=> 6,
        ]
    );
} catch (Throwable $th) {
    echo '<pre>:';
    print_r($th->__toString());
    echo '</pre><hr>';
}