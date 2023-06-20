<?php

use Lkn\HookNotification\Domains\Notifications\Messenger;
use Lkn\HookNotification\Notifications\WhatsApp\TicketAnswered\TicketAnsweredNotification;

Messenger::run(TicketAnsweredNotification::class);
