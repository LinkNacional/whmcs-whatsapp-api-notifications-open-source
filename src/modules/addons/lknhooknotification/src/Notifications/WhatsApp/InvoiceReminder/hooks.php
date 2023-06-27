<?php

use Lkn\HookNotification\Helpers\View;
use Lkn\HookNotification\Notifications\WhatsApp\InvoiceReminder\InvoiceReminderNotification;

add_hook('AdminInvoicesControlsOutput', 1, function (array $hookParams): string {
    return View::addNotificationOption(InvoiceReminderNotification::class);
});
