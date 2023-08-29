<?php

use Lkn\HookNotification\Domains\Notifications\Messenger;
use Lkn\HookNotification\Notifications\WhatsApp\OrderCancelled\OrderCancelledNotification;

Messenger::run(OrderCancelledNotification::class);
