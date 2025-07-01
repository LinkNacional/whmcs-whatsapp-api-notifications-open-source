<?php

namespace Lkn\HookNotification\Core\NotificationQueue\Application;

use Lkn\HookNotification\Core\NotificationQueue\Domain\QueuedNotification;
use Lkn\HookNotification\Core\NotificationQueue\Domain\QueuedNotificationStatus;
use Lkn\HookNotification\Core\NotificationQueue\Infrastructure\Repositories\NotificationQueueRepository;

final class NotificationQueueService
{
    private readonly NotificationQueueRepository $notificationQueueRepository;

    public function __construct()
    {
        $this->notificationQueueRepository = new NotificationQueueRepository();
    }

    /**
     * @param  integer $bulkId
     * @param  int[]   $clientIds
     *
     * @return void
     */
    public function insertFromBulkToQueue(
        int $bulkId,
        array $clientIds,
    ) {
        /** @var array<array{bulk_id: int, status: string, notif_code: string, client_id: int}> $toAdd */
        $toAdd = [];

        foreach ($clientIds as $clientId) {
            $toAdd[] = [
                'bulk_id' => $bulkId,
                'status' => 'waiting',
                'notif_code' => '',
                'client_id' => $clientId,
            ];
        }

        $result = $this->notificationQueueRepository->insertToQueue($toAdd);

        return $result;
    }

    public function getQueuedNotifications(
        ?int $bulkId = null,
        ?QueuedNotificationStatus $status = null,
        int $limit = 50,
        bool $withClient = false,
        bool $withReport = false,
    ) {
        $rawQueuedNotifcations     = $this->notificationQueueRepository->getQueuedNotifications(
            $bulkId,
            $status->value,
            limit: $limit,
            withClient: $withClient,
            withReport: $withReport
        );
        $parsedQueuedNotifications = [];

        foreach ($rawQueuedNotifcations as $raw) {
            $parsedQueuedNotifications[] = new QueuedNotification(
                $raw->id,
                $raw->bulk_id,
                $raw->bulk_tpl,
                QueuedNotificationStatus::from($raw->status),
                $raw->notif_code,
                $raw->client_id,
                $withClient
                    ? [
                        'full_name' => $raw->full_name,
                    ]
                    : null,
                $withReport
                    ? [
                        'status' => $raw->report_status,
                        'msg' => $raw->report_msg,
                        'target' => $raw->report_target,
                    ]
                    : null
            );
        }

        return $parsedQueuedNotifications;
    }

    public function countQueuedNotifications(
        ?int $bulkId = null,
        ?QueuedNotificationStatus $status = null
    ): int {
        return $this->notificationQueueRepository->getQueuedNotifications(
            $bulkId,
            $status->value,
            returnCount: true
        );
    }

    public function updateQueuedNotificationStatus(
        int $queuedNotificationId,
        QueuedNotificationStatus $status
    ): int {
        $result = $this->notificationQueueRepository->updateQueuedNotification(
            $queuedNotificationId,
            status: $status->value,
        );

        lkn_hn_log(
            'Change bulk notification status',
            [
                'queued_notification_id' => $queuedNotificationId,
                'status' => $status,
            ],
            [
                'result' => $result,
            ]
        );

        return $result;
    }
}
