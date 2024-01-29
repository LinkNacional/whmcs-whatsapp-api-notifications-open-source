<?php

$lang['notification_title'] = 'Nota privada do WhatsApp';
$lang['notification_description'] = 'É usada por outras notificações para enviar uma nota privada a um cliente.';
$lang['settings'] = [
    'private_note_mode' => [
        'label' => 'Modo',
        'descrip' => '(1) Sempre abre uma nova conversa e a mantém aberta. (2) Se estiver fechada, abre, envia e fecha; se estiver aberta, envia e mantém aberta. Caso o contato não tenha conversas, uma é criada e depois fechada.',
        'options' => [
            '(1) Abrir uma nova conversa',
            '(2) Adicionar uma nota na última conversa'
        ]
    ]
];

return $lang;
