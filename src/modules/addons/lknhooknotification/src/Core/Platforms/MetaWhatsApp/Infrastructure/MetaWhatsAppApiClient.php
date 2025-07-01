<?php

namespace Lkn\HookNotification\Core\Platforms\MetaWhatsApp\Infrastructure;

use Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse;
use Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;

/**
 * Holds methods that used to comunicate with the WhatsApp business API and
 * cloud API.
 */
final class MetaWhatsAppApiClient extends BaseApiClient
{
    public function __construct(
        private readonly ?string $apiVersion,
        private readonly ?string $phoneNumberId,
        private readonly ?string $userAccessToken,
        private readonly ?string $businessAccountId,
    ) {
    }

    public function areSettingsFilled()
    {
        return $this->apiVersion && $this->phoneNumberId && $this->userAccessToken && $this->businessAccountId;
    }

    /**
     * Performs a request to WhatsApp API.
     *
     * @since 3.0.0
     *
     * @param string $method
     * @param string $endpoint
     * @param array  $body
     * @param array  $headers
     * @param array  $queryParams
     *
     * @return ApiResponse raw WhatsApp response converted to array or an empty array on failure.
     */
    final public function request(
        string $method,
        string $endpoint,
        array $body = [],
        array $headers = [],
        array $queryParams = []
    ): ApiResponse {
        $baseUrl = 'https://graph.facebook.com/' . $this->apiVersion;
        $headers = array_merge(
            $headers,
            [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->userAccessToken,
            ]
        );

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
     * @since 3.0.0
     *
     * @param string $method
     * @param string $endpoint
     * @param array  $body
     * @param array  $headers
     * @param array  $queryParams
     *
     * @link https://developers.facebook.com/docs/whatsapp/cloud-api/get-started
     *
     * @return ApiResponse
     */
    final public function apiCloud(
        string $method,
        string $endpoint,
        array $body = [],
        array $headers = [],
        array $queryParams = [],
    ): ApiResponse {
        $endpoint = "{$this->phoneNumberId}/$endpoint";

        return $this->request(
            $method,
            $endpoint,
            $body,
            $headers,
            $queryParams,
        );
    }

    /**
     * @since 3.0.0
     *
     * @param string $method
     * @param string $endpoint
     * @param array  $body
     * @param array  $headers
     * @param array  $queryParams
     *
     * @link https://developers.facebook.com/docs/whatsapp/business-management-api
     *
     * @return ApiResponse
     */
    final public function apiBusiness(
        string $method,
        string $endpoint,
        array $body = [],
        array $headers = [],
        array $queryParams = [],
    ): ApiResponse {
        $endpoint = $this->businessAccountId . '/' . $endpoint;

        return $this->request(
            $method,
            $endpoint,
            $body,
            $headers,
            $queryParams,
        );
    }

    /**
     * @see https://developers.facebook.com/docs/marketing-api/reference/business
     *
     * @return ApiResponse
     */
    public function getPhoneNumberStatus(): ApiResponse
    {
        $response = $this->apiBusiness('GET', '');

        lkn_hn_log(
            Platforms::WHATSAPP->value . ': getPhoneNumberStatus',
            [],
            $response,
        );

        return $response;
    }

    /**
     * @since 3.0.0
     *
     * @param array $params
     *
     * @link https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates/#retrieve-templates
     *
     * @return ApiResponse
     */
    public function getMessageTemplates(array $params = []): ApiResponse
    {
        $response = $this->apiBusiness(
            'GET',
            'message_templates',
            [],
            [],
            $params
        );

        lkn_hn_log(
            Platforms::WHATSAPP->value . ': getMessageTemplates',
            ['params' => $params],
            $response,
        );

        return $response;
    }

    /**
     * @see https://developers.facebook.com/docs/whatsapp/cloud-api/guides/send-message-templates/#text-based
     *
     * @param  string $toPhoneNumber
     * @param  string $msgTemplateName
     * @param  array  $msgTemplateComponents
     * @param  string $msgTemplateLangCode
     *
     * @return ApiResponse
     */
    public function sendMessageTemplate(
        string $toPhoneNumber,
        string $msgTemplateName,
        array $msgTemplateComponents,
        string $msgTemplateLangCode
    ): ApiResponse {
        $requestBody = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $toPhoneNumber,
            'type' => 'template',
            'template' => [
                'name' => $msgTemplateName,
                'language' => ['code' => $msgTemplateLangCode],
                'components' => $msgTemplateComponents,
            ],
        ];

        $apiResponse = $this->apiCloud('POST', 'messages', $requestBody);

        lkn_hn_log(
            Platforms::WHATSAPP->value . ': sendMessageTemplate',
            [
                'toPhoneNumber' => $toPhoneNumber,
                'msgTemplateName' => $msgTemplateName,
                'msgTemplateComponents' => $msgTemplateComponents,
                'msgTemplateLangCode' => $msgTemplateLangCode,
                'requestBody' => $requestBody,
            ],
            $apiResponse,
        );

        return $apiResponse;
    }
}
