<?php

$lang['notification_title'] = 'WhatsApp private note';
$lang['notification_description'] = 'This is used by other notifications for sending a private note to a client.';
$lang['settings'] = [
    'private_note_mode' => [
        'label' => 'Mode',
        'descrip' => '(1) Always opens a new conversation and keeps it open. (2) If closed, opens, sends, and closes; if open, sends and keeps it open. If the contact has no conversations, one is created and then closed.',
        'options' => [
            '(1) Open a new conversation',
            '(2) Add a note to the last conversation'
        ]
    ]
];

return $lang;
