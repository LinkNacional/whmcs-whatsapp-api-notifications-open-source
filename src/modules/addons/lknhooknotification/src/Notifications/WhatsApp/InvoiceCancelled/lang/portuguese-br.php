<?php

$lang['notification_title'] = 'Fatura cancelada';
$lang['notification_description'] = '';

// Nomes dos parâmetros

$lang['invoice_id'] = 'ID da fatura';
$lang['invoice_balance'] = 'Balanço da fatura';
$lang['invoice_total'] = 'Total da fatura';
$lang['invoice_subtotal'] = 'Subtotal da fatura';
$lang['invoice_items'] = 'Items da fatura';
$lang['invoice_due_date'] = 'Data de vencimento da fatura';
$lang['client_id'] = 'ID do cliente';
$lang['client_first_name'] = 'Primeiro nome do cliente';
$lang['client_full_name'] = 'Nome completo do cliente';
$lang['invoice_pdf_url'] = 'URL do PDF da fatura';
$lang['client_email'] = 'E-mail do cliente';
$lang['invoice_pdf_url_asaas_pay'] = 'URL de pagamento Asaas';
$lang['settings'] = [
    'send_when_order_cancelled' => [
        'label' => 'Enviar mesmo quando o pedido for cancelado',
        'descrip' => 'Válido apenas quando a notificação de pedido cancelado estiver ativada.'
    ]
];

return $lang;
