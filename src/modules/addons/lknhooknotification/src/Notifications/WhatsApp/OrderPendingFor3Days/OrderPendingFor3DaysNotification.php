<?php
/**
 * Name: Pedido pendente por três dias
 * Code: OrderPendingFor3Days
 * Platform: WhatsApp
 * Version: 1.0.0
 * Author: Link Nacional
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\OrderPendingFor3Days;

use DateTime;
use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;

final class OrderPendingFor3DaysNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'OrderPendingFor3Days';
    public Hooks $hook = Hooks::DAILY_CRON_JOB;

    public function run(): void
    {
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

            if ($interval->days === 3) {
                $this->setClientId($clientId);

                $response = $this->sendMessage();

                $this->report($response, 'order', $orderId);

                $this->setHookParams(['order_id' => $orderId]);

                if (isset($response['messages'][0]['id'])) {
                    $this->events->sendMsgToChatwootAsPrivateNote(
                        $this->clientId,
                        "Notificação: pedido pendente há 3 dias #{$orderId}"
                    );
                }
            }
        }
    }

    public function defineParameters(): void
    {
        $this->parameters = [
            'order_id' => [
                'label' => 'ID do pedido',
                'parser' => fn () => $this->hookParams['order_id'],
            ],
            'order_items_descrip' => [
                'label' => 'Items do pedido',
                'parser' => fn () => self::getOrderItemsDescripByOrderId($this->hookParams['order_id'])
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
}
