<?php

/**
 * Code: OrderFraud
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\OrderFraud;

use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Config\ReportCategory;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;

final class OrderFraudNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'OrderFraud';
    public Hooks|array|null $hook = [Hooks::FRAUD_ORDER, Hooks::FRAUD_CHECK_FAILED];

    public function run(): bool
    {
        $orderId = $this->hookParams['orderid'];

        // Setup properties for reporting purposes (not required).
        $this->setReportCategory(ReportCategory::ORDER);
        $this->setReportCategoryId($orderId);

        // Setup client ID for getting its WhatsApp number (required).
        $clientId = $this->getClientIdByOrderId($orderId);
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
            'order_id' => [
                'label' => $this->lang['order_id'],
                'parser' => fn () => $this->reportCategoryId,
            ],
            'order_items_descrip' => [
                'label' => $this->lang['order_items_descrip'],
                'parser' => fn () => self::getOrderItemsDescripByOrderId($this->reportCategoryId)
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
