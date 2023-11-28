<?php
/**
 * Code: InvoiceReminder
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\InvoiceReminder;

use Lkn\HookNotification\Config\ReportCategory;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;

final class InvoiceReminderNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'InvoiceReminder';

    public function run(): bool
    {
        // Setup properties for reporting purposes (not required).
        $this->setReportCategory(ReportCategory::INVOICE);
        $this->setReportCategoryId($this->hookParams['invoiceId']);

        // Setup client ID for getting its WhatsApp number (required).
        $clientId = $this->getClientIdByInvoiceId($this->hookParams['invoiceId']);

        $this->setClientId($clientId);
        // Send the message and get the raw response (converted to array) from WhatsApp API.
        $response = $this->sendMessage();

        // Defines if response tells if the message was sent successfully.
        $success = isset($response['messages'][0]['id']);

        return $success;
    }

    private function getAsaasPayUrl()
    {
        $invoicePayMethod = Capsule::table('tblinvoices')->where('id', $this->hookParams['invoiceId'])->first('paymentmethod')->paymentmethod;

        if ($invoicePayMethod !== 'cobrancaasaasmpay') {
            return;
        }

        $asaasPayBoletoUrl = Capsule::table('mod_cobrancaasaasmpay')->where('fatura_id', $this->hookParams['invoiceId'])->first('url_boleto')->url_boleto;

        if (empty($asaasPayBoletoUrl)) {
            throw new Exception('Could not get Asaas URL.');
        }

        return str_replace('/b/pdf/', '/i/', $asaasPayBoletoUrl);
    }

    public function defineParameters(): void
    {
        $this->parameters = [
            'invoice_id' => [
                'label' => $this->lang['invoice_id'],
                'parser' => fn () => $this->hookParams['invoiceId']
            ],
            'invoice_balance' => [
                'label' => $this->lang['invoice_balance'],
                'parser' => fn (): string => self::getInvoiceBalance($this->hookParams['invoiceId'])
            ],
            'invoice_total' => [
                'label' => $this->lang['invoice_total'],
                'parser' => fn (): string => self::getInvoiceTotal($this->hookParams['invoiceId'])
            ],
            'invoice_subtotal' => [
                'label' => $this->lang['invoice_subtotal'],
                'parser' => fn (): string => self::getInvoiceSubtotal($this->hookParams['invoiceId'])
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
            'invoice_pdf_url_asaas_pay' => [
                'label' => $this->lang['invoice_pdf_url_asaas_pay'],
                'parser' => fn () => $this->getAsaasPayUrl()
            ],
            'client_id' => [
                'label' => $this->lang['client_id'],
                'parser' => fn () => $this->clientId
            ],
            'client_email' => [
                'label' => $this->lang['client_email'],
                'parser' => fn () => $this->getClientEmailByClientId($this->clientId)
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
