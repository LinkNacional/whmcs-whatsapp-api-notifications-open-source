<?php

$lang['notification_title'] = 'Invoice cancelled';
$lang['notification_description'] = '';

// Parameters labels

$lang['invoice_id'] = 'Invoice ID';
$lang['invoice_balance'] = 'Invoice balance';
$lang['invoice_total'] = 'Invoice total';
$lang['invoice_subtotal'] = 'Invoice subtotal';
$lang['invoice_items'] = 'Invoice items';
$lang['invoice_due_date'] = 'Invoice due date';
$lang['client_id'] = 'Client ID';
$lang['client_first_name'] = 'Client first name';
$lang['client_full_name'] = 'Client full name';
$lang['invoice_pdf_url'] = 'Invoice PDF URL';
$lang['client_email'] = 'Client email';
$lang['invoice_pdf_url_asaas_pay'] = 'Asaas payment URL';
$lang['settings'] = [
    'send_when_order_cancelled' => [
        'label' => 'Send even when order is cancelled',
        'descrip' => 'Only valid when you have the Order Cancelled notification enabled.'
    ]
];

return $lang;
