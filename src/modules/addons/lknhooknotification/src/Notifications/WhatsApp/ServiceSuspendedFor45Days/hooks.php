<?php

use Lkn\HookNotification\Domains\Notifications\Messenger;
use Lkn\HookNotification\Notifications\WhatsApp\ServiceSuspendedFor45Days\ServiceSuspendedFor45DaysNotification;

Messenger::run(ServiceSuspendedFor45DaysNotification::class);
