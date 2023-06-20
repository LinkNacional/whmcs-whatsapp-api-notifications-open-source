<?php
/**
 * Name: Lembrete de fatura
 * Code: InvoiceReminder
 * Platform: WhatsApp
 * Version: 1.0.0
 * Author: Link Nacional
 * Description: Essa notificação consiste em disparo manual do envio da message template ao clicar no botão que fica dentro da visualização administrativa da fatura.
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\InvoiceReminder;

use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;
use Lkn\HookNotification\Helpers\Response;

final class InvoiceReminderNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'InvoiceReminder';

    public function run(): void
    {
        $this->setClientId($this->getClientIdByInvoiceId($this->hookParams['invoiceId']));

        $response = $this->sendMessage();

        $success = isset($response['messages'][0]['id']);

        $this->report($response, 'invoice', $this->hookParams['invoiceId']);

        if ($success) {
            $this->events->sendMsgToChatwootAsPrivateNote(
                $this->clientId,
                "Notificação: lembrete de fatura #{$this->hookParams['invoiceId']}"
            );
        }

        Response::api($success);
    }

    public function defineParameters(): void
    {
        $this->parameters = [
            'invoice_id' => [
                'label' => 'ID da fatura',
                'parser' => fn () => $this->hookParams['invoiceId']
            ],
            'invoice_items' => [
                'label' => 'Items da fatura',
                'parser' => fn () => self::getOrderItemsDescripByOrderId(
                    $this->hookParams['invoiceId']
                )
            ],
            'invoice_due_date' => [
                'label' => 'Data de vencimento da fatura',
                'parser' => fn () => self::getInvoiceDueDateByInvoiceId(
                    $this->hookParams['invoiceId']
                )
            ],
            'client_id' => [
                'label' => 'ID do cliente',
                'parser' => fn () => $this->clientId
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
