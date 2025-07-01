<?php

namespace Lkn\HookNotification\Core\AdminUI\Infrastructure;

use Lkn\HookNotification\Core\Shared\Infrastructure\Repository\BaseRepository;

class LogsRepository extends BaseRepository {
    /**
     * @param  integer     $offset
     * @param  integer     $limit
     * @param  null|string $filter
     *
     * @return array{logs: array<object{action: string, response: string, date: string}>, totalLogs: int}
     */
    public function paginate(int $offset, int $limit, ?string $filter): array
    {
        $logsQuery = $this->query->table('tblmodulelog')
        ->where('module', 'lknhooknotification')
        ->whereNotIn('action', ['check license']);

        $totalLogsQuery = clone $logsQuery;

        if ($filter) {
            $logsQuery->where(function ($q) use ($filter) {
                $q->where('action', 'like', '%' . $filter . '%')
                  ->orWhere('request', 'like', '%' . $filter . '%')
                  ->orWhere('response', 'like', '%' . $filter . '%');
            });

            $totalLogsQuery->where(function ($q) use ($filter) {
                $q->where('action', 'like', '%' . $filter . '%')
                  ->orWhere('request', 'like', '%' . $filter . '%')
                  ->orWhere('response', 'like', '%' . $filter . '%');
            });
        }

        $logs = $logsQuery
            ->orderBy('date', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($logs as $log) {
            try {
                $log->response = json_decode($log->response);
                $log->request  = json_decode($log->request);
            } catch (\Throwable $th) {
            }
        }

        $totalLogs = $totalLogsQuery->count();

        return [
            'logs' => $logs->toArray(),
            'totalLogs' => $totalLogs,
        ];
    }
}
