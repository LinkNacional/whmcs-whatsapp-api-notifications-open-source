<?php

namespace Lkn\HookNotification\Core\BulkMessaging\Infrastructure;

use Lkn\HookNotification\Core\BulkMessaging\Application\Services\BulkService;
use Lkn\HookNotification\Core\BulkMessaging\Domain\BulkNotification;
use Lkn\HookNotification\Core\NotificationQueue\Domain\QueuedNotificationStatus;
use Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportStatus;
use Lkn\HookNotification\Core\Notification\Application\Services\NotificationSender;
use Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings;
use Lkn\HookNotification\Core\Shared\Infrastructure\Result;
use Lkn\HookNotification\Core\Shared\Infrastructure\Singleton;
use Throwable;

/**
 * Used in entrypoint.php
 */
final class BulkDispatcher extends Singleton
{
    private readonly BulkService $bulkMessageRepositoryService;
    private readonly NotificationSender $notificationSender;

    /**
     * @return void
     */
    protected function __construct()
    {
        $this->notificationSender           = NotificationSender::getInstance();
        $this->bulkMessageRepositoryService = new BulkService();
    }

    /**
     * @return void
     */
    public function run()
    {
        if (lkn_hn_config(Settings::BULK_ENABLE)) {
            add_hook(
                'AfterCronJob',
                999,
                function (): void {
                    try {
                        $this->dispatchBulks();
                    } catch (Throwable $th) {
                        lkn_hn_log(
                            'Bulk cron job error',
                            [],
                            [
                                'error' => $th->__toString(),
                            ]
                        );
                    }
                }
            );
        }
    }

    /**
     * @return void
     */
    public function dispatchBulks(): void
    {
        $bulks = $this->bulkMessageRepositoryService->getInProgressBulks();

        foreach ($bulks as $bulk) {
            [
                $waitingBulkMessages,
                $totalWaitingBulkMessages,
                $totalBulkMessages,
            ] = $this->bulkMessageRepositoryService->getInProgressBulkMessages(
                $bulk->id,
                limit: $bulk->maxConcurrency
            );

            /** @var \Lkn\HookNotification\Core\NotificationQueue\Domain\QueuedNotification[] $waitingBulkMessages */
            /** @var int $totalWaitingBulkMessages */
            /** @var int $totalBulkMessages */

            foreach ($waitingBulkMessages as $queuedBulkMessage) {
                $template = new NotificationTemplate(
                    $bulk->platform,
                    null,
                    $bulk->template,
                    $bulk->platformPayload ?? []
                );

                $notification = new BulkNotification();

                $notification->setTemplates([$template]);

                $platformResponse = $this->notificationSender->send(
                    $notification,
                    [
                        'client_id' => $queuedBulkMessage->clientId,
                        ...$bulk->filters,
                    ],
                    queueId: $queuedBulkMessage->id,
                );

                if ($platformResponse instanceof Result ) {
                    if (!is_null($queuedBulkMessage->id)) {
                        $this->bulkMessageRepositoryService->updateBulkMessageStatus(
                            $queuedBulkMessage->id,
                            QueuedNotificationStatus::ERROR,
                        );

                        $totalWaitingBulkMessages = $totalWaitingBulkMessages - 1;

                        $this->bulkMessageRepositoryService->updateBulkMessageProgress(
                            $bulk->id,
                            $totalWaitingBulkMessages,
                            $totalBulkMessages
                        );
                    }

                    continue;
                }

                $newQueuedNotificationStatus =
                    $platformResponse->status === NotificationReportStatus::SENT
                        ? QueuedNotificationStatus::SENT
                        : QueuedNotificationStatus::ERROR;

                if (is_null($queuedBulkMessage->id)) {
                    continue;
                }

                $this->bulkMessageRepositoryService->updateBulkMessageStatus(
                    $queuedBulkMessage->id,
                    $newQueuedNotificationStatus
                );

                $totalWaitingBulkMessages = $totalWaitingBulkMessages - 1;

                $this->bulkMessageRepositoryService->updateBulkMessageProgress(
                    $bulk->id,
                    $totalWaitingBulkMessages,
                    $totalBulkMessages
                );
            }
        }
    }
}
