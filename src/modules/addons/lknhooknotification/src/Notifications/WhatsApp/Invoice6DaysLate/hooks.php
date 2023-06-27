<?php

use Lkn\HookNotification\Domains\Notifications\Messenger;
use Lkn\HookNotification\Notifications\WhatsApp\Invoice6DaysLate\Invoice6DaysLateNotification;

Messenger::run(Invoice6DaysLateNotification::class);
