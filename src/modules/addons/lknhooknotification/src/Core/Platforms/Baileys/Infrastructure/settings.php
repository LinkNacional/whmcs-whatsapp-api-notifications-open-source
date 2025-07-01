<?php

use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings;

return [
    [
        'setting' => Settings::BAILEYS_ENABLE,
        'label' => lkn_hn_lang('Enable Baileys'),
        'description' => lkn_hn_lang('Enable Baileys WhatsApp integration.'),
        'type' => 'checkbox',
        'warning_on_unchecked' => lkn_hn_lang('This platform only will send messages when this is checked.'),
    ],
    [
        'setting' => Settings::BAILEYS_ENDPOINT_URL,
        'label' => lkn_hn_lang('Baileys Endpoint URL'),
        'type' => 'url',
        'description' => <<<HTML
        <pre>
        // 1. Defined POST endpoint /lkn-notif/send-msg
        app.post('/lkn-notif/send-msg', async (req, res) => {
            console.log()

            // 2. Check the API Key you configured in the module was sent.
            if (req.headers['api-key'] !== '12345678') {
                res.status(401)
            }

            const { number, message } = req.body;

            // 3. Valide number and message from request body
            if (!number || !message) {
                return res.status(400).json({ error: 'Number and message are required' });
            }

            try {
                const jid = number.includes('@s.whatsapp.net') ? number : `\$\{number}@s.whatsapp.net`;

                // 4. Send message to the received phone number.
                await sock.sendMessage(jid, { text: message });

                res.json({ success: true, message: 'Message sent successfully' });
            } catch (error) {
                res.status(500).json({ error: 'Failed to send message', details: error.toString() });
            }
        });
        </pre>
        HTML,
        'popover-config' => [
            'popover-title' =>lkn_hn_lang('Baileys Endpoint URL'),
            'popover-images' =>[
                ['popover-img' => 'popover-baileys-endpoint.png','popover-width' => '400']
            ],
            'popover-description' => lkn_hn_lang('The module will send a POST request with the body:<br>{<br> number: number,<br> message: string<br> }')
        ]
    ],
    [
        'setting' => Settings::BAILEYS_API_KEY,
        'label' => lkn_hn_lang('API Key'),
        'description' => lkn_hn_lang('API Key'),
        'type' => 'password',
        'popover-config' => [
            'popover-title' =>lkn_hn_lang('API Key'),
            'popover-description' => lkn_hn_lang('The module will send a POST request with the header “API-Key”')
        ]
    ],
    [
        'setting' => Settings::BAILEYS_WP_CUSTOM_FIELD_ID,
        'label' => lkn_hn_lang('WhatsApp Custom Field'),
        'description' => lkn_hn_lang('Client profile field that contains the WhatsApp number.'),
        'type' => 'select',
        'options' => 'lkn_hn_custom_fields',
        'default'=> [
            'value'=> null,  'label' => lkn_hn_lang('Use default WHMCS phone field'),
        ],
        'hide' => true,
    ],
];
