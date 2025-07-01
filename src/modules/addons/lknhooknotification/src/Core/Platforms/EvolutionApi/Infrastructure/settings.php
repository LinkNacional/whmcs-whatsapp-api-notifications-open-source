<?php

use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings;

return [
    [
        'setting' => Settings::WP_EVO_ENABLE,
        'label' => lkn_hn_lang('Enable WhatsApp Evolution'),
        'description' => lkn_hn_lang('Toggle to activate the WhatsApp Evolution integration.'),
        'type' => 'checkbox',
        'warning_on_unchecked' => lkn_hn_lang('This platform only will send messages when this is checked.'),
    ],
    [
        'setting' => Settings::WP_EVO_API_URL,
        'label' => lkn_hn_lang('Evolution API URL'),
        'description' => lkn_hn_lang('Provide the base URL for the Evolution API instance.'),
        'type' => 'url',
        'popover-config' => [
            'popover-title' =>lkn_hn_lang('Evolution API URL'),
            'popover-images' => [
                ['popover-img' => 'popover-we-url.png','popover-width' => '300']
            ]
        ]
    ],
    [
        'setting' => Settings::WP_EVO_API_KEY,
        'label' => lkn_hn_lang('Evolution API License Key'),
        'description' => lkn_hn_lang('Required to authenticate with the WhatsApp Evolution API.'),
        'type' => 'password',
        'popover-config' => [
            'popover-title' =>lkn_hn_lang('Evolution API License Key'),
            'popover-images' => [
                ['popover-img' => 'popover-we-apiKey.png','popover-width' => '300',]
            ],
            'popover-description' =>lkn_hn_lang("It's in the .env file of your Evolution: AUTHENTICATION_API_KEY")
        ]
    ],
    [
        'setting' => Settings::WP_EVO_WP_NUMBER_CUSTOM_FIELD_ID,
        'label' => lkn_hn_lang('WhatsApp Custom Field ID'),
        'description' => lkn_hn_lang('Select the custom client field to be used for WhatsApp numbers.'),
        'type' => 'select',
        'options' => 'lkn_hn_custom_fields',
        'default'=> [
            'value'=> null,  'label' => lkn_hn_lang('Use default WHMCS phone field'),
        ],
        'hide' => true,
    ],
];
