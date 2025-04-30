<?php

namespace Lkn\HookNotification\Notifications;

use Lkn\HookNotification\Core\Notification\Domain\AbstractManualNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;

final class InvoiceReminderPdfNotification extends AbstractManualNotification
{
    public function __construct()
    {
        parent::__construct(
            'InvoiceReminderPdf',
            NotificationReportCategory::INVOICE,
            Hooks::ADMIN_INVOICES_CONTROLS_OUTPUT,
            new NotificationParameterCollection([
                new NotificationParameter(
                    'invoice_id',
                    lkn_hn_lang('Invoice ID'),
                    fn (): int => $this->whmcsHookParams['invoiceid']
                ),
                new NotificationParameter(
                    'invoice_items',
                    lkn_hn_lang('Invoice items'),
                    fn (): string => getItemsRelatedToInvoice($this->whmcsHookParams['invoiceid'])
                ),
                new NotificationParameter(
                    'invoice_due_date',
                    lkn_hn_lang('Invoice due date'),
                    fn (): string => getInvoiceDueDateByInvoiceId($this->whmcsHookParams['invoiceid'])
                ),
                new NotificationParameter(
                    'invoice_pdf_url',
                    lkn_hn_lang('Invoice PDF URL'),
                    fn (): string => getInvoicePdfUrlByInvocieId($this->whmcsHookParams['invoiceid'])
                ),
                new NotificationParameter(
                    'invoice_balance',
                    lkn_hn_lang('Invoice balance'),
                    fn (): string => getInvoiceBalance($this->whmcsHookParams['invoiceid'])
                ),
                new NotificationParameter(
                    'invoice_total',
                    lkn_hn_lang('Invoice total'),
                    fn (): string => getInvoiceTotal($this->whmcsHookParams['invoiceid'])
                ),
                new NotificationParameter(
                    'invoice_subtotal',
                    lkn_hn_lang('Invoice subtotal'),
                    fn (): string => getInvoiceSubtotal($this->whmcsHookParams['invoiceid'])
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
            ]),
            fn() => getClientIdByInvoiceId($this->whmcsHookParams['invoiceid']),
            fn() => $this->whmcsHookParams['invoiceid']
        );
    }
}
