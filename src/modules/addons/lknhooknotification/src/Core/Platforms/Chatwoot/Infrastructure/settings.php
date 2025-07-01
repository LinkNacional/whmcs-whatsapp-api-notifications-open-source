<?php

use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings;

return [
    [
        'setting' => Settings::CW_ENABLED,
        'label' => lkn_hn_lang('Enable Chatwoot'),
        'type' => 'checkbox',
        'warning_on_unchecked' => lkn_hn_lang('This platform only will send messages when this is checked.'),
    ],
    [
        'setting' => Settings::CW_URL,
        'label' => lkn_hn_lang('Chatwoot URL'),
        'description' => lkn_hn_lang('Chatwoot URL'),
        'type' => 'text',
        'popover-config' => [
            'popover-title' =>lkn_hn_lang('Chatwoot URL'),
            'popover-images' =>[
                ['popover-img' => 'popover-chatwoot-url.png','popover-width' => '400']
            ],
        ] 
    ],
    [
        'setting' => Settings::CW_API_ACCESS_TOKEN,
        'label' => lkn_hn_lang('Chatwoot API Token'),
        'description' => lkn_hn_lang('Token used to authenticate API requests.'),
        'type' => 'password',
        'popover-config' => [
            'popover-title' =>lkn_hn_lang('Chatwoot API Token'),
            'popover-images' =>[
                ['popover-img' => 'popover-chatwoot-accessToken.png','popover-width' => '600']
            ],
        ]
    ],
    [
        'setting' => Settings::CW_ACCOUNT_ID,
        'label' => lkn_hn_lang('Chatwoot Account ID'),
        'description' => lkn_hn_lang('Numeric ID of your Chatwoot account.'),
        'type' => 'text',
        'popover-config' => [
            'popover-title' =>lkn_hn_lang('Chatwoot Account ID'),
            'popover-images' =>[
                ['popover-img' => 'popover-chatwoot-accountID.png','popover-width' => '600']
            ],
        ]
    ],
    [
        'setting' => Settings::CW_WHATSAPP_INBOX_ID,
        'label' => lkn_hn_lang('WhatsApp Inbox ID'),
        'description' => lkn_hn_lang('Inbox ID used for WhatsApp integration.'),
        'type' => 'text',
        'popover-config' => [
            'popover-title' =>lkn_hn_lang('WhatsApp Inbox ID'),
            'popover-images' =>[
                ['popover-img' => 'popover-chatwoot-inboxID-1.png','popover-width' => '400'],
                ['popover-img' => 'popover-chatwoot-inboxID-2.png','popover-width' => '300']
            ],
        ]
    ],
    [
        'setting' => Settings::CW_FACEBOOK_INBOX_ID,
        'label' => lkn_hn_lang('Facebook Inbox ID'),
        'description' => lkn_hn_lang('Inbox ID used for Facebook integration.'),
        'type' => 'text',
    ],
    [
        'setting' => Settings::CW_LISTEN_WHATSAPP,
        'label' => lkn_hn_lang('Listen to Notifications'),
        'description' => lkn_hn_lang(
            'The same message sent to others Platforms is sent as a private note message to Chatwoot.'
        ),
        'type' => 'checkbox',
    ],
    [
        'setting' => Settings::CW_PRIVATE_NOTE_MODE,
        'label' => lkn_hn_lang('Private note mode'),
        'description' => lkn_hn_lang('Enable listening for WhatsApp conversations.'),
        'type' => 'select',
        'popover-config' => [
            'popover-title' =>lkn_hn_lang('Private note mode'),
            'popover-images' =>[
                ['popover-img' => 'popover-chatwoot-privateNote.png','popover-width' => '600']
            ],
        ],
        'options' => [
            ['label' => lkn_hn_lang('Open new conversation'), 'value' => 'open_new_conversation'],
            ['label' => lkn_hn_lang('Send to latest conversation'), 'value' => 'send_to_latest_conversation'],
        ],
    ],
    [
        'setting' => Settings::CW_WP_CUSTOM_FIELD_ID,
        'label' => lkn_hn_lang('WhatsApp Custom Field ID'),
        'description' => lkn_hn_lang('Select the custom field for WhatsApp numbers; default is the WHMCS phone field if not set. Numbers must include country and area code.'),
        'type' => 'select',
        'options' => 'lkn_hn_custom_fields',
        'default'=> [
            'value'=> null,  'label' => lkn_hn_lang('Use default WHMCS phone field'),
        ],
        'hide' => true,
    ],
];
