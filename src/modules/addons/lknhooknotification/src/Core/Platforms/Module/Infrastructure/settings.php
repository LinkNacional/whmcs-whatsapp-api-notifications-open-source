<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings;

return [
    [
        'setting' => Settings::LANGUAGE,
        'label' => lkn_hn_lang('Module language.'),
        'description' => lkn_hn_lang('This does not affect the language of the notifications.'),
        'type' => 'select',
        'options' => [
            ['label' => lkn_hn_lang('English'), 'value' => 'english'],
            ['label' => lkn_hn_lang('Portuguese (BR)'), 'value' => 'portugues-br'],
            ['label' => lkn_hn_lang('Portuguese (PT)'), 'value' => 'portugues-pt'],
        ],
    ],
    [
        'setting' => Settings::LKN_LICENSE,
        'label' => lkn_hn_lang('License'),
        'description' => lkn_hn_lang('Link Nacional license to access premium module features.'),
        'type' => 'password',
    ],
    [
        'setting' => Settings::DEFAULT_CLIENT_NAME,
        'label' => lkn_hn_lang('Default name for clients without filled names'),
        'description' => lkn_hn_lang('Platforms will use this name as a parameter if the client does not have a name filled in their WHMCS profile.'),
        'type' => 'text',
    ],
    [
        'setting' => Settings::ENABLE_LOG,
        'label' => lkn_hn_lang('Enable logs'),
        'description' => lkn_hn_lang('The module will logs its operations on WHMCS Module Log.'),
        'type' => 'checkbox',
    ],
    [
        'separator' => true,
        'title' => lkn_hn_lang('WhatsApp custom field'),
        'description' => lkn_hn_lang('By default, the module will use WHMCS default phone number field or you can specify a WhatsApp custom field below.'),
    ],
    [
        'setting' => Settings::WP_CUSTOM_FIELD_ID,
        'label' => lkn_hn_lang('WhatsApp Custom Field ID'),
        'description' => lkn_hn_lang('Select the custom field for WhatsApp numbers; default is the WHMCS phone field if not set. Numbers must include country and area code.'),
        'description_link' => [
            'label' => lkn_hn_lang('Go to custom fields page') . ' <i class="fas fa-external-link-alt"></i>',
            'link' => 'configcustomfields.php',
        ],
        'type' => 'select',
        'options' => 'lkn_hn_custom_fields',
        'default'=> [
            'value'=> null,  'label' => lkn_hn_lang('Use default WHMCS phone field'),
        ],
    ],
    [
        'setting' => Settings::TICKET_WP_CUSTOM_FIELD_ID,
        'label' => lkn_hn_lang('Custom fields for tickets of unregistered clients'),
        'description' => lkn_hn_lang('Enable to send notifications to the custom WhatsApp field instead of default.'),
        'type' => 'select',
        'options' => 'lkn_hn_custom_fields',
        'description_link' => [
            'label' => lkn_hn_lang('Go to custom fields page') . ' <i class="fas fa-external-link-alt"></i>',
            'link' => 'configcustomfields.php',
        ],
    ],
    [
        'setting' => Settings::BD_CUSTOM_FIELD_ID,
        'label' => lkn_hn_lang('Birthdate Custom Field ID'),
        'description' => lkn_hn_lang("Select the custom field corresponding to the customer's date of birth; the default date format is defined in the general settings of WHMCS and is currently configured as: ").Capsule::table('tblconfiguration')->where('setting', 'DateFormat')->value('value'),
        'description_link' => [
            'label' => lkn_hn_lang('Go to date format settings') . ' <i class="fas fa-external-link-alt"></i>',
            'link' => 'configgeneral.php#tab=2'
        ],'description_right_link' => [
            'label' => lkn_hn_lang('Go to custom fields page') . ' <i class="fas fa-external-link-alt"></i>',
            'link' => 'configcustomfields.php'
        ],
        'type' => 'select',
        'options' => 'lkn_hn_custom_fields',
        'default'=> [
            'value'=> null,  'label' => lkn_hn_lang('Select the birthday date custom field'),
        ],
    ]
];
