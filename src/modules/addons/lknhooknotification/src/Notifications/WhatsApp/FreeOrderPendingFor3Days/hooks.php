<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Lkn\HookNotification\Domains\Notifications\Messenger;
use Lkn\HookNotification\Notifications\WhatsApp\FreeOrderPendingFor3Days\FreeOrderPendingFor3DaysNotification;

Messenger::run(FreeOrderPendingFor3DaysNotification::class);
