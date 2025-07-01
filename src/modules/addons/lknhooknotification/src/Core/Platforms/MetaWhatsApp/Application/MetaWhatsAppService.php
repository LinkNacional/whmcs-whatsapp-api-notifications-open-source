<?php

namespace Lkn\HookNotification\Core\Platforms\MetaWhatsApp\Application;

use Lkn\HookNotification\Core\Platforms\Common\Infrastructure\PlatformSettingsFactory;
use Lkn\HookNotification\Core\Platforms\MetaWhatsApp\Infrastructure\MetaWhatsAppApiClient;
use Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;
use Lkn\HookNotification\Core\Shared\Infrastructure\Repository\SettingsRepository;

final class MetaWhatsAppService
{
    private MetaWhatsAppApiClient $metaWhatsAppApiClient;

    public function __construct()
    {
        $rawMetaWhatsAppSettings = (new SettingsRepository())->getSettingsForPlatform(Platforms::WHATSAPP);

        $metaWhatsAppSettings = PlatformSettingsFactory::makeMetaWhatsAppSettings();

        $this->metaWhatsAppApiClient = new MetaWhatsAppApiClient(
            $metaWhatsAppSettings->apiVersion,
            $metaWhatsAppSettings->phoneNumberId,
            $metaWhatsAppSettings->userAccessToken,
            $metaWhatsAppSettings->businessAccountId
        );
    }

    public function getMessageTemplatesForView(): ApiResponse
    {
        return $this->metaWhatsAppApiClient->getMessageTemplates(
            [
                'fields' => 'name,language,components,status&status=APPROVED',
                'limit' => 9999,
            ]
        );
    }
}
