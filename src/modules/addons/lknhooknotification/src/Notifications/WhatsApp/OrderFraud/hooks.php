<?php

use Lkn\HookNotification\Domains\Notifications\Messenger;
use Lkn\HookNotification\Notifications\WhatsApp\OrderFraud\OrderFraudNotification;

Messenger::run(OrderFraudNotification::class);
