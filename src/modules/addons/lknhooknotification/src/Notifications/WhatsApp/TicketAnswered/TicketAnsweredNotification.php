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
use Lkn\HookNotification\Config\Platforms;
use Lkn\HookNotification\Config\Settings;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;
use Lkn\HookNotification\Helpers\Config;
use Lkn\HookNotification\Helpers\Logger;

final class TicketAnsweredNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'TicketAnswered';
    public Hooks $hook = Hooks::TICKET_ADMIN_REPLY;

    public function run(): void
    {
        $useTicketWhatsAppCf = Config::get(Platforms::WHATSAPP, Settings::WP_USE_TICKET_WHATSAPP_CF_WHEN_SET);

        if ($useTicketWhatsAppCf === 'disabled') {
            $this->sendMessageForRegisteredClient();
        } else {
            if ($this->getTicketWhatsAppCfValue($this->hookParams['ticketid']) === null) {
                $this->sendMessageForRegisteredClient();
            } else {
                $this->sendMessageForUnregisteredClient($useTicketWhatsAppCf);
            }
        }
    }

    private function sendMessageForRegisteredClient()
    {
        $clientId = $this->getClientIdByTicketId($this->hookParams['ticketid']);

        $this->setClientId($clientId);

        $response = $this->sendMessage();

        $status = is_bool($response) ? ($response ? 'sent' : 'error') : (isset($response['messages'][0]['id']) ? 'sent' : 'error');

        Logger::report(
            $status,
            $this->platform,
            $this->notificationCode,
            $this->clientId,
            'ticket',
            $this->hookParams['ticketid']
        );

        if ($status === 'sent') {
            $this->events->sendMsgToChatwootAsPrivateNote(
                $this->clientId,
                "Notificação: ticket respondido #{$this->hookParams['ticketid']}"
            );
        }
    }

    private function sendMessageForUnregisteredClient()
    {
        $whatsAppNumber = $this->getTicketWhatsAppCfValue($this->hookParams['ticketid']);

        $response = $this->sendMessage($whatsAppNumber);

        $status = is_bool($response) ? ($response ? 'sent' : 'error') : (isset($response['messages'][0]['id']) ? 'sent' : 'error');

        Logger::report(
            $status,
            $this->platform,
            $this->notificationCode,
            null,
            'ticket',
            $this->hookParams['ticketid']
        );

        if ($status === 'sent') {
            $whatsAppInboxId = Config::get(Platforms::CHATWOOT, Settings::CW_WHATSAPP_INBOX_ID);

            $ticketMask = $this->getTicketMask($this->hookParams['ticketid']);

            $this->events->sendMsgToChatwootAsPrivateNoteForUnregisteredClient(
                $whatsAppNumber,
                "Notificação: ticket respondido #{$ticketMask}",
                $this->parameters['client_full_name']['parser'](),
                $this->getTicketEmail($this->hookParams['ticketid']),
                $whatsAppInboxId
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
                'parser' => fn () => empty($this->clientId) ? $this->getTicketNameColumn($this->hookParams['ticketid']) : $this->getClientFirstNameByClientId($this->clientId)
            ],
            'client_full_name' => [
                'label' => 'Nome completo do cliente',
                'parser' => fn () => empty($this->clientId) ? $this->getTicketNameColumn($this->hookParams['ticketid']) : $this->getClientFullNameByClientId($this->clientId)
            ]
        ];
    }
}
