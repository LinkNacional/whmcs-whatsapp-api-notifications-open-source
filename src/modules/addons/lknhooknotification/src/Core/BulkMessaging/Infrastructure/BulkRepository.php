<?php

namespace Lkn\HookNotification\Core\BulkMessaging\Infrastructure;

use DateTime;
use Lkn\HookNotification\Core\BulkMessaging\Domain\BulkStatus;
use Lkn\HookNotification\Core\NotificationQueue\Domain\QueuedNotificationStatus;
use Lkn\HookNotification\Core\Shared\Infrastructure\Repository\BaseRepository;

final class BulkRepository extends BaseRepository
{

    public function getInProgressBulkMessages(int $bulkId): array
    {
        $result = $this->query
                ->table('mod_lkn_hook_notification_notif_queue')
                ->where('status', QueuedNotificationStatus::WAITING->value)
                ->where('bulk_id', $bulkId)
                ->get();

        return $result->toArray();
    }

    /**
     * @return object{
     *     id: int,
     *     status: string,
     *     title: string,
     *     description: string,
     *     platform: string,
     *     start_at: string,
     *     max_concurrency: int,
     *     filters: string,
     *     progress: float,
     *     created_at: string,
     *     completed_at: ?string,
     *     template: string,
     *     platform_payload: string,
     * }[]
     */
    public function getInProgressBulks(): array
    {
        $result = $this->query
                ->table('mod_lkn_hook_notification_bulks')
                ->where('status', '=', BulkStatus::IN_PROGRESS->value)
                ->where('progress', '!=', 100)
                ->where('start_at', '<=', (new DateTime())->format('Y-m-d H:i:s'))
                ->get();

        return $result->toArray();
    }

    /**
     * @return array{
     *     id: int,
     *     status: string,
     *     title: string,
     *     description: string,
     *     platform: string,
     *     start_at: string,
     *     max_concurrency: int,
     *     filters: string,
     *     progress: float,
     *     created_at: string,
     *     completed_at: ?string,
     *     template: string,
     *     platform_payload: string,
     * }
     */
    public function getBulk(int $bulkId): array
    {
        $result = $this->query
                ->table('mod_lkn_hook_notification_bulks')
                ->where('id', $bulkId)
                ->first();

        return (array) $result;
    }

    public function getBulks(): array
    {
        $result = $this->query
                ->table('mod_lkn_hook_notification_bulks')
                ->get();

        return $result->toArray();
    }

    public function insertBulk(
        string $title,
        string $status,
        string $description,
        string $platform,
        string $startAt,
        int $maxConcurrency,
        array $filters,
        float $progress,
        string $template,
        ?string $platformPayload,
    ): int {
        $bulkId = $this->query
            ->table('mod_lkn_hook_notification_bulks')
            ->insertGetId(
                [
                    'status' => $status,
                    'title' => $title,
                    'description' => $description,
                    'platform' => $platform,
                    'start_at' => $startAt,
                    'max_concurrency' => $maxConcurrency,
                    'filters' => lkn_hn_safe_json_encode($filters),
                    'progress' => $progress,
                    'template' => $template,
                    'platform_payload' => $platformPayload,
                ]
            );

        return $bulkId;
    }

    public function updateBulk(
        int $bulkId,
        ?string $status = null,
        ?string $completedAt = null,
        ?float $progress = null,
        ?string $startAt = null,
    ): int {
        $updateArray = [];

        $query = $this->query->table('mod_lkn_hook_notification_bulks')->where('id', $bulkId);

        if ($status) {
            $updateArray['status'] = $status;
        }

        if ($completedAt) {
            $updateArray['completed_at'] = $completedAt;
        }

        if ($startAt) {
            $updateArray['start_at'] = $startAt;
        }

        if (!is_null($progress)) {
            $updateArray['progress'] = $progress;
        }

        $result = $query->update($updateArray);

        lkn_hn_log(
            'Update bulk status',
            [
                'bulk_id' => $bulkId,
                'status' => $status,
                'completed_at' => $completedAt,
                'start_at' => $startAt,
                'progress' => $progress,
                'update_array' => $updateArray,
            ],
            [
                'result' => $result,
            ],
        );

        return $result;
    }
}
