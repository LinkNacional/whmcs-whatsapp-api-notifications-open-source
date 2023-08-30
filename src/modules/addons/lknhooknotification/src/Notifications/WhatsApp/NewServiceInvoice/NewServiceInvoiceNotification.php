<?php

/**
 * Code: NewServiceInvoice
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\NewServiceInvoice;

use DateInterval;
use DateTime;
use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Config\ReportCategory;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;
use Lkn\HookNotification\Helpers\Logger;
use Lkn\HookNotification\Notifications\Chatwoot\WhatsAppPrivateNote\WhatsAppPrivateNoteNotification;
use Throwable;
use WHMCS\Database\Capsule;

/**
 * Runs when a recurring service invoice is created.
 *
 * @since 3.2.0
 */
final class NewServiceInvoiceNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'NewServiceInvoice';

    /**
     * This notification needs to be run on the daily cron job.
     *
     * If not, the order is inserted into the DB after this notification is sent,
     * so there is no to check if the OrderCreated notification was sent or if
     * there is a order for the invoice.
     *
     * The same occurs for InvoiceCreated hook.
     *
     * @var \Lkn\HookNotification\Config\Hooks|array|null
     * @link https://developers.whmcs.com/hooks-reference/cron/#dailycronjob
     */
    public Hooks|array|null $hook = Hooks::DAILY_CRON_JOB;

    public function run(): bool
    {
        $this->events = [];
        $this->enableAutoReport = false;

        $this->setReportCategory(ReportCategory::INVOICE);

        $currentDateTime = new DateTime();
        $tenMinutesAgo = (new DateTime())->sub(new DateInterval('PT10M'));

        $invoices = Capsule::table('tblinvoices')
            ->where('created_at', '>=', $tenMinutesAgo->format('Y-m-d H:i:s'))
            ->where('created_at', '<=', $currentDateTime->format('Y-m-d H:i:s'))
            ->where('status', 'Unpaid')
            ->whereNotIn('id', function ($query) {
                $query->select('invoiceid')
                    ->from('tblorders');
            })
            ->get(['id', 'userid'])
            ->toArray();

        foreach ($invoices as $invoice) {
            $invoiceId = $invoice->id;
            $clientId = $invoice->userid;

            // Setup properties for reporting purposes (not required).
            $this->setClientId($clientId);
            $this->setReportCategoryId($invoiceId);

            try {
                // Send the message and get the raw response (converted to array) from WhatsApp API.
                $response = $this->sendMessage();

                // Defines if response tells if the message was sent successfully.
                $success = isset($response['messages'][0]['id']);

                $this->report($success);

                if ($success && class_exists('Lkn\HookNotification\Notifications\Chatwoot\WhatsAppPrivateNote\WhatsAppPrivateNoteNotification')) {
                    (new WhatsAppPrivateNoteNotification(['instance' => $this]))->run();
                }
            } catch (Throwable $th) {
                $this->report(false);

                Logger::log(
                    "{$this->getNotificationLogName()} error for invoice {$invoiceId}",
                    [
                        'msg' => 'Unable to send notification for this invoice.',
                        'context' => ['invoice' => $invoice]
                    ],
                    [
                        'response' => $response,
                        'error' => $th->__toString()
                    ]
                );
            }
        }

        return true;
    }

    public function defineParameters(): void
    {
        $this->parameters = [
            'invoice_id' => [
                'label' => $this->lang['invoice_id'],
                'parser' => fn () => $this->reportCategoryId
            ],
            'invoice_items' => [
                'label' => $this->lang['invoice_items'],
                'parser' => fn () => self::getOrderItemsDescripByOrderId($this->reportCategoryId)
            ],
            'invoice_due_date' => [
                'label' => $this->lang['invoice_due_date'],
                'parser' => fn () => self::getInvoiceDueDateByInvoiceId($this->reportCategoryId)
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
