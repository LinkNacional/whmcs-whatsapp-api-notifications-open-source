<?php

/**
 * Code: NewServiceInvoice
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\NewServiceInvoice;

use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Config\ReportCategory;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;
use Lkn\HookNotification\Helpers\Logger;
use WHMCS\Database\Capsule;

final class NewServiceInvoiceNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'NewServiceInvoice';
    public Hooks|array|null $hook = Hooks::INVOICE_CREATED;

    public function run(): bool
    {
        $invoiceId = $this->hookParams['invoiceid'];

        $isInvoiceForAnOrder = Capsule::table('tblorders')->where('invoiceid', $invoiceId)->exists();

        if ($isInvoiceForAnOrder) {
            $this->events = [];
            $this->enableAutoReport = false;

            Logger::log(
                "{$this->getNotificationLogName()} aborted",
                [
                    'msg' => 'aborted for it"s not an existing service invoice',
                    'instance' => $this
                ]
            );

            return false;
        }

        // Setup properties for reporting purposes (not required).
        $this->setReportCategory(ReportCategory::INVOICE);
        $this->setReportCategoryId($invoiceId);

        // Setup client ID for getting its WhatsApp number (required).
        $clientId = $this->getClientIdByInvoiceId($this->hookParams['invoiceid']);

        $this->setClientId($clientId);
        // Send the message and get the raw response (converted to array) from WhatsApp API.
        $response = $this->sendMessage();

        // Defines if response tells if the message was sent successfully.
        $success = isset($response['messages'][0]['id']);

        return $success;
    }

    public function defineParameters(): void
    {
        $this->parameters = [
            'invoice_id' => [
                'label' => $this->lang['invoice_id'],
                'parser' => fn () => $this->hookParams['invoiceid']
            ],
            'invoice_items' => [
                'label' => $this->lang['invoice_items'],
                'parser' => fn () => self::getOrderItemsDescripByOrderId($this->hookParams['invoiceid'])
            ],
            'invoice_due_date' => [
                'label' => $this->lang['invoice_due_date'],
                'parser' => fn () => self::getInvoiceDueDateByInvoiceId($this->hookParams['invoiceid'])
            ],
            'client_id' => [
                'label' => $this->lang['client_id'],
                'parser' => fn () => $this->clientId
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
