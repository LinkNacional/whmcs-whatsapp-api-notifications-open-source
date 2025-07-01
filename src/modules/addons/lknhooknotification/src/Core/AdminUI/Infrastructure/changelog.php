<?php

return [
    [
        'version' => '3.9.0',
        'date' => '2025-03-28',
        'changes' => [
            'Integration with Baileys',
        ],
    ],
    [
        'version' => '3.8.1',
        'date' => '2025-03-24',
        'changes' => [
            'General fixes',
        ],
    ],
    [
        'version' => '3.8.0',
        'date' => '2025-03-03',
        'changes' => [
            'WhatsApp Evolution.',
        ],
    ],
    [
        'version' => '3.7.1',
        'date' => '2025-02-21',
        'changes' => [
            'Fix language error when sending message.',
            'Fix number inputs to accept larger numbers.',
            'Add fallback to English for WhatsApp languages.',
        ],
    ],
    [
        'version' => '3.7.0',
        'date' => '2025-01-22',
        'changes' => [
            'Add multi-language support for WhatsApp Meta notifications.',
            'Add FPDI lib to enable editing invoice PDFs.',
            'Show warning when environment is incompatible with module requirements.',
        ],
    ],
    [
        'version' => '3.6.0',
        'date' => '2024-11-21',
        'changes' => [
            'Update WhatsApp API version.',
            'Settings menu now displays current API version.',
            'Add compatibility with PHP 8.1.',
        ],
    ],
    [
        'version' => '3.5.1',
        'date' => '2024-11-05',
        'changes' => [
            'Fix error when sending notifications with WhatsApp PDF.',
        ],
    ],
    [
        'version' => '3.5.0',
        'date' => '2024-10-28',
        'changes' => [
            'Fix error when sending manual WhatsApp notifications.',
        ],
    ],
    [
        'version' => '3.4.8',
        'date' => '2024-09-18',
        'changes' => [
            'Fix JS script references.',
        ],
    ],
    [
        'version' => '3.4.7',
        'date' => '2024-09-18',
        'changes' => [
            'Fix module artifact references for WHMCS in subdirectory.',
        ],
    ],
    [
        'version' => '3.4.6',
        'date' => '2024-08-12',
        'changes' => [
            'Fix SQL build error.',
        ],
    ],
    [
        'version' => '3.4.5',
        'date' => '2024-08-09',
        'changes' => [
            'Improve logging and error handling.',
            'Remove foreign key to avoid setup errors in WHMCS.',
            'Handle clients without custom WhatsApp number field.',
            'Improve handling for non-existent clients.',
        ],
    ],
    [
        'version' => '3.4.4',
        'date' => '2024-07-01',
        'changes' => [
            'Fix database issue.',
            'Fix language template recognition.',
            'Add template configuration.',
        ],
    ],
    [
        'version' => '3.4.3',
        'date' => '2024-03-20',
        'changes' => [
            'Fix issues creating DB tables.',
            'Fix notification config page when no notifications exist.',
        ],
    ],
    [
        'version' => '3.4.2',
        'date' => '2024-03-07',
        'changes' => [
            'Fix table installation.',
        ],
    ],
    [
        'version' => '3.4.1',
        'date' => '2024-01-30',
        'changes' => [
            'Adjust logic to send private notes to non-registered clients.',
        ],
    ],
    [
        'version' => '3.4.0',
        'date' => '2024-01-29',
        'changes' => [
            ' Rename "chat" to "integration".',
            ' Add description to Chatwoot integration screen.',
            ' Add links to dynamically access Chatwoot instance info.',
            ' Rename module to WhatsApp and Chatwoot.',
            'Implement per-notification configuration.',
            'Migrate structure for saving active Chatwoot settings in DB.',
        ],
    ],
    [
        'version' => '3.3.0',
        'date' => '2023-11-10',
        'changes' => [
            'Remove DB table deletion on module deactivation.',
            'Fix customer profile links in Chatwoot.',
        ],
    ],
    [
        'version' => '3.2.1',
        'date' => '2023-08-31',
        'changes' => [
            'Fix translations.',
            'Fix Config class when `_config` table doesn’t exist.',
        ],
    ],
    [
        'version' => '3.2.0',
        'date' => '2023-08-31',
        'changes' => [
            'Add module and notification internationalization.',
            'Add support for Chatwoot Live Chat.',
            'Improve notification delivery logging.',
            'Adjust module responsiveness on mobile.',
            'Highlight buttons to create/download notifications.',
        ],
    ],
    [
        'version' => '3.1.1',
        'date' => '2023-08-04',
        'changes' => [
            'Fix license validation checks.',
            'Fix association between notification and message template.',
        ],
    ],
    [
        'version' => '3.1.0',
        'date' => '2023-08-04',
        'changes' => [
            'Add home page with module docs and useful links.',
            'Add text-type parameters to header.',
            'Implement libphonenumber to validate client phone.',
            'Update license logic to allow >3 notifications on free plan.',
            'Add modal to show notification reports in client profile.',
        ],
    ],
    [
        'version' => '3.0.1',
        'date' => '2023-06-27',
        'changes' => [
            'Fix first installation bugs.',
        ],
    ],
    [
        'version' => '3.0.0',
        'date' => '2023-06-21',
        'changes' => [
            'Reimplement and simplify notification creation.',
            'Support temporary invoice PDF generation.',
            'Improve message template config with notification.',
            'Add reports screen.',
            'Simplify repository structure.',
        ],
    ],
    [
        'version' => '2.3.3',
        'date' => '2023-05-08',
        'changes' => [
            ' Change value column to longText for older DB compatibility.',
            ' Fix links to logs page.',
        ],
    ],
    [
        'version' => '2.3.2',
        'date' => '2023-05-03',
        'changes' => [
            'Fix template-notification association page error.',
        ],
    ],
    [
        'version' => '2.3.1',
        'date' => '2023-04-25',
        'changes' => [
            ' Implement check for mod_paghiper table existence.',
        ],
    ],
    [
        'version' => '2.3.0',
        'date' => '',
        'changes' => [
            'Add AfterModuleSuspend notification.',
            'Migrate config to Chatwoot settings screen.',
            'Fix message templates select with limit=200.',
        ],
    ],
    [
        'version' => '2.2.1',
        'date' => '',
        'changes' => [
            'Update logic for creating custom hooks.',
        ],
    ],
    [
        'version' => '2.2.0',
        'date' => '',
        'changes' => [
            'Implement version check.',
            'Add logo and description in addon list.',
            'Add button for module log access.',
        ],
    ],
    [
        'version' => '2.1.0',
        'date' => '',
        'changes' => [
            'Grammar fixes.',
            'Add default client name config.',
            'Improve invoice reminder feedback UI.',
            'Fix bugs in order created hook.',
        ],
    ],
    [
        'version' => '2.0.0',
        'date' => '',
        'changes' => [
            'Add Composer and dependencies.',
            'Add support for custom hooks.',
            'Migrate and improve settings screen.',
            'Improve message template register/edit screen.',
            'Add help screen.',
        ],
    ],
    [
        'version' => '1.1.0',
        'date' => '',
        'changes' => [
            'Add Dev Container setup files.',
            'Add "OrderCreated" hook for WhatsApp.',
            'Add "OrderCreated" hook for WhatsApp in ChatWoot channel.',
        ],
    ],
    [
        'version' => '1.0.0',
        'date' => '',
        'changes' => [
            'Admin panel to view invoices.',
            'Send invoice reminders as plain text.',
            'Send invoice reminder with PagHiper boleto.',
            'On success, send similar message to Chatwoot as private.',
        ],
    ],
];
