<?php

/**
 * Code: Invoice6DaysLate
 */

namespace Lkn\HookNotification\Notifications\Custom;

use DateTime;
use Exception;
use Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory;
use Lkn\HookNotification\Core\Notification\Domain\AbstractCronNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;
use WHMCS\Database\Capsule;

final class Invoice6DaysLateNotification extends AbstractCronNotification
{
    public function __construct()
    {
        $parameters = [
            new NotificationParameter(
                'invoice_id',
                lkn_hn_lang('invoice_id'),
                fn (): int => $this->whmcsHookParams['invoice_id'],
            ),
            new NotificationParameter(
                'invoice_balance',
                lkn_hn_lang('invoice_balance'),
                fn (): string => getInvoiceBalance($this->whmcsHookParams['invoice_id'])
            ),
            new NotificationParameter(
                'invoice_total',
                lkn_hn_lang('invoice_total'),
                fn (): string => getInvoiceTotal($this->whmcsHookParams['invoice_id'])
            ),
            new NotificationParameter(
                'invoice_subtotal',
                lkn_hn_lang('invoice_subtotal'),
                fn (): string => getInvoiceSubtotal($this->whmcsHookParams['invoice_id'])
            ),
            new NotificationParameter(
                'invoice_due_date',
                lkn_hn_lang('invoice_due_date'),
                fn (): string => getInvoiceDueDateByInvoiceId($this->whmcsHookParams['invoice_id'])
            ),
            new NotificationParameter(
                'invoice_items',
                lkn_hn_lang('invoice_items'),
                fn (): string => $this->getInvoiceIdAndFirstItsFirstItem(),
            ),
            new NotificationParameter(
                'invoice_id_and_first_item',
                lkn_hn_lang('invoice_id_and_first_item'),
                fn (): string => getInvoicePdfUrlByInvocieId($this->whmcsHookParams['invoice_id'])
            ),
            new NotificationParameter(
                'client_id',
                lkn_hn_lang('Client ID'),
                fn (): int => $this->client->id
            ),
            new NotificationParameter(
                'client_email',
                lkn_hn_lang('Client email'),
                fn (): string => getClientEmailByClientId($this->client->id)
            ),
            new NotificationParameter(
                'client_first_name',
                lkn_hn_lang('Client first name'),
                fn (): string => getClientFirstNameByClientId($this->client->id)
            ),
            new NotificationParameter(
                'client_full_name',
                lkn_hn_lang('Client full name'),
                fn (): string => getClientFullNameByClientId($this->client->id)
            ),
        ];

        $asaasTable = Capsule::schema()->hasTable('mod_cobrancaasaasmpay');

        if ($asaasTable) {
            $parameters['invoice_pdf_url_asaas_pay'] = new NotificationParameter(
                'invoice_pdf_url_asaas_pay',
                lkn_hn_lang('invoice_pdf_url_asaas_pay'),
                fn () => $this->getAsaasPayUrl()
            );
        }

        parent::__construct(
            'Invoice6DaysLate',
            NotificationReportCategory::INVOICE,
            Hooks::DAILY_CRON_JOB,
            new NotificationParameterCollection($parameters),
            fn() => $this->whmcsHookParams['client_id'],
            fn() => $this->whmcsHookParams['report_category_id'],
        );
    }

    public function getPayload(): array
    {
        $invoices = localAPI('GetInvoices', [
            'limitnum' => 1000,
            'status' => 'Overdue',
        ]);

        $payloads = [];

        foreach ($invoices['invoices']['invoice'] as $invoice) {
            $givenDateTime   = new DateTime($invoice['duedate']);
            $currentDateTime = new DateTime();
            $interval        = $currentDateTime->diff($givenDateTime);

            if (
                $interval->days !== 6
                || $invoice['paymentmethod'] === 'freeproducts'
                || $invoice['total'] === '0.00'
            ) {
                continue;
            }

            $invoiceId = $invoice['id'];
            $clientId  = $invoice['userid'];

            $payloads[] = [
                'client_id' => $clientId,
                'report_category_id' => $invoiceId,
                'invoice_id' => $invoiceId,
            ];
        }

        return $payloads;
    }

    private function getAsaasPayUrl()
    {
        $invoicePayMethod = Capsule::table('tblinvoices')->where('id', $this->whmcsHookParams['invoice_id'])->first('paymentmethod')->paymentmethod;

        if ($invoicePayMethod !== 'cobrancaasaasmpay') {
            throw new Exception('Invoice does not belong to cobrancaasaasmpay gateway.');
        }

        $asaasPayBoletoUrl = Capsule::table('mod_cobrancaasaasmpay')->where('fatura_id', $this->whmcsHookParams['invoice_id'])->first('url_boleto')->url_boleto;

        if (empty($asaasPayBoletoUrl)) {
            throw new Exception('Could not get Asaas URL.');
        }

        return str_replace('/b/pdf/', '/i/', $asaasPayBoletoUrl);
    }

    private function getInvoiceIdAndFirstItsFirstItem(): string
    {
        $invoiceId = $this->whmcsHookParams['invoice_id'];

        return "$invoiceId " . getInvoiceItemsDescriptionsByInvoiceId($invoiceId)[0];
    }

    public function defineParameters(): void
    {
    }
}
