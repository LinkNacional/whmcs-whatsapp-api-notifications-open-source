<?php

namespace Lkn\HookNotification\Core\Notification\Infrastructure;

use Lkn\HookNotification\Core\NotificationReport\Application\NotificationReportService;
use Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory;
use Lkn\HookNotification\Core\Notification\Application\NotificationFactory;
use Lkn\HookNotification\Core\Notification\Application\Services\NotificationSender;
use Lkn\HookNotification\Core\Platforms\Common\PlatformNotificationSendResult;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;
use Lkn\HookNotification\Core\Shared\Infrastructure\View\View;
use Throwable;

final class ManualNotificationHookListener
{
    private readonly View $view;
    private readonly NotificationFactory $notificationFactory;
    private readonly NotificationReportService $notificationReportService;
    private readonly NotificationSender $notificationSender;

    public function __construct()
    {
        $this->notificationSender = NotificationSender::getInstance();
        $this->view               =  new View();
        $this->view->setTemplateDir(__DIR__ . '/../Http/Views');
        $this->notificationFactory       = NotificationFactory::getInstance();
        $this->notificationReportService = new NotificationReportService();
    }

    public function listenFor(Hooks $hook): void
    {
        $notificationsForHook = $this->notificationFactory->makeAllForHook(
            $hook,
            true
        );

        if (count($notificationsForHook) === 0) {
            return;
        }

        add_hook(
            $hook->value,
            999,
            function (array $whmcsHookParams) use ($hook, $notificationsForHook): ?string {
                try {
                    $reports = $this->buildReports();

                    $viewParams = [
                        'hook' => $hook,
                        'whmcsHookParams' => $whmcsHookParams,
                        'notification_reports' => $reports,
                    ];

                    $wasSent = $this->listenToTrigger();

                    $viewParams['notification_send_result'] = $wasSent;
                    $viewParams['notifications']            = $notificationsForHook;

                    return $this->view->view(
                        'components/manual_notification',
                        $viewParams
                    )->render();
                } catch (Throwable $th) {
                    lkn_hn_log(
                        'manual listener error',
                        [
                            'notificationsForHook' => $notificationsForHook,
                            'hook' => $hook->name,
                        ],
                        [
                            'exception' => $th->__toString(),
                        ]
                    );
                }

                return null;
            }
        );
    }

    /**
     * @return ?array
     */
    private function listenToTrigger(): ?array
    {
        /** @var string $notificationCode */
        $notificationCode = $_POST['lkn-hn-manual-notif-code'];

        if (empty($notificationCode)) {
            return null;
        }

        unset($_POST['lkn-hn-manual-notif-code']);

        $notification = $this->notificationFactory->makeByCode($notificationCode);

        if (!$notification) {
            return null;
        }

        $whmcsHookParams = $_POST;

        $platformSendResult = $this->notificationSender->dispatchNotification($notification, $whmcsHookParams);

        if ($platformSendResult instanceof PlatformNotificationSendResult) {
            return ['code' => $platformSendResult->status->label(), 'msg' => $platformSendResult->msg];
        } else {
            return ['code' => $platformSendResult->code, 'msg' => $platformSendResult->msg];
        }

        return null;
    }

    private function buildReports()
    {
        $reports = $this->notificationReportService->getReportsForCategory(
            NotificationReportCategory::INVOICE,
            $_GET['id']
        );

        return $reports;
    }
}
