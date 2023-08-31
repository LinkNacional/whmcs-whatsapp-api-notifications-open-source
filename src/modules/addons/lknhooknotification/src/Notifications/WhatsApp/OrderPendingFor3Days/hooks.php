<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Lkn\HookNotification\Domains\Notifications\Messenger;
use Lkn\HookNotification\Notifications\WhatsApp\OrderPendingFor3Days\OrderPendingFor3DaysNotification;

Messenger::run(OrderPendingFor3DaysNotification::class);
