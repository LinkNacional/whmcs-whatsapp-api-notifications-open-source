<?php
/**
 * Name: Lembrete de fatura com PDF
 * Code: InvoiceReminderPdf
 * Platform: WhatsApp
 * Version: 1.0.0
 * Author: Link Nacional
 * Description: Essa notificação consiste em disparo manual do envio da message template ao clicar no botão que fica dentro da visualização administrativa da fatura e suporta o envio do PDF da fatura.
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\InvoiceReminderPdf;

use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;
use Lkn\HookNotification\Helpers\Response;

final class InvoiceReminderPdfNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'InvoiceReminderPdf';

    public function run(): void
    {
        $clientId = $this->getClientIdByInvoiceId($this->hookParams['invoiceId']);

        $this->setClientId($clientId);

        $response = $this->sendMessage();

        $this->report($response, 'invoice', $this->hookParams['invoiceId']);

        if (isset($response['messages'][0]['id'])) {
            $this->events->sendMsgToChatwootAsPrivateNote(
                $this->clientId,
                "Notificação: lembrete de fatura com PDF #{$this->hookParams['invoiceId']}"
            );
        }

        Response::api(true, ['msg' => $this->notificationCode]);
    }

    public function defineParameters(): void
    {
        $this->parameters = [
            'invoice_id' => [
                'label' => 'ID da fatura',
                'parser' => fn () => $this->hookParams['invoiceId'],
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
            'invoice_pdf_url' => [
                'label' => 'PDF da fatura',
                'parser' => fn () => self::getInvoicePdfUrlByInvocieId($this->hookParams['invoiceId'])
            ],
            'client_id' => [
                'label' => 'ID do cliente',
                'parser' => fn () => $this->clientId,
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
