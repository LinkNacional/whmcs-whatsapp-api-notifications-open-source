<?php

namespace Lkn\HookNotification\Core\Platforms\EvolutionApi\Infrastructure;

use Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse;
use Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;

final class EvolutionApiClient extends BaseApiClient
{
    /**
     * @param  string $baseApiUrl
     * @param  string $apiKey
     */
    public function __construct(
        private readonly ?string $baseApiUrl,
        private readonly ?string $apiKey
    ) {
    }

    /**
     * Performs a request to WhatsApp API.
     *
     * @param string $method
     * @param string $endpoint
     * @param array  $body
     * @param array  $headers
     *
     * @return ApiResponse
     */
    final public function request(
        string $method,
        string $endpoint = '',
        array $queryParams = [],
        array $body = [],
        array $headers = []
    ): ApiResponse {
        $baseUrl = $this->baseApiUrl;

        $headers = array_merge($headers, [
            'Content-Type: application/json',
            "apiKey: {$this->apiKey}",
        ]);

        return $this->httpRequest(
            $method,
            $baseUrl,
            $endpoint,
            $headers,
            $body,
            $queryParams,
        );
    }

    /**
     * @see https://doc.evolution-api.com/v2/api-reference/instance-controller/logout-instance
     *
     * @param string $instanceName
     *
     * @return ApiResponse
     */
    public function sendTextMessage(string $instanceName, string $text, string $toPhoneNumber)
    {
        $toPhoneNumber = str_replace('+', '', $toPhoneNumber);

        $apiResponse = $this->request(
            'POST',
            "message/sendText/{$instanceName}",
            [],
            [
                'text' => $text,
                'number' => $toPhoneNumber,
            ]
        );

        lkn_hn_log(
            Platforms::WP_EVO->value . ': send text message',
            [
                'instanceName' => $instanceName,
                'text' => $text,
                'toPhoneNumber' => $toPhoneNumber,
            ],
            $apiResponse,
            [
                $apiResponse->body['message']['conversation'],
                $toPhoneNumber,
            ]
        );

        return $apiResponse;
    }

    /**
     * @see https://doc.evolution-api.com/v2/api-reference/get-information
     *
     * @return ApiResponse
     */
    public function getInformation()
    {
        $apiResponse = $this->request('GET');

        lkn_hn_log(
            Platforms::WP_EVO->value . ': getInformation',
            [],
            $apiResponse,
        );

        return $apiResponse;
    }

    /**
     * @see https://doc.evolution-api.com/v2/api-reference/instance-controller/create-instance-basic
     *
     * @param string $instanceName
     *
     * @return ApiResponse
     */
    public function createInstance(string $instanceName)
    {
        $apiResponse = $this->request(
            'POST',
            'instance/create',
            [],
            [
                'instanceName' => $instanceName,
                'integration' => 'WHATSAPP-BAILEYS',
            ]
        );

        lkn_hn_log(
            Platforms::WP_EVO->value . ': createInstance',
            ['instanceName' => $instanceName],
            $apiResponse,
        );

        return $apiResponse;
    }

    /**
     * @see https://doc.evolution-api.com/v2/api-reference/instance-controller/fetch-instances
     *
     * @param string $instanceName
     *
     * @return ApiResponse
     */
    public function getInstance(string $instanceName)
    {
        $apiResponse = $this->request(
            'GET',
            'instance/fetchInstances',
            ['instanceName' => $instanceName]
        );

        lkn_hn_log(
            Platforms::WP_EVO->value . ': getInstance',
            ['instanceName' => $instanceName],
            $apiResponse,
        );

        return $apiResponse;
    }

    /**
     * @see https://doc.evolution-api.com/v2/api-reference/instance-controller/instance-connect
     *
     * @param string $instanceName
     *
     * @return ApiResponse
     */
    public function getWhatsAppQrCodeForInstance(string $instanceName)
    {
        $apiResponse = $this->request(
            'GET',
            "instance/connect/$instanceName",
            ['instanceName' => $instanceName]
        );

        lkn_hn_log(
            Platforms::WP_EVO->value . ': getWhatsAppQrCodeForInstance',
            ['instanceName' => $instanceName],
            $apiResponse,
        );

        return $apiResponse;
    }

    /**
     * @see https://doc.evolution-api.com/v2/api-reference/instance-controller/delete-instance
     *
     * @param string $instanceName
     *
     * @return ApiResponse
     */
    public function deleteInstance(string $instanceName)
    {
        $apiResponse = $this->request(
            'GET',
            'instance/delete',
            ['instanceName' => $instanceName]
        );

        lkn_hn_log(
            Platforms::WP_EVO->value . ': deleteInstance',
            ['instanceName' => $instanceName],
            $apiResponse,
        );

        return $apiResponse;
    }

    /**
     * @see https://doc.evolution-api.com/v2/api-reference/instance-controller/logout-instance
     *
     * @param string $instanceName
     *
     * @return ApiResponse
     */
    public function disconnectInstanceFromWp(string $instanceName)
    {
        $apiResponse = $this->request(
            'DELETE',
            "instance/logout/$instanceName"
        );

        lkn_hn_log(
            Platforms::WP_EVO->value . ': disconnectInstanceFromWp',
            ['instanceName' => $instanceName],
            $apiResponse,
        );

        return $apiResponse;
    }
}
