<?php
/**
 * Name: Fatura 6 dias atrasada
 * Code: Invoice6DaysLate
 * Platform: WhatsApp
 * Version: 1.0.0
 * Author: Link Nacional
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\Invoice6DaysLate;

use DateTime;
use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;

final class Invoice6DaysLateNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'Invoice6DaysLate';
    public Hooks $hook = Hooks::DAILY_CRON_JOB;

    public function run(): void
    {
        $invoices = localAPI('GetInvoices', [
            'orderby' => 'invoicenumber',
            'limitnum' => '250',
            'status' => 'Overdue',
            'orderby' => 'invoicenumber',
            'order' => 'desc',
        ]);

        foreach ($invoices['invoices']['invoice'] as $invoice) {
            $givenDateTime = new DateTime($invoice['duedate']);
            $currentDateTime = new DateTime();
            $interval = $currentDateTime->diff($givenDateTime);

            if ($interval->days === 6) {
                if ($invoice['paymentmethod'] !== 'freeproducts') {
                    if ($invoice['total'] !== '0.00') {
                        $invoiceId = $invoice['id'];
                        $clientId = $invoice['userid'];

                        $clientId = $invoice['userid'];

                        $this->setClientId($clientId);
                        $this->setHookParams(['invoice_id' => $invoiceId]);

                        $response = $this->sendMessage();

                        $this->report($response, 'invoice', $invoiceId);

                        if (isset($response['messages'][0]['id'])) {
                            $this->events->sendMsgToChatwootAsPrivateNote(
                                $this->clientId,
                                "Notificação: fatura 6 dias atrasada #{$invoiceId}"
                            );
                        }
                    }
                }
            }
        }
    }

    public function defineParameters(): void
    {
        $this->parameters = [
            'invoice_id' => [
                'label' => 'ID da fatura',
                'parser' => fn () => $this->hookParams['invoice_id'],
            ],
            'invoice_id_and_first_item' => [
                'label' => 'ID da fatura + primeiro item da fatura',
                'parser' => fn () => $this->getInvoiceIdAndFirstItsFirstItem(),
            ],
            'client_first_name' => [
                'label' => 'Primeiro nome do cliente',
                'parser' => fn () => $this->getClientFirstNameByClientId($this->clientId),
            ],
            'client_full_name' => [
                'label' => 'Nome completo do cliente',
                'parser' => fn () => $this->getClientFullNameByClientId($this->clientId),
            ]
        ];
    }

    private function getInvoiceIdAndFirstItsFirstItem(): string
    {
        $invoiceId = $this->hookParams['invoice_id'];

        return "$invoiceId {$this->getInvoiceItemsDescriptionsByInvoiceId($invoiceId)[0]}";
    }
}
