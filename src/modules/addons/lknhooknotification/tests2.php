<?php

use Lkn\HookNotification\Core\Notification\Application\NotificationFactory;
use Lkn\HookNotification\Core\Notification\Application\Services\NotificationSender;

require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/vendor/autoload.php';


try {
    $notification = NotificationFactory::getInstance()->makeByCode('QuoteDeliveredExpiresIn2days');
    echo 'tests2 <br>';
    NotificationSender::getInstance()->dispatchNotification(
        $notification,
        [
            'client_id' => 5,
            'quoteid' => 1,
            'client_first_name' => 'cliente',
            'link_pdf' => 'teste',
            'link_quote' => 'testeQuote'
        ]
    );
} catch (Throwable $th) {
    echo '<pre>:';
    print_r($th->__toString());
    echo '</pre><hr>';
}