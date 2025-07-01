<?php

use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings;

return [
    [
        'setting' => Settings::WP_META_ENABLE,
        'label' => lkn_hn_lang('Enable Meta WhatsApp'),
        'description' => lkn_hn_lang('Enable Meta WhatsApp integration.'),
        'type' => 'checkbox',
        'warning_on_unchecked' => lkn_hn_lang('This platform only will send messages when this is checked.'),
    ],
    [
        'setting' => Settings::WP_BUSINESS_ACCOUNT_ID,
        'label' => lkn_hn_lang('Business Account ID'),
        'description' => lkn_hn_lang('Unique identifier of a WhatsApp business account created in WhatsApp Business Manager.'),
        'type' => 'password',
        'popover-config' => [
            'popover-title' =>lkn_hn_lang('Business Account ID'),
            'popover-images' =>[
            ['popover-img' => 'popover-wp-accountID.png', 'popover-width' => '700']
            ]
        ]   
    ],
    [
        'setting' => Settings::WP_USER_ACCESS_TOKEN,
        'label' => lkn_hn_lang('User Access Token'),
        'description' => lkn_hn_lang('Unique token allowing third-party apps to access the WhatsApp API functionalities with prior authorization.'),
        'type' => 'password',
        'popover-config' => [
            'popover-title' =>lkn_hn_lang('User Access Token'),
            'popover-images' =>[
                ['popover-img' => 'popover-wp-userAcessToken.png', 'popover-width' => '700']
            ]
        ]
    ],
    [
        'setting' => Settings::WP_PHONE_NUMBER_ID,
        'label' => lkn_hn_lang('Phone Number ID'),
        'description' => lkn_hn_lang('Phone number associated with the WhatsApp business account for API interactions.'),
        'type' => 'password',
        'popover-config' => [
            'popover-title' =>lkn_hn_lang('Phone Number ID'),
            'popover-images' =>[
                ['popover-img' => 'popover-wp-phoneID.png', 'popover-width' => '700']
            ]
        ]
    ],
    [
        'setting' => Settings::WP_VERSION,
        'label' => lkn_hn_lang('WhatsApp API Version'),
        'description' => lkn_hn_lang('Defines the WhatsApp API version used for integration to ensure compatibility.'),
        'type' => 'select',
        'default' => 'v22.0',
        'options' => [
            ['label' => 'v22.0', 'value' => 'v22.0'],
        ],
    ],
    [
        'setting' => Settings::WP_CUSTOM_FIELD_ID,
        'label' => lkn_hn_lang('WhatsApp Custom Field ID'),
        'description' => lkn_hn_lang('Select the custom field for WhatsApp numbers; default is the WHMCS phone field if not set. Numbers must include country and area code.'),
        'type' => 'select',
        'options' => 'lkn_hn_custom_fields',
        'default'=> [
            'value'=> null,  'label' => lkn_hn_lang('Use default WHMCS phone field'),
        ],
        'hide' => true,
    ],
    [
        'setting' => Settings::WP_SHOW_INVOICE_REMINDER_BTN_WHEN_PAID,
        'label' => lkn_hn_lang('Display Button on Paid Invoices'),
        'description' => lkn_hn_lang('Enable to display a button that sends invoice reminder notifications on paid invoices.'),
        'type' => 'checkbox',
    ],
    [
        'setting' => Settings::WP_USE_TICKET_WHATSAPP_CF_WHEN_SET,
        'label' => lkn_hn_lang('Ticket Answered Notification must prefer the WhatsApp custom field for tickets'),
        'description' => lkn_hn_lang('Enable to send notifications to the custom WhatsApp field instead of default.'),
        'type' => 'select',
        'options' => 'lkn_hn_custom_fields',
        'hide' => true,
    ],
    [
        'setting' => Settings::WP_MSG_TEMPLATE_LANG,
        'label' => lkn_hn_lang('Default language for template messages'),
        'description' => lkn_hn_lang('Defines the default language for WhatsApp Cloud API template messages.'),
        'type' => 'select',
        'options' => 'lkn_hn_locales',
    ],
];
