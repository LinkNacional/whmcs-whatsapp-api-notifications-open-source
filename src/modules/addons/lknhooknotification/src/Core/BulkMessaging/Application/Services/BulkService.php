<?php

namespace Lkn\HookNotification\Core\BulkMessaging\Application\Services;

use DateTime;
use DateTimeZone;
use Lkn\HookNotification\Core\BulkMessaging\Domain\Bulk;
use Lkn\HookNotification\Core\BulkMessaging\Domain\BulkStatus;
use Lkn\HookNotification\Core\BulkMessaging\Http\NewBulkRequest;
use Lkn\HookNotification\Core\BulkMessaging\Infrastructure\BulkRepository;
use Lkn\HookNotification\Core\NotificationQueue\Application\NotificationQueueService;
use Lkn\HookNotification\Core\NotificationQueue\Domain\QueuedNotificationStatus;
use Lkn\HookNotification\Core\Notification\Application\Services\NotificationService;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;
use Lkn\HookNotification\Core\Shared\Infrastructure\Result;
use Throwable;
use WHMCS\Database\Capsule;

final class BulkService
{
    private readonly BulkRepository $bulkRepository;
    private readonly NotificationQueueService $notificationQueueService;
    private readonly NotificationService $notificationService;

    public function __construct()
    {
        $this->bulkRepository           = new BulkRepository();
        $this->notificationQueueService = new NotificationQueueService();
        $this->notificationService      = new NotificationService();
    }

    public function updateBulkMessageStatus(
        int $queuedNotificationid,
        QueuedNotificationStatus $status
    ): bool {
        return $this->notificationQueueService->updateQueuedNotificationStatus(
            $queuedNotificationid,
            $status
        );
    }

    public function updateBulkStatus(int $bulkId, BulkStatus $status): bool
    {
        $result = $this->bulkRepository->updateBulk($bulkId, status: $status->value);

        if ($result) {
            if ($status === BulkStatus::ABORTED) {
                /** @var QueuedNotification[] $inProgressBulkMessages */
                [$inProgressBulkMessages, $x, $y] = $this->getInProgressBulkMessages($bulkId, 99999999);

                foreach ($inProgressBulkMessages as $message) {
                    $this->updateBulkMessageStatus($message->id, QueuedNotificationStatus::ABORTED);
                }
            }
        }

        return $result;
    }

    public function updateBulkMessageProgress(
        int $bulkId,
        int $totalWaitingBulkMessages,
        int $totalBulkMessages,
    ) {
        $processed   = $totalBulkMessages - $totalWaitingBulkMessages;
        $progress    = ($processed / $totalBulkMessages) * 100;
        $progress    = min(max($progress, 0), 100);
        $completedAt = null;
        $status      = BulkStatus::IN_PROGRESS->value;

        if ($progress === 100) {
            $completedAt = (new DateTime())->format('Y-m-d H:i:s');
            $status      = BulkStatus::COMPLETED->value;
        }

        $this->bulkRepository->updateBulk(
            $bulkId,
            progress: $progress,
            completedAt: $completedAt,
            status: $status,
        );
    }

    /**
     * @return Bulk[]
     */
    public function getInProgressBulks()
    {
        $rawInProgressBulks = $this->bulkRepository->getInProgressBulks();

        $bulks = [];

        foreach ($rawInProgressBulks as $rawBulk) {
            $bulks[] = new Bulk(
                $rawBulk->id,
                BulkStatus::from($rawBulk->status),
                $rawBulk->title,
                $rawBulk->description,
                Platforms::from($rawBulk->platform),
                new DateTime($rawBulk->start_at),
                $rawBulk->max_concurrency,
                json_decode($rawBulk->filters, true),
                $rawBulk->progress,
                new DateTime($rawBulk->created_at),
                $rawBulk->completed_at ? new DateTime($rawBulk->completed_at) : null,
                $rawBulk->template,
                json_decode($rawBulk->platform_payload, true),
            );
        }

        return $bulks;
    }

    /**
     * @param integer $bulkId
     * @param integer $limit
     *
     * @return array
     */
    public function getInProgressBulkMessages(
        int $bulkId,
        int $limit
    ): array {
        $waitingBulkMessages = $this->notificationQueueService->getQueuedNotifications(
            bulkId: $bulkId,
            status: QueuedNotificationStatus::WAITING,
            limit: $limit
        );

        $totalWaitingBulkMessages = $this->notificationQueueService->countQueuedNotifications(
            $bulkId,
            status: QueuedNotificationStatus::WAITING,
        );

        $totalBulkMessages = $this->notificationQueueService->countQueuedNotifications($bulkId);

        return [$waitingBulkMessages, $totalWaitingBulkMessages, $totalBulkMessages];
    }

    /**
     * @param  array<string, array<string>>|null $filters
     * @param boolean                           $allClients
     *
     * @return array
     */
    public function getClientsByFilter(array $filters, bool $allClients = false): array
    {
        $query = Capsule::table('tblclients')
            ->leftJoin('tblhosting', 'tblclients.id', '=', 'tblhosting.userid');

        if (!$allClients && !empty($filters['not_sending_clients'])) {
            $query->whereNotIn('tblclients.id', $filters['not_sending_clients']);
        }

        if (!empty($filters['client_locale'])) {
            $query->whereIn('tblclients.language', $filters['client_locale']);
        }

        if (!empty($filters['client_status'])) {
            $query->whereIn('tblclients.status', $filters['client_status']);
        }

        if (!empty($filters['client_country'])) {
            $query->whereIn('tblclients.country', $filters['client_country']);
        }

        if (!empty($filters['services'])) {
            $query->whereIn('tblhosting.packageid', $filters['services']);
        }

        if (!empty($filters['service_status'])) {
            $query->whereIn('tblhosting.domainstatus', $filters['service_status']);
        }

        $query
            ->selectRaw(
                "tblclients.id as value, CONCAT(tblclients.firstname, ' ', tblclients.lastname) as label"
            )
            ->distinct();

        $result = $query->get();

        return array_map(
            fn ($item) => (array) $item,
            $result->toArray()
        );
    }

    public function getBulk(int $bulkId): ?Bulk
    {
        $rawBulk = $this->bulkRepository->getBulk($bulkId);

        if (!$rawBulk) {
            return null;
        }

        return new Bulk(
            $rawBulk['id'],
            BulkStatus::from($rawBulk['status']),
            $rawBulk['title'],
            $rawBulk['description'],
            Platforms::from($rawBulk['platform']),
            new DateTime($rawBulk['start_at']),
            $rawBulk['max_concurrency'],
            json_decode($rawBulk['filters'], true),
            $rawBulk['progress'],
            new DateTime($rawBulk['created_at']),
            new DateTime($rawBulk['completed_at']),
            $rawBulk['template'],
            json_decode($rawBulk['platform_payload'], true),
        );
    }

    /**
     * @return Bulk[]
     */
    public function getBulks(): array
    {
        $rawBulk = $this->bulkRepository->getBulks();



        $bulks = [];

        foreach ($rawBulk as $rawBulk) {
            $bulks[] = new Bulk(
                $rawBulk->id,
                BulkStatus::from($rawBulk->status),
                $rawBulk->title,
                $rawBulk->description,
                Platforms::from($rawBulk->platform),
                new DateTime($rawBulk->start_at),
                $rawBulk->max_concurrency,
                json_decode($rawBulk->filters, true),
                $rawBulk->progress,
                new DateTime($rawBulk->created_at, new DateTimeZone('America/Sao_Paulo')),
                $rawBulk->completed_at ? new DateTime($rawBulk->completed_at) : null,
                $rawBulk->template,
                json_decode($rawBulk->platform_payload, true),
            );
        }

        return $bulks;
    }

    /**
     * @param NewBulkRequest $newBulkRequest
     * @param array{
     *     header-parameter?: string,
     *     body-parameters: array<int, string>,
     *     button-parameters: array<int, string>,
     *     message-template-lang: string,
     *     message-template: string,
     *     header-format: string
     * } $rawFormPost
     *
     * @return Result
     */
    public function createBulk(
        NewBulkRequest $newBulkRequest,
        array $rawFormPost
    ): Result {
        $template        = null;
        $platformPayload = null;

        if ($newBulkRequest->platform === Platforms::WHATSAPP) {
            $platformPayloadResult = $this->notificationService->handleWhatsAppPlatformPayloadForm($rawFormPost);

            if (!$platformPayloadResult->data) {
                lkn_hn_log(
                    'bulk: parse meta whatsapp template',
                    [
                        'new_bulk_request' => $newBulkRequest,
                        'raw_form_post' => $rawFormPost,
                    ],
                    [
                        'platform_payload_result' => $platformPayloadResult->toArray(),
                    ]
                );

                return lkn_hn_result(
                    'error',
                    msg: lkn_hn_lang('Unable to parse Meta WhatsApp template.')
                );
            }

            $template        = $platformPayloadResult->data['template'];
            $platformPayload = $platformPayloadResult->data['platformPayload'];
        } else {
            $template = $newBulkRequest->template;
        }

        $bulkId = $this->bulkRepository->insertBulk(
            $newBulkRequest->title ?? '',
            $newBulkRequest->status->value ?? '',
            $newBulkRequest->descrip ?? '',
            $newBulkRequest->platform->value ?? '',
            $newBulkRequest->startAt ? $newBulkRequest->startAt->format('Y-m-d H:i:s') : '',
            $newBulkRequest->maxConcurrency ?? 25,
            $newBulkRequest->filters ?? [],
            0.0,
            $template ?? '',
            $platformPayload ? lkn_hn_safe_json_encode($platformPayload) : null,
        );

        if (!$bulkId) {
            lkn_hn_log(
                'Unable to queue bulk messages',
                [
                    'new_bulk_request' => $newBulkRequest,
                    'client_ids' => $clientIds,
                ],
                [
                    'bulk_id' => $bulkId,
                ]
            );

            return lkn_hn_result(code: 'unable-to-create-bulk');
        }

        $clientIds = array_column($this->getClientsByFilter($newBulkRequest->filters), 'value');

        $result = $this->notificationQueueService->insertFromBulkToQueue($bulkId, $clientIds);

        if (!$result) {
            lkn_hn_log(
                'Unable to queue bulk messages',
                [
                    'bulk_id' => $bulkId,
                    'new_bulk_request' => $newBulkRequest,
                    'client_ids' => $clientIds,
                ],
                [
                    'result' => $result,
                ]
            );

            return lkn_hn_result(code: 'unable-to-queue-bulk-messages');
        }

         return lkn_hn_result(
             code: 'success',
             data: ['bulk_id' => $bulkId]
         );
    }

    public function getBulkReportForView(int $bulkId)
    {
        return $this->notificationQueueService->getQueuedNotifications(
            $bulkId,
            withClient: true,
            withReport: true,
        );
    }

    public function resendBulkMessage(
        int $bulkId,
        int $bulkMessageId,
    ): Result {
        try {
            [$x, $totalWaitingBulkMessages, $totalBulkMessages] = $this->getInProgressBulkMessages($bulkId, 1);

            $result = $this->notificationQueueService->updateQueuedNotificationStatus(
                $bulkMessageId,
                QueuedNotificationStatus::WAITING
            );

            if (!$result) {
                return lkn_hn_result(
                    'error',
                    msg: lkn_hn_lang('Unable to update messages status to waiting.')
                );
            }

            $processedCount = $totalBulkMessages - ($totalWaitingBulkMessages + 1);

            $newProgress = min(max(($processedCount / $totalBulkMessages) * 100, 0), 100);

            $result = $this->bulkRepository->updateBulk(
                $bulkId,
                progress: $newProgress,
            );

            return lkn_hn_result(
                $result ? 'success' : 'error',
                msg: $result ? lkn_hn_lang('The message was queued.') : lkn_hn_lang('Unable to update bulk progress.'),
            );
        } catch (Throwable $th) {
            return lkn_hn_result(
                'error',
                msg: 'Internal error',
                errors: ['exception' => $th->__toString()]
            );
        }
    }

    public function sendNow(int $bulkId): bool
    {
        return boolval(
            $this->bulkRepository->updateBulk(
                $bulkId,
                status: BulkStatus::IN_PROGRESS->value,
                startAt: (new DateTime())->format(DateTime::ATOM)
            )
        );
    }
}
