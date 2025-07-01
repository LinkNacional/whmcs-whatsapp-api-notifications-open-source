<?php

use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings;

return [
    [
        'setting' => Settings::BULK_ENABLE,
        'label' => lkn_hn_lang('Enable Bulk Messaging'),
        'description' => lkn_hn_lang('If disabled, in progress bulk messages will be paused.'),
        'type' => 'checkbox',
    ],
];
