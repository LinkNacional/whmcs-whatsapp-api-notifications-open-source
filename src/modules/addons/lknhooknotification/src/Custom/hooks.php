<?php

// Doc WHMCS Hook Reference: https://developers.whmcs.com/hooks/hook-index/

use Lkn\HookNotification\Config\Platforms;
use Lkn\HookNotification\Custom\HooksData\Factories\OrderPaidFactory;
use Lkn\HookNotification\Custom\HooksData\Factories\TicketAnsweredNotificationFactory;
use Lkn\HookNotification\Custom\HooksData\Factories\TicketOpenNotificationFactory;
use Lkn\HookNotification\Custom\HooksData\Invoice;
use Lkn\HookNotification\Dispatcher;

require_once __DIR__ . '/../../vendor/autoload.php';

add_hook('OrderPaid', 1, function ($vars): void {
    $hookData = OrderPaidFactory::fromHook($vars);

    // You can execute the hook files for every platform.
    Dispatcher::runHook('OrderPaid', $hookData);

    // Or you can run the hook for only one platform.
    // Dispatcher::runHookForPlatform('OrderPaid', Platforms::WHATSAPP, $hookData);
});

add_hook('DailyCronJob', 1, function ($vars): void {
    $postData = [
        'orderby' => 'invoicenumber',
        'limitnum' => '250',
        'status' => 'Overdue',
        'orderby' => 'invoicenumber',
        'order' => 'desc',
    ];

    $invoices = localAPI('GetInvoices', $postData);

    foreach ($invoices['invoices']['invoice'] as $key => $invoice) {
        if (strtotime($invoice['duedate']) === strtotime(date('Y-m-d', strtotime('-6 day')))) {
            if ($invoice['paymentmethod'] !== 'freeproducts') {
                if ($invoice['total'] !== '0.00') {
                    $invoiceId = $invoice['id'];
                    $invoiceCurrencyPrefix = $invoice['currencyprefix'];
                    $invoiceTotal = $invoice['total'];
                    $clientId = $invoice['userid'];
                    $invoiceDueDate = $invoice['duedate'];

                    $invoiceDatails = localAPI('GetInvoice', ['invoiceid' => $invoiceId]);

                    $invoiceDesc = $invoiceDatails['items']['item'][0]['description'];
                    $invoiceIdAndFirstItem = $invoiceId . ' ' . $invoiceDesc;

                    $hookData = new Invoice($invoiceId, $invoiceIdAndFirstItem, $clientId, $invoiceCurrencyPrefix, $invoiceTotal, $invoiceDueDate);

                    Dispatcher::runHook('InvoiceLate6days', $hookData);
                }
            }
        }
    }
});

// "TicketOpen" only runs when a client open a ticket, for admins use "TicketOpenAdmin".
add_hook('TicketOpen', 1, function ($vars): void {
    $hookData = TicketOpenNotificationFactory::fromHook($vars);
    Dispatcher::runHook('TicketOpenNotification', $hookData);
});

add_hook('TicketAdminReply', 1, function ($vars): void {
    $hookData = TicketAnsweredNotificationFactory::fromHook($vars);
    Dispatcher::runHook('TicketAnsweredNotification', $hookData);
});
