<?php

namespace Lkn\HookNotification\Core\NotificationQueue\Infrastructure\Repositories;

use Illuminate\Database\Query\JoinClause;
use Lkn\HookNotification\Core\Shared\Infrastructure\Repository\BaseRepository;

final class NotificationQueueRepository extends BaseRepository
{
    /**
     * @param  integer|null $builkId
     * @param  string|null  $status
     * @param  string|null  $notifCode
     * @param  integer|null $clientId
     * @param  boolean|null $returnCount
     * @param  integer      $limit
     * @param  boolean      $withClient
     * @param  boolean      $withReport
     *
     * @return integer|\Illuminate\Support\Collection
     */
    public function getQueuedNotifications(
        ?int $builkId = null,
        ?string $status = null,
        ?string $notifCode = null,
        ?int $clientId = null,
        ?bool $returnCount = null,
        int $limit = 50,
        bool $withClient = false,
        bool $withReport = false,
    ): int|array {
        $query = $this->query->table('mod_lkn_hook_notification_notif_queue')->limit($limit);

        if ($builkId) {
            $query = $query->where('mod_lkn_hook_notification_notif_queue.bulk_id', $builkId);
        }

        if ($status) {
            $query = $query->where('mod_lkn_hook_notification_notif_queue.status', $status);
        }

        if ($notifCode) {
            $query = $query->where('mod_lkn_hook_notification_notif_queue.notif_code', $notifCode);
        }

        if ($clientId) {
            $query = $query->where('mod_lkn_hook_notification_notif_queue.client_id', $clientId);
        }

        if ($withClient) {
            $query = $query
                ->leftJoin(
                    'tblclients',
                    'tblclients.id',
                    '=',
                    'mod_lkn_hook_notification_notif_queue.client_id'
                )
                ->selectRaw(
                    "mod_lkn_hook_notification_notif_queue.id,
                    mod_lkn_hook_notification_notif_queue.bulk_id,
                    mod_lkn_hook_notification_notif_queue.status,
                    mod_lkn_hook_notification_notif_queue.notif_code,
                    mod_lkn_hook_notification_notif_queue.client_id,
                    CONCAT(tblclients.firstname, ' ', tblclients.lastname) AS full_name"
                );
        }

        if ($withReport) {
            $latestReports = $this->query->table('mod_lkn_hook_notification_reports')
                ->selectRaw('MAX(id) as id, queue_id')
                ->groupBy('queue_id');

            $query = $query
                ->leftJoinSub($latestReports, 'latest_ids', function (JoinClause $join) {
                    $join->on('latest_ids.queue_id', '=', 'mod_lkn_hook_notification_notif_queue.id');
                })
                // @phpstan-ignore argument.type
                ->leftJoin('mod_lkn_hook_notification_reports', function (JoinClause $join) {
                    $join->on('mod_lkn_hook_notification_reports.id', '=', 'latest_ids.id');
                })
                ->selectRaw(
                    "mod_lkn_hook_notification_notif_queue.id,
                    mod_lkn_hook_notification_notif_queue.bulk_id,
                    mod_lkn_hook_notification_notif_queue.status,
                    mod_lkn_hook_notification_notif_queue.notif_code,
                    mod_lkn_hook_notification_notif_queue.client_id,
                    CONCAT(tblclients.firstname, ' ', tblclients.lastname) AS full_name,
                    mod_lkn_hook_notification_reports.status AS report_status,
                    mod_lkn_hook_notification_reports.msg AS report_msg,
                    mod_lkn_hook_notification_reports.target AS report_target"
                );
        }

        if ($returnCount) {
            return $query->count();
        }

        $a = $query->get();

        return $a->toArray();
    }

    /**
     * @param  array<string, string|int>[] $toQueue
     *
     * @return boolean
     */
    public function insertToQueue(array $toQueue): bool
    {
        $result = $this->query
            ->table('mod_lkn_hook_notification_notif_queue')
            ->insert($toQueue);

        return $result;
    }

    public function updateQueuedNotification(
        int $queuedNotificationId,
        string $status,
    ): int {
        $result = $this->query
            ->table('mod_lkn_hook_notification_notif_queue')
            ->where('id', $queuedNotificationId)
            ->update(['status' => $status]);


        return $result;
    }
}
