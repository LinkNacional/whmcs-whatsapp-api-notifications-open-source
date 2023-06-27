<?php
/**
 * Name: Ticket respondido por administrador
 * Code: TicketAnswered
 * Platform: WhatsApp
 * Version: 1.0.0
 * Author: Link Nacional
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\TicketAnswered;

use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;

final class TicketAnsweredNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'TicketAnswered';
    public Hooks $hook = Hooks::TICKET_ADMIN_REPLY;

    public function run(): void
    {
        $clientId = $this->getClientIdByTicketId($this->hookParams['ticketid']);

        $this->setClientId($clientId);

        $response = $this->sendMessage();

        $this->report($response, 'ticket', $this->hookParams['ticketid']);

        if (isset($response['messages'][0]['id'])) {
            $this->events->sendMsgToChatwootAsPrivateNote(
                $this->clientId,
                "Notificação: ticket respondido #{$this->hookParams['ticketid']}"
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
