<?php

/**
 * Code: OrderPendingFor3Days
 */

namespace Lkn\HookNotification\Notifications\Custom;

use DateTime;
use Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory;
use Lkn\HookNotification\Core\Notification\Domain\AbstractCronNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;
use WHMCS\Database\Capsule;

final class OrderPendingFor3DaysNotification extends AbstractCronNotification
{
    public function __construct()
    {
        parent::__construct(
            'OrderPendingFor3Days',
            NotificationReportCategory::ORDER,
            Hooks::DAILY_CRON_JOB,
            new NotificationParameterCollection([
                new NotificationParameter(
                    'order_id',
                    lkn_hn_lang('Order ID'),
                    fn (): int => $this->whmcsHookParams['order_id']
                ),
                new NotificationParameter(
                    'order_items_descrip',
                    lkn_hn_lang('Order items description'),
                    fn (): string => getOrderItemsDescripByOrderId($this->whmcsHookParams['order_id'])
                ),
                new NotificationParameter(
                    'client_id',
                    lkn_hn_lang('Client ID'),
                    fn (): int => $this->client->id
                ),
                new NotificationParameter(
                    'client_email',
                    lkn_hn_lang('Client email'),
                    fn (): string => getClientEmailByClientId($this->client->id)
                ),
                new NotificationParameter(
                    'client_first_name',
                    lkn_hn_lang('Client first name'),
                    fn (): string => getClientFirstNameByClientId($this->client->id)
                ),
                new NotificationParameter(
                    'client_full_name',
                    lkn_hn_lang('Client full name'),
                    fn (): string => getClientFullNameByClientId($this->client->id)
                ),
            ]),
            fn() => $this->whmcsHookParams['client_id'],
            fn() => $this->whmcsHookParams['report_category_id'],
        );
    }

    public function getPayload(): array
    {
        $threeDaysAgo = (new DateTime())->modify('-3 days');

        $orders = Capsule::table('tblorders')
            ->where('status', 'Pending')
            ->where('amount', '>', '0.00')
            ->whereDate('date', $threeDaysAgo)
            ->get(['id', 'userid'])
            ->toArray();

        $payloads = [];

        foreach ($orders as $order) {
            $clientId = $order->userid;
            $orderId  = $order->id;


            $payloads[] = [
                'client_id' => $clientId,
                'order_id' => $orderId,
                'report_category_id' => $orderId,
            ];
        }

        return $payloads;
    }
}
