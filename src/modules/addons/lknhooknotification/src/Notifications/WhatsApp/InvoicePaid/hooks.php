<?php

use Lkn\HookNotification\Domains\Notifications\Messenger;
use Lkn\HookNotification\Notifications\WhatsApp\NewServiceInvoice\NewServiceInvoiceNotification;

Messenger::run(NewServiceInvoiceNotification::class);
