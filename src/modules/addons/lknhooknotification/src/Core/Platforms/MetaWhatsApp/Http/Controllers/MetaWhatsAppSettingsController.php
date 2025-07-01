<?php

namespace Lkn\HookNotification\Core\Platforms\MetaWhatsApp\Http\Controllers;

use Lkn\HookNotification\Core\Platforms\Common\Infrastructure\PlatformApiClientFactory;
use Lkn\HookNotification\Core\Platforms\Common\Infrastructure\PlatformSettingsFactory;
use Lkn\HookNotification\Core\Platforms\MetaWhatsApp\Infrastructure\MetaWhatsAppApiClient;
use Lkn\HookNotification\Core\Shared\Infrastructure\Interfaces\BaseController;
use Lkn\HookNotification\Core\Shared\Infrastructure\View\View;

final class MetaWhatsAppSettingsController extends BaseController
{
    private MetaWhatsAppApiClient $metaWhatsAppApiClient;

    public function __construct()
    {
        $this->metaWhatsAppApiClient = (new PlatformApiClientFactory())->makeMetaWhatsAppClient(PlatformSettingsFactory::makeMetaWhatsAppSettings());

        parent::__construct(new View());
    }

    public function handle(array $request)
    {
        if (!$this->metaWhatsAppApiClient->areSettingsFilled()) {
            return $this->view->view('connection_info', ['step' => 1])->render();
        }

        $response = $this->metaWhatsAppApiClient->getPhoneNumberStatus();

        if (empty($response->body['name'])) {
            return $this->view->view(
                'connection_info',
                [
                    'step'=>  'error',
                    'error' => lkn_hn_safe_json_encode($response->toArray()),
                ]
            )->render();
        }

        return $this->view->view(
            'connection_info',
            [
                'connected_to_name' => $response->body['name'],
            ]
        )->render();
    }
}
