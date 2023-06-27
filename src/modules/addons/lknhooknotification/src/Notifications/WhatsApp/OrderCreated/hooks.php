<?php

use Lkn\HookNotification\Domains\Notifications\Messenger;
use Lkn\HookNotification\Notifications\WhatsApp\OrderCreated\OrderCreatedNotification;

Messenger::run(OrderCreatedNotification::class);
