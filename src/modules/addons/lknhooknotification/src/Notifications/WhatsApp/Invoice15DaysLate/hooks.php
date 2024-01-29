<?php

use Lkn\HookNotification\Domains\Notifications\Messenger;
use Lkn\HookNotification\Notifications\WhatsApp\Invoice15DaysLate\Invoice15DaysLateNotification;

Messenger::run(Invoice15DaysLateNotification::class);
