<?php

/**
 * This file is used to provide support for a new hook inside the module.
 *
 * Structure of item [
 *      'value' => an internal name, unique for each param.
 *      'label' => a user friendly name for displaying on associaton page.
 * ]
 *
 * Example:
 * [
 *     'value' => 'InvoiceReminder',
 *     'label' => 'Lembrete de fatura'
 * ]
 */

return [
    [
        'value' => 'InvoiceLate6days',
        'label' => 'Fatura atrasada 6 dias'
    ],

    [
        'value' => 'TicketOpenNotification',
        'label' => 'Ticket aberto'
    ],

    [
        'value' => 'TicketAnsweredNotification',
        'label' => 'Ticket respondido'
    ]
];
