<?php

namespace Lkn\HookNotification\Core\NotificationReport\Infrastructure;

use DateTime;
use Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory;
use Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportStatus;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;
use Lkn\HookNotification\Core\Shared\Infrastructure\Repository\BaseRepository;

final class NotificationReportRepository extends BaseRepository
{
    public function paginate(int $offset, int $limit)
    {
        $reports = $this->query->table('mod_lkn_hook_notification_reports')
            ->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        $totalReports = $this->query->table('mod_lkn_hook_notification_reports')->count();

        return [
            'reports' => $reports->toArray(),
            'totalReports' =>  $totalReports,
        ];
    }

    public function insertReport(
        int $clientId,
        ?int $categoryId,
        ?NotificationReportCategory $reportCategory,
        NotificationReportStatus $reportStatus,
        ?string $reportMsg,
        ?Platforms $platform,
        string $notificationCode,
        ?Hooks $hook,
        ?int $queueId,
        ?string $target,
    ): int {
        // TODO: add also which NotificationTemplate and whmcsHookParams to allow resend?
        return $this->query->table('mod_lkn_hook_notification_reports')
            ->insert([
                'client_id' => $clientId,
                'category_id' => $categoryId,
                'category' => $reportCategory->value,
                'status' => $reportStatus->value,
                'msg' => $reportMsg,
                'platform' => $platform->value,
                'channel' => null,
                'notification' => $notificationCode,
                'hook' => $hook ? $hook->value : null,
                'queue_id' => $queueId,
                'target' => $target,
            ]);
    }

    public function getReportsForCategory(
        NotificationReportCategory $category,
        int $categoryId
    ): array {
        return $this->query
            ->table('mod_lkn_hook_notification_reports')
            ->orderBy('created_at', 'desc')
            ->where('category', $category->value)
            ->where('category_id', $categoryId)
            ->get()
            ->toArray();
    }

    public function getReportsForLastHour()
    {
        $oneHourAgo = (new DateTime())->modify('-1 hour')->format('Y-m-d H:i:s');

        return $this->query
            ->table('mod_lkn_hook_notification_reports')
            ->where('created_at', '>=', $oneHourAgo)
            ->count();
    }

    public function getFailedReports()
    {
        $oneHourAgo = (new DateTime())->modify('-1 hour')->format('Y-m-d H:i:s');

        return $this->query
            ->table('mod_lkn_hook_notification_reports')
            ->where('created_at', '>=', $oneHourAgo)
            ->where('status', '!=', NotificationReportStatus::SENT->value)
            ->count();
    }

    public function getTopNotificationsForLastHour()
    {
        $oneHourAgo = (new DateTime())->modify('-1 hour')->format('Y-m-d H:i:s');

        return $this->query
            ->table('mod_lkn_hook_notification_reports')
            ->select(
                'notification',
                $this->query::table('mod_lkn_hook_notification_reports')->raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', $oneHourAgo)
            ->groupBy('notification')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }
}
