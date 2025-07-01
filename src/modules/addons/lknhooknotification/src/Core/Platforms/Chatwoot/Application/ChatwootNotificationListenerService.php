<?php

namespace Lkn\HookNotification\Core\Platforms\Chatwoot\Application;

use Lkn\HookNotification\Core\NotificationReport\Application\NotificationReportService;
use Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory;
use Lkn\HookNotification\Core\Notification\Domain\AbstractNotification;
use Lkn\HookNotification\Core\Notification\Domain\BuiltInNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate;
use Lkn\HookNotification\Core\Platforms\Chatwoot\Domain\ChatwootPlatform;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;

final class ChatwootNotificationListenerService
{
    public function __construct(
        private ChatwootPlatform $chatwootPlatform
    ) {
    }

    public function run(
        AbstractNotification $notification,
    ) {
        $whatsAppInboxId = $this->chatwootPlatform->platformSettings->wpInboxId;

        if ($whatsAppInboxId === null) {
            lkn_hn_log(
                Platforms::CHATWOOT->value . ': unable to send notification as note',
                [
                    'notification' => $notification,
                    'whatsAppInboxId' => $whatsAppInboxId,
                ]
            );

            return;
        }

        $categoryUrl = null;

        if ($notification->category) {
            $categoryUrl = $this->getUrlPathForObject(
                $notification->category,
                $notification->categoryId,
                $notification->client->id
            );
        }

        $privateNoteNotification = new BuiltInNotification(
            $notification->code,
            $notification->category,
            $notification->hook,
            $notification->parameters,
            $notification->findClientId,
            $notification->findCategoryId
        );

        $privateNoteNotification->finishInit($notification->whmcsHookParams);

        $templateMsg = lkn_hn_lang(
            'Notification: [1] [#[2]]([3])',
            [
                lkn_hn_lang($notification->code),
                $notification->categoryId,
                $categoryUrl,
            ]
        );

        $privateNoteTemplate = new NotificationTemplate(
            Platforms::CHATWOOT,
            $this->chatwootPlatform->platformSettings->moduleSettings->defaultClientName,
            $templateMsg,
        );

        $platformResponse = $this->chatwootPlatform->sendNotification(
            $privateNoteNotification,
            $privateNoteTemplate
        );

        $notificationReportService = new NotificationReportService();

        $notificationReportService->createReport(
            $privateNoteNotification->client->id,
            $privateNoteNotification->categoryId,
            $privateNoteNotification->category,
            $platformResponse->status,
            $platformResponse->msg,
            $privateNoteTemplate->platform,
            $privateNoteNotification->code,
            $privateNoteNotification->hook
        );
    }

    private function getUrlPathForObject(
        NotificationReportCategory $category,
        string $categoryId,
        int $clientId
    ) {
        $objectUrlPath = match ($category) {
            NotificationReportCategory::TICKET => 'supporttickets.php?action=view&id=:categoryId',
            NotificationReportCategory::SERVICE => 'clientsservices.php?userid=:clientId&id=:categoryId',
            NotificationReportCategory::INVOICE => 'invoices.php?action=edit&id=:categoryId',
            NotificationReportCategory::ORDER => 'orders.php?action=view&id=:categoryId',
            NotificationReportCategory::DOMAIN => 'clientsdomains.php?userid=:clientId&id=:categoryId'
        };

        $objectUrlPath = str_replace(':categoryId', $categoryId, $objectUrlPath);
        $objectUrlPath = str_replace(':clientId', $clientId, $objectUrlPath);

        return lkn_hn_get_admin_root_url($objectUrlPath);
    }
}
