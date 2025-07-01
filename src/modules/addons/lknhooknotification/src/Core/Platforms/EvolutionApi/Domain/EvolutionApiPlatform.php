<?php

namespace Lkn\HookNotification\Core\Platforms\EvolutionApi\Domain;

use Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportStatus;
use Lkn\HookNotification\Core\Notification\Domain\AbstractNotification;
use Lkn\HookNotification\Core\Notification\Domain\AbstractNotificationParser;
use Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate;
use Lkn\HookNotification\Core\Platforms\Common\AbstractPlatform;
use Lkn\HookNotification\Core\Platforms\Common\AbstractPlatformSettings;
use Lkn\HookNotification\Core\Platforms\Common\PlatformNotificationSendResult;
use Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient;

final class EvolutionApiPlatform extends AbstractPlatform
{
    /**
     * @var EvolutionApiSettings
     */
    public readonly AbstractPlatformSettings $platformSettings;
    public readonly AbstractNotificationParser $notificationParser;

    /**
     * @var EvolutionApiClient
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
            lkn_hn_log(
                "{$template->platform->value}: client has no valid phone number",
                [
                    'notification' => $notification,
                    'template' => $template,
                ],
                [
                    'phoneNumber' => $phoneNumber,
                ]
            );

            return new PlatformNotificationSendResult(
                NotificationReportStatus::NOT_SENT,
                'Client has no valid phone number.',
                $phoneNumber
            );
        }

        $filledTemplate = $notification->fillTemplate($template);

        $apiResponse = $this->apiClient->sendTextMessage(
            $this->platformSettings->instanceName,
            $filledTemplate,
            $phoneNumber
        );

        if (empty($apiResponse->body['key']['id'])) {
            lkn_hn_log(
                "{$template->platform->value}: api error",
                [
                    'notification' => $notification,
                    'template' => $template,
                ],
                [
                    'api_response' => $apiResponse,
                ]
            );

            return new PlatformNotificationSendResult(
                NotificationReportStatus::ERROR,
                json_encode(
                    $apiResponse->body,
                    JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE
                ),
                $phoneNumber
            );
        }

        return new PlatformNotificationSendResult(
            NotificationReportStatus::SENT,
            'The notification was sent.',
            $phoneNumber,
            $phoneNumber
        );
    }
}
