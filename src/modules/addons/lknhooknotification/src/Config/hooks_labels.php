<?php

/**
 * This file contains values and labels for those values that will be displayed to the user.
 */

use Lkn\HookNotification\Config\Hooks;

return [
    [
        'value' => Hooks::INVOICE_REMINDER->value,
        'label' => 'Lembrete de fatura'
    ],

    [
        'value' => Hooks::INVOICE_REMINDER_PDF->value,
        'label' => 'Lembrete de fatura com PDF'
    ],

    [
        'value' => Hooks::ORDER_CREATED->value,
        'label' => 'Pedido criado'
    ]
];
