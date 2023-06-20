<?php
/**
 * Name: Ticket aberto
 * Code: TicketOpen
 * Platform: WhatsApp
 * Version: 1.0.0
 * Author: Link Nacional
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\TicketOpen;

use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;

final class TicketOpenNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'TicketOpen';
    public Hooks $hook = Hooks::TICKET_OPEN;

    public function run(): void
    {
        $this->setClientId($this->getClientIdByTicketId($this->hookParams['ticketid']));

        $response = $this->sendMessage();

        $this->report($response, 'ticket', $this->hookParams['ticketid']);

        if (isset($response['messages'][0]['id'])) {
            $this->events->sendMsgToChatwootAsPrivateNote(
                $this->clientId,
                "Notificação: ticket aberto #{$this->hookParams['ticketid']}"
            );
        }
    }

    public function defineParameters(): void
    {
        $this->parameters = [
            'ticket_id' => [
                'label' => 'ID do ticket',
                'parser' => fn () => $this->hookParams['ticketid']
            ],
            'ticket_subject' => [
                'label' => 'Assunto do ticket',
                'parser' => fn () => $this->hookParams['subject']
            ],
            'client_first_name' => [
                'label' => 'Primeiro nome do cliente',
                'parser' => fn () => $this->getClientFirstNameByClientId($this->clientId)
            ],
            'client_full_name' => [
                'label' => 'Nome completo do cliente',
                'parser' => fn () => $this->getClientFullNameByClientId($this->clientId)
            ]
        ];
    }
}
