<?php

/**
 * Code: OrderCancelled
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\OrderCancelled;

use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Config\ReportCategory;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;

final class OrderCancelledNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'OrderCancelled';
    public Hooks|array|null $hook = [Hooks::CANCEL_ORDER, Hooks::CANCEL_AND_REFUND_ORDER];

    public function run(): bool
    {
        // Setup properties for reporting purposes (not required).
        $this->setReportCategory(ReportCategory::ORDER);
        $this->setReportCategoryId($this->hookParams['orderid']);

        // Setup client ID for getting its WhatsApp number (required).
        $this->setClientId($this->getClientIdByOrderId($this->hookParams['orderid']));

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
