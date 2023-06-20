<?php
/**
 * Name: Serviço suspenso
 * Code: AfterModuleSuspend
 * Platform: WhatsApp
 * Version: 1.0.0
 * Author: Link Nacional
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\AfterModuleSuspend;

use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;

final class AfterModuleSuspendNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'AfterModuleSuspend';
    public Hooks $hook = Hooks::AFTER_MODULE_SUSPEND;

    public function run(): void
    {
        $this->setClientId($this->hookParams['params']['userid']);

        $response = $this->sendMessage();

        $success = isset($response['messages'][0]['id']);

        $this->report($response, 'invoice', $this->hookParams['invoiceId']);

        if ($success) {
            $this->events->sendMsgToChatwootAsPrivateNote(
                $this->clientId,
                "Notificação: serviço suspenso #{$this->hookParams['invoiceId']}"
            );
        }
    }

    public function defineParameters(): void
    {
        $this->parameters = [
            'service_id' => [
                'label' => 'ID do serviço',
                'parser' => fn () => $this->hookParams['params']['serviceid']
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
