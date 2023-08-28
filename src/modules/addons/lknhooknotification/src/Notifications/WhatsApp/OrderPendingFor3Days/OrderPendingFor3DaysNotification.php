<?php

/**
 * Code: OrderPendingFor3Days
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\OrderPendingFor3Days;

use DateTime;
use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Config\ReportCategory;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;
use Lkn\HookNotification\Helpers\Logger;
use Lkn\HookNotification\Notifications\Chatwoot\WhatsAppPrivateNote\WhatsAppPrivateNoteNotification;
use Throwable;

final class OrderPendingFor3DaysNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'OrderPendingFor3Days';
    public ?Hooks $hook = Hooks::DAILY_CRON_JOB;

    public function run(): bool
    {
        $this->setReportCategory(ReportCategory::ORDER);

        // Disable the event of sending a private note to Chatwoot, which is by default for registered clients.
        $this->events = [];
        $this->enableAutoReport = false;

        // You have to manually figure out the client id using the data provided
        // by WHMCS in the add_hook function.

        $orders = localAPI('GetOrders', ['limitnum' => 100, 'status' => 'Pending']);

        foreach ($orders['orders']['order'] as $order) {
            $clientId = $order['userid'];
            $orderId = $order['id'];

            $orderCreatedAt = $order['date'];

            $givenDateTime = new DateTime($orderCreatedAt);
            $currentDateTime = new DateTime();
            $interval = $currentDateTime->diff($givenDateTime);

            try {
                if ($interval->days === 23) {
                    $this->setReportCategoryId($orderId);

                    $this->setClientId($clientId);

                    $this->setHookParams(['order_id' => $orderId]);

                    $response = $this->sendMessage();

                    $success = isset($response['messages'][0]['id']);

                    $this->report($success);

                    if ($success && class_exists('Lkn\HookNotification\Notifications\Chatwoot\WhatsAppPrivateNote\WhatsAppPrivateNoteNotification')) {
                        (new WhatsAppPrivateNoteNotification(['instance' => $this]))->run();
                        echo 'hey';
                    }
                }
            } catch (Throwable $th) {
                $this->report(false);

                Logger::log(
                    "{$this->getNotificationLogName()} error for order {$orderId}",
                    [
                        'msg' => 'Unable to send notification for this order..',
                        'context' => ['order' => $order]
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
            'order_id' => [
                'label' => $this->lang['order_id'],
                'parser' => fn () => $this->hookParams['order_id'],
            ],
            'order_items_descrip' => [
                'label' => $this->lang['order_items_descrip'],
                'parser' => fn () => self::getOrderItemsDescripByOrderId($this->hookParams['order_id'])
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
