<?php

/**
 * Code: InvoiceReminderPdf
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\InvoiceReminderPdf;

use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;
use Lkn\HookNotification\Helpers\Response;

final class InvoiceReminderPdfNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'InvoiceReminderPdf';

    public function run(): bool
    {
        // Setup client ID for getting its WhatsApp number (required).
        $this->setClientId($this->getClientIdByInvoiceId($this->hookParams['invoiceId']));

        // Send the message and get the raw response (converted to array) from WhatsApp API.
        $response = $this->sendMessage();

        // Defines if response tells if the message was sent successfully.
        $success = isset($response['messages'][0]['id']);

        $this->report($response, 'invoice', $this->hookParams['invoiceId']);

        Response::api(true, ['msg' => $this->notificationCode]);

        return $success;
    }

    public function defineParameters(): void
    {
        $this->parameters = [
            'invoice_id' => [
                'label' => $this->lang['invoice_id'],
                'parser' => fn () => $this->hookParams['invoiceId'],
            ],
            'invoice_items' => [
                'label' => $this->lang['invoice_items'],
                'parser' => fn () => self::getOrderItemsDescripByOrderId($this->hookParams['invoiceId'])
            ],
            'invoice_due_date' => [
                'label' => $this->lang['invoice_due_date'],
                'parser' => fn () => self::getInvoiceDueDateByInvoiceId($this->hookParams['invoiceId'])
            ],
            'invoice_pdf_url' => [
                'label' => $this->lang['invoice_pdf_url'],
                'parser' => fn () => self::getInvoicePdfUrlByInvocieId($this->hookParams['invoiceId'])
            ],
            'client_id' => [
                'label' => $this->lang['client_id'],
                'parser' => fn () => $this->clientId,
            ],
            'client_first_name' => [
                'label' => $this->lang['client_first_name'],
                'parser' => fn () => $this->getClientFirstNameByClientId($this->clientId)
            ],
            'client_full_name' => [
                'label' => $this->lang['client_full_name'],
                'parser' => fn () => $this->getClientFullNameByClientId($this->clientId)
            ]
        ];
    }
}
