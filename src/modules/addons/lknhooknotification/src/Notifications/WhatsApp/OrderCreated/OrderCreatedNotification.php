<?php

/**
 * Code: OrderCreated
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\OrderCreated;

use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Config\ReportCategory;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;
use Lkn\HookNotification\Helpers\Logger;

final class OrderCreatedNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'OrderCreated';
    public Hooks|array|null $hook = Hooks::AFTER_SHOPPING_CART_CHECKOUT;

    public function run(): bool
    {
        $orderId = $this->hookParams['OrderID'];

        // Setup properties for reporting purposes (not required).
        $this->setReportCategory(ReportCategory::ORDER);
        $this->setReportCategoryId($orderId);

        // Setup client ID for getting its WhatsApp number (required).
        $this->setClientId($this->getClientIdByOrderId($this->hookParams['OrderID']));

        $fraudCheckResponse = localAPI('OrderFraudCheck', ['orderid' => $orderId]);

        if (isset($fraudCheckResponse['status']) && $fraudCheckResponse['status'] === 'Fail') {
            Logger::log(
                $this->getNotificationLogName(),
                [
                    'msg' => 'Abort for order was identified as fraud',
                    'instance' => $this
                ],
                ['fraud_check' => $fraudCheckResponse]
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
                'parser' => fn () => $this->hookParams['OrderID'],
            ],
            'order_items_descrip' => [
                'label' => $this->lang['order_items_descrip'],
                'parser' => fn () => self::getOrderItemsDescripByOrderId($this->hookParams['OrderID'])
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
