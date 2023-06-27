<?php
/**
 * Name: Pedido criado
 * Code: OrderCreated
 * Platform: WhatsApp
 * Version: 1.0.0
 * Author: Link Nacional
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\OrderCreated;

use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;

final class OrderCreatedNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'OrderCreated';
    public Hooks $hook = Hooks::AFTER_SHOPPING_CART_CHECKOUT;

    public function run(): void
    {
        $this->setClientId($this->getClientIdByInvoiceId($this->hookParams['InvoiceID']));

        $response = $this->sendMessage();

        $this->report($response, 'order', $this->hookParams['OrderID']);

        if (isset($response['messages'][0]['id'])) {
            $this->events->sendMsgToChatwootAsPrivateNote(
                $this->clientId,
                "Notificação: pedido criado #{$this->hookParams['OrderID']}"
            );
        }
    }

    public function defineParameters(): void
    {
        $this->parameters = [
            'order_id' => [
                'label' => 'ID do pedido',
                'parser' => fn () => $this->hookParams['OrderID'],
            ],
            'order_items_descrip' => [
                'label' => 'Items do pedido',
                'parser' => fn () => self::getOrderItemsDescripByOrderId(
                    $this->hookParams['OrderID']
                )
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
