<?php

namespace Lkn\HookNotification\Core\Platforms\EvolutionApi\Application;

use Lkn\HookNotification\Core\Platforms\EvolutionApi\Infrastructure\EvolutionApiClient;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings;
use Lkn\HookNotification\Core\Shared\Infrastructure\Repository\SettingsRepository;
use Lkn\HookNotification\Core\Shared\Infrastructure\Result;

final class EvolutionApiSetupService
{
    private SettingsRepository $settingsRepository;
    private EvolutionAPiClient $evolutionApiClient;

    public function __construct()
    {
        $this->settingsRepository = new SettingsRepository();
        $this->evolutionApiClient = new EvolutionApiClient(
            lkn_hn_config(Settings::WP_EVO_API_URL),
            lkn_hn_config(Settings::WP_EVO_API_KEY),
        );
    }

    public function setup()
    {
        $evolutionApiUrl = lkn_hn_config(Settings::WP_EVO_API_URL);
        $evolutionApiKey = lkn_hn_config(Settings::WP_EVO_API_KEY);

        // Check if necessary settings are set.

        if (empty($evolutionApiUrl) || empty($evolutionApiKey)) {
            return lkn_hn_result(code: 'empty-api-credentials');
        }

        // Check if can connect to Evolution API.

        $evolutionInfoRes = $this->evolutionApiClient->getInformation();

        if (empty($evolutionInfoRes->body['status'])) {
            return lkn_hn_result(
                code: 'unable-to-connect-to-evolution',
                errors: [
                    'evo-api-res' => $evolutionInfoRes->body,
                ]
            );
        }

        // Check if an instance exist.

        $instanceName = lkn_hn_config(Settings::WP_EVO_INSTANCE_NAME);

        if (empty($instanceName)) {
            $result = $this->createNewInstance();

            if ($result->code === 'success') {
                $instanceName = lkn_hn_config(Settings::WP_EVO_INSTANCE_NAME);
            } else {
                return $result;
            }
        }

        // Check if an instance exist.

        $getInstanceRes = $this->evolutionApiClient->getInstance($instanceName);

        if (empty($getInstanceRes->body[0]['id'])) {
            $result = $this->createNewInstance();

            if ($result->code === 'success') {
                $instanceName = lkn_hn_config(Settings::WP_EVO_INSTANCE_NAME);
            } else {
                return $result;
            }
        }

        // Check if instance is connected to WhatsApp.

        $getInstanceRes = $this->evolutionApiClient->getInstance($instanceName);

        $instanceConnectionStatus = $getInstanceRes->body[0]['connectionStatus'];

        if (
            in_array($instanceConnectionStatus, ['connecting', 'close'], true)
        ) {
            $connectInstanceRes = $this->evolutionApiClient->getWhatsAppQrCodeForInstance($instanceName);

            return lkn_hn_result(
                code: 'step-2-read-qr-code',
                data: [
                    'qr_code_base64' => $connectInstanceRes->body['base64'],
                ]
            );
        }

        $connectedPhoneNumber = explode('@', $getInstanceRes->body['0']['ownerJid'])[0];

        return lkn_hn_result(
            code: 'step-3-connected',
            data: [
                'profilePicUrl' => $getInstanceRes->body[0]['profilePicUrl'],
                'connectedPhoneNumber' => $connectedPhoneNumber,
            ],
            errors: [
                'evo-api-res' => $evolutionInfoRes->body,
            ]
        );
    }

    private function createNewInstance()
    {
        $newInstanceName    = uniqid();
        $createdInstanceRes = $this->evolutionApiClient->createInstance($newInstanceName);

        if (empty($createdInstanceRes->body['instance']['instanceName'])) {
            return lkn_hn_result(
                code: 'unable-to-create-instance',
                errors: [
                    'evo-api-res' => $createdInstanceRes->body,
                ]
            );
        }

        $instanceName = $createdInstanceRes->body['instance']['instanceName'];

        lkn_hn_config_set(
            Platforms::WP_EVO,
            Settings::WP_EVO_INSTANCE_NAME,
            $instanceName
        );

        return lkn_hn_result(
            code: 'success',
            errors: [
                'evo-api-res' => $createdInstanceRes->body,
            ]
        );
    }

    public function disconnectInstance(): Result
    {
        $instanceName = lkn_hn_config(Settings::WP_EVO_INSTANCE_NAME);

        $result = $this->evolutionApiClient->disconnectInstanceFromWp($instanceName);

        if (empty($result->body['response']['message'])) {
            return lkn_hn_result(
                code: 'api-error',
                errors: [
                    'evo-api-res' => $createdInstanceRes->body,
                ]
            );
        }

        return lkn_hn_result(code: 'success');
    }
}
