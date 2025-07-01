<?php

namespace Lkn\HookNotification\Core\NotificationReport\Http\Controllers;

use Lkn\HookNotification\Core\NotificationReport\Application\NotificationReportService;
use Lkn\HookNotification\Core\Shared\Infrastructure\Interfaces\BaseController;
use Lkn\HookNotification\Core\Shared\Infrastructure\View\View;

final class NotificationReportController extends BaseController
{
    private NotificationReportService $notificationReportService;

    public function __construct(View $view)
    {
        $this->notificationReportService = new NotificationReportService();

        parent::__construct($view);
    }

    public function viewReports(array $request): void
    {
        $currentPage    = $request['pageN'] ?? 1;
        $reportsPerPage = 30;

        $reportsForView = $this->notificationReportService->getReportsForView($reportsPerPage, $currentPage);

        $viewParams = [
            'reports' => $reportsForView['reports'],
            'current_page' => $currentPage,
            'reports_per_page' => $reportsPerPage,
            'total_reports' => $reportsForView['totalReports'],
        ];

        $this->view->view('pages/reports', $viewParams);
    }
}
