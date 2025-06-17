<?php

use Lkn\HookNotification\Core\Notification\Application\NotificationFactory;
use Lkn\HookNotification\Core\Notification\Application\Services\NotificationSender;

require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/vendor/autoload.php';


try {
    $notification = NotificationFactory::getInstance()->makeByCode('QuoteStatusChange');
    echo 'tests2 <br>';
    NotificationSender::getInstance()->dispatchNotification(
        $notification,
        [
            'quoteid' => 1,
            'status' => 'Lost'
        ]
    );
} catch (Throwable $th) {
    echo '<pre>:';
    print_r($th->__toString());
    echo '</pre><hr>';
}