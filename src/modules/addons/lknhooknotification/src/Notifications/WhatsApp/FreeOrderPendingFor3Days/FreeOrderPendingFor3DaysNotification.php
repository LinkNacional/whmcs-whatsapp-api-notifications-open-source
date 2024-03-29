<?php

/**
 * Code: FreeOrderPendingFor3Days
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\FreeOrderPendingFor3Days;

use DateTime;
use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Config\Platforms;
use Lkn\HookNotification\Config\ReportCategory;
use Lkn\HookNotification\Config\Settings;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;
use Lkn\HookNotification\Helpers\Config;
use Lkn\HookNotification\Helpers\Logger;
use Lkn\HookNotification\Notifications\Chatwoot\WhatsAppPrivateNote\WhatsAppPrivateNoteNotification;
use Throwable;
use WHMCS\Database\Capsule;

final class FreeOrderPendingFor3DaysNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'FreeOrderPendingFor3Days';
    public Hooks|array|null $hook = Hooks::DAILY_CRON_JOB;

    public function run(): bool
    {
        $this->setReportCategory(ReportCategory::ORDER);

        // Disable the event of sending a private note to Chatwoot, which is by default for registered clients.
        $this->events = [];
        $this->enableAutoReport = false;

        $threeDaysAgo = (new DateTime())->modify('-3 days');

        $orders = Capsule::table('tblorders')
            ->where('status', 'Pending')
            ->where('amount', '0.00')
            ->whereDate('date', $threeDaysAgo)
            ->get(['id', 'userid'])
            ->toArray();

        foreach ($orders as $order) {
            $clientId = $order->userid;
            $orderId = $order->id;

            try {
                $this->setReportCategoryId($orderId);

                $this->setClientId($clientId);

                $this->setHookParams(['order_id' => $orderId]);

                $response = $this->sendMessage();

                $success = isset($response['messages'][0]['id']);

                $this->report($success);

                if (
                    $success
                    && class_exists('Lkn\HookNotification\Notifications\Chatwoot\WhatsAppPrivateNote\WhatsAppPrivateNoteNotification')
                    && Config::get(Platforms::CHATWOOT, Settings::CW_LISTEN_WHATSAPP)
                ) {
                    (new WhatsAppPrivateNoteNotification(['instance' => $this]))->run();
                }
            } catch (Throwable $th) {
                $this->report(false);

                Logger::log(
                    "{$this->getNotificationLogName()} error for order {$orderId}",
                    [
                        'msg' => 'Unable to send notification for this order.',
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
