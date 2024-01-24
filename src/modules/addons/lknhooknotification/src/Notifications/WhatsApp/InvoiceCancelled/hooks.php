<?php

use Lkn\HookNotification\Domains\Notifications\Messenger;
use Lkn\HookNotification\Notifications\WhatsApp\InvoiceCancelled\InvoiceCancelledNotification;

Messenger::run(InvoiceCancelledNotification::class);
