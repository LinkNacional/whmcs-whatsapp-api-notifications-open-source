<?php

namespace Lkn\HookNotification\Core\Platforms\Baileys;

use Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse;
use Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;

final class BaileysApiClient extends BaseApiClient
{
    /**
     * @param  string $baseApiUrl
     * @param  string $apiKey
     */
    public function __construct(
        private readonly string $baseApiUrl,
        private readonly string $apiKey
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

        if (str_ends_with($this->baseApiUrl, 'ngrok-free.app')) {
            $headers[] = 'ngrok-skip-browser-warning: 1';
        }

        $headers = array_merge($headers, [
            'Content-Type: application/json',
            "API-Key: {$this->apiKey}",
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

    public function sendTextMessage(string $toPhoneNumber, string $text)
    {
        $toPhoneNumber = str_replace('+', '', $toPhoneNumber);

        $apiResponse =$this->request(
            'POST',
            'lkn-notif/send-msg',
            body: ['number' => $toPhoneNumber, 'message' => $text]
        );

        lkn_hn_log(
            Platforms::BAILEYS->value . ': send text message',
            ['text' => $text, 'toPhoneNumber' => $toPhoneNumber],
            $apiResponse,
            [$toPhoneNumber]
        );

        return $apiResponse;
    }
}
