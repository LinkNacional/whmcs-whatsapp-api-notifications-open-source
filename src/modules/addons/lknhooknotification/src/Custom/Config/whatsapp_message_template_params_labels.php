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
    ]
];
