<?php

use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings;

return [
    [
        'setting' => Settings::CW_ENABLE_LIVE_CHAT,
        'label' => lkn_hn_lang('Enable live chat'),
        'description' => lkn_hn_lang('Enable or disable the live chat feature.'),
        'type' => 'checkbox',
        'warning_on_unchecked' => lkn_hn_lang('This platform only will send messages when this is checked.'),
    ],
    [
        'setting' => Settings::CW_LIVE_CHAT_USER_IDENTITY_TOKEN,
        'label' => lkn_hn_lang('User Identity Validation Secret'),
        'description' => lkn_hn_lang('Used to validate user identity for live chat.'),
        'type' => 'password',
        'popover-config' => [
            'popover-title' =>lkn_hn_lang('User Identity Validation Secret'),
            'popover-images' =>[
                ['popover-img' => 'popover-chatwootLivechat-userIdentity.png','popover-width' => '500']
            ],
        ]
    ],
    [
        'setting' => Settings::CW_LIVE_CHAT_SCRIPT,
        'label' => lkn_hn_lang('Messenger Scriptn'),
        'type' => 'textarea',
        'popover-config' => [
            'popover-title' =>lkn_hn_lang('Messenger Scriptn'),
            'popover-images' =>[
                ['popover-img' => 'popover-chatwootLivechat-messenger.png','popover-width' => '500']
            ],
        ]
    ],
    [
        'separator' => true,
        'title' => lkn_hn_lang('Custom Attributes to Send'),
        'description' => lkn_hn_lang('These fields will be added to the client profile in Chatwoot.'),
    ],
    [
        'setting' => Settings::CW_CLIENT_STATS_TO_SEND,
        'label' => lkn_hn_lang('Client stats to send'),
        'type' => 'multiple',
        'options' => (require __DIR__ . '/constants.php')['chat_widget_attrs_options'],
    ],
    [
        'setting' => Settings::CW_CUSTOM_FIELDS_TO_SEND,
        'label' => lkn_hn_lang('Custom fields to send'),
        'type' => 'multiple',
        'options' => 'lkn_hn_custom_fields','popover-config' => [
            'popover-title' =>lkn_hn_lang('Custom fields to send'),
            'popover-images' =>[
                ['popover-img' => 'popover-chatwootLivechat-customAttributes.png','popover-width' => '500']
            ],
        ]
    ],
    [
        'setting' => Settings::CW_LIVE_CHAT_MODULE_ATTRS_TO_SEND,
        'label' => lkn_hn_lang('Addtional fields'),
        'type' => 'multiple',
        'options' => (require __DIR__ . '/constants.php')['module_attrs_options'],
    ],
];
