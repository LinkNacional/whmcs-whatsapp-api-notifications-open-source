<?php

use Lkn\HookNotification\Domains\Notifications\Messenger;
use Lkn\HookNotification\Notifications\WhatsApp\InvoicePaid\InvoicePaidNotification;

Messenger::run(InvoicePaidNotification::class);
