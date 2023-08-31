<?php

/**
 * Code: OrderCancelled
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\OrderCancelled;

use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Config\ReportCategory;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;
use Lkn\HookNotification\Helpers\Logger;
use WHMCS\Database\Capsule;

final class OrderCancelledNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'OrderCancelled';
    public Hooks|array|null $hook = [Hooks::CANCEL_ORDER, Hooks::CANCEL_AND_REFUND_ORDER];

    public function run(): bool
    {
        $orderId = $this->hookParams['orderid'];
        // Setup properties for reporting purposes (not required).
        $this->setReportCategory(ReportCategory::ORDER);
        $this->setReportCategoryId($orderId);

        $clientId = $this->getClientIdByOrderId($orderId);

        // Setup client ID for getting its WhatsApp number (required).
        $this->setClientId($clientId);

        // CancelOrder hook is also called when a order is deleted.
        // An admin may have cancelled the order before deleting it, so this checks if a notification was already sent.
        $wasAlreadySent = Capsule::table('mod_lkn_hook_notification_reports')
            ->where('client_id', $clientId)
            ->where('category_id', $this->reportCategoryId)
            ->where('category', $this->reportCategory->value)
            ->where('platform', $this->platform->value)
            ->where('notification', $this->notificationCode)
            ->exists();

        if ($wasAlreadySent) {
            Logger::log(
                "{$this->getNotificationLogName()} abort",
                [
                    'msg' => 'Notification about cancelled order was previously sent.',
                    'context' => ['instance' => $this]
                ]
            );

            return false;
        }

        // Send the message and get the raw response (converted to array) from WhatsApp API.
        $response = $this->sendMessage();

        // Defines if response tells if the message was sent successfully.
        $success = isset($response['messages'][0]['id']);

        return $success;
    }

    public function defineParameters(): void
    {
        $this->parameters = [
            'order_id' => [
                'label' => $this->lang['order_id'],
                'parser' => fn () => $this->hookParams['orderid'],
            ],
            'order_items_descrip' => [
                'label' => $this->lang['order_items_descrip'],
                'parser' => fn () => self::getOrderItemsDescripByOrderId($this->hookParams['orderid'])
            ],
            'client_first_name' => [
                'label' => $this->lang['client_first_name'],
                'parser' => fn () => $this->getClientFirstNameByClientId($this->clientId),
            ],
            'client_full_name' => [
                'label' => $this->lang['client_full_name'],
                'parser' => fn () => $this->getClientFullNameByClientId($this->clientId),
            ]
        ];
    }
}
