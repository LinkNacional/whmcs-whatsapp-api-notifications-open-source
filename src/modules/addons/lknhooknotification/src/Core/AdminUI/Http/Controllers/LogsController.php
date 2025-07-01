<?php

namespace Lkn\HookNotification\Core\AdminUI\Http\Controllers;

use Lkn\HookNotification\Core\AdminUI\Infrastructure\LogsRepository;
use Lkn\HookNotification\Core\Shared\Infrastructure\Interfaces\BaseController;
use Lkn\HookNotification\Core\Shared\Infrastructure\View\View;

final class LogsController extends BaseController
{
    private readonly LogsRepository $logsRepository;

    public function __construct(View $view)
    {
        $this->logsRepository = new LogsRepository();

        parent::__construct($view);
    }

    public function viewLogs(array $request): void
    {
        if (isset($request['download-last-100-logs'])) {
            $logsForView = $this->logsRepository->paginate(0, 100, filter: $filter);

            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="logs.json"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');

            echo json_encode($logsForView, JSON_PRETTY_PRINT);

            exit;
        }

        $logsPerPage = 12;
        $currentPage = $request['pageN'] ?? 1;
        $offset      = ($currentPage - 1) * $logsPerPage;
        $filter      = $request['filter'] ? urldecode($request['filter']) : null;

        $logsForView = $this->logsRepository->paginate($offset, $logsPerPage, filter: $filter);

        foreach ($logsForView['logs'] as $log) {
            try {
                $log->response = htmlentities(json_encode($log->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                $log->request  = htmlentities(json_encode($log->request, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            } catch (\Throwable $th) {
                     echo $th;
            }
        }

        $totalPages = (int) ceil($logsForView['totalLogs'] / $logsPerPage);

        if ($currentPage > $totalPages && $totalPages > 0) {
            header('Location: ?module=lknhooknotification&page=logs&pageN=1' . ($filter ? '&filter=' . urlencode($filter) : ''));
            exit;
        }

        $this->view->view(
            'pages/logs',
            [
                'logs' => $logsForView['logs'],
                'current_page' => $currentPage,
                'reports_per_page' => $logsPerPage,
                'total_reports' => $logsForView['totalLogs'],
                'filter' => $filter,
            ],
        );
    }
}
