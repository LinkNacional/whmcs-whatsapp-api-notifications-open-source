<?php

use Lkn\HookNotification\Helpers\View;
use Lkn\HookNotification\Notifications\WhatsApp\InvoiceReminderPdf\InvoiceReminderPdfNotification;

add_hook('AdminInvoicesControlsOutput', 1, function (array $hookParams): string {
    return View::addNotificationOption(InvoiceReminderPdfNotification::class);
});
