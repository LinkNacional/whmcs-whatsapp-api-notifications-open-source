<?php

namespace Lkn\HookNotification\Core\Platforms\Baileys\Domain;

use Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportStatus;
use Lkn\HookNotification\Core\Notification\Domain\AbstractNotification;
use Lkn\HookNotification\Core\Notification\Domain\AbstractNotificationParser;
use Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate;
use Lkn\HookNotification\Core\Platforms\Common\AbstractPlatform;
use Lkn\HookNotification\Core\Platforms\Common\AbstractPlatformSettings;
use Lkn\HookNotification\Core\Platforms\Common\PlatformNotificationSendResult;
use Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient;

final class BaileysPlatform extends AbstractPlatform
{
    /**
     * @var BaileysSettings
     */
    public readonly AbstractPlatformSettings $platformSettings;
    public readonly AbstractNotificationParser $notificationParser;

    /**
     * @var BaileysApiClient
     */
    protected readonly BaseApiClient $apiClient;


    public function sendNotification(
        AbstractNotification $notification,
        NotificationTemplate $template,
    ): PlatformNotificationSendResult {
        if (!$this->platformSettings->enabled) {
            return new PlatformNotificationSendResult(
                NotificationReportStatus::NOT_SENT,
                'The platform is disabled.'
            );
        }

        $phoneNumber = $this->getPhoneNumber($notification);

        if (!$phoneNumber) {
            return new PlatformNotificationSendResult(
                NotificationReportStatus::NOT_SENT,
                'Client has no valid phone number.'
            );
        }

        $filledTemplate = $notification->fillTemplate($template);

        $apiResponse = $this->apiClient->sendTextMessage(
            $phoneNumber,
            $filledTemplate,
        );

        if ($apiResponse->httpStatusCode !== 200) {
            return new PlatformNotificationSendResult(
                NotificationReportStatus::ERROR,
                $apiResponse->body['message']
            );
        }

        return new PlatformNotificationSendResult(
            NotificationReportStatus::SENT,
            'The notification was sent.',
            $phoneNumber
        );
    }
}
