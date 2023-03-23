<?php

namespace Lkn\HookNotification\Config;

/**
 * @see based on the hook names from https://developers.whmcs.com/hooks/hook-index/.
 * Others hooks were created for this module.
 */
enum Hooks: string
{
    case ORDER_CREATED = 'OrderCreated';
    case ORDER_PAID = 'OrderPaid';
    case INVOICE_REMINDER = 'InvoiceReminder';
    case INVOICE_REMINDER_PDF = 'InvoiceReminderPdf';
    case INVOICE_CREATED = 'InvoiceCreated';
}
