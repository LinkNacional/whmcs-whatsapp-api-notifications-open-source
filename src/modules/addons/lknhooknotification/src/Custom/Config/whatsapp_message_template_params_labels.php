<?php

/**
 * Structure of item [
 *      'value' => a internal name, unique for each param.
 *      'label' => a user friendly name for display on associaton page.
 *      'for' => this param must be shown on header, body or button sections?
 * ]
 *
 * Example:
 * [
 *     'value' => 'client_first_name',
 *     'label' => 'Primeiro nome do cliente',
 *     'for' => ['body']
 * ]
 */

return [
    [
        'value' => 'ticket_id',
        'label' => 'ID do ticket',
        'for' => ['body', 'button']
    ],

    [
        'value' => 'invoice_id_invoice_first_item',
        'label' => 'ID da fatura e primeiro item da fatura',
        'for' => ['body', 'button']
    ],

    [
        'value' => 'ticket_subject',
        'label' => 'Assunto do ticket',
        'for' => ['body', 'button']
    ],

    [
        'value' => 'order_id_and_product',
        'label' => 'ID do pedido e produto',
        'for' => ['body', 'button']
    ],

    [
        'value' => 'quote_subject',
        'label' => 'Assunto do orçamento',
        'for' => ['body', 'button']
    ],

    [
        'value' => 'quote_first_name',
        'label' => 'Primeiro nome do cliente no orçamento (não cadastrado)',
        'for' => ['body', 'button']
    ],

    [
        'value' => 'quote_email',
        'label' => 'Email do cliente no orçamento',
        'for' => ['body', 'button']
    ],

    [
        'value' => 'quote_id',
        'label' => 'ID do orçamento',
        'for' => ['body', 'button']
    ]
];
