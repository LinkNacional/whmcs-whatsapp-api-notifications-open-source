<?php

return [
    [
        'value' => 'client_first_name',
        'label' => 'Primeiro nome do cliente',
        'for' => ['body']
    ],

    [
        'value' => 'client_full_name',
        'label' => 'Nome completo do cliente',
        'for' => ['body']
    ],

    [
        'value' => 'client_first_two_names',
        'label' => 'Primeiros dois nomes do cliente',
        'for' => ['body']
    ],

    [
        'value' => 'invoice_id',
        'label' => 'ID da fatura',
        'for' => ['body', 'button']
    ],

    [
        'value' => 'invoice_due_date',
        'label' => 'Data de vencimento da fatura (00/00/0000)',
        'for' => ['body']
    ],

    [
        'value' => 'invoice_pdf_url',
        'label' => 'PDF da fatura (se disponível)',
        'for' => ['body', 'button', 'header']
    ],

    [
        'value' => 'order_items_descrip',
        'label' => 'Descrições dos itens do pedido',
        'for' => ['body']
    ],
];
