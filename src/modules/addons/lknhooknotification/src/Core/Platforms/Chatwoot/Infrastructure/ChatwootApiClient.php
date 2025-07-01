<?php

namespace Lkn\HookNotification\Core\Platforms\Chatwoot\Infrastructure;

use Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse;
use Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;
use Lkn\HookNotification\Core\Shared\Infrastructure\Result;

/**
 * Implements raw methods for communicating with the API.
 *
 * Must only contain http requests to the API. Should not process the responses.
 *
 * @link https://www.chatwoot.com/developers/api/
 */
final class ChatwootApiClient extends BaseApiClient
{
    public function __construct(
        private readonly ?string $accountId,
        private readonly ?string $chatwootUrl,
        private readonly ?string $apiAccessToken,
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
        array $body = [],
        array $headers = [],
        array $queryParams = [],
    ): ApiResponse {
        $baseUrl = "{$this->chatwootUrl}/api/v1/accounts/{$this->accountId}";
        $headers = array_merge($headers, [
            "api_access_token: $this->apiAccessToken",
            'Content-Type: application/json',
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
     * @param integer $conversationId
     * @param string  $content
     * @param string  $contentType
     * @param string  $msgType        outgoing or incoming
     * @param boolean $private
     * @param array   $contentAttrs
     *
     * @link https://www.chatwoot.com/developers/api/#tag/Messages/operation/create-a-new-message-in-a-conversation
     *
     * @return ApiResponse returns the message ID.
     */
    final public function sendMessageToConversation(
        int $conversationId,
        string $content,
        string $contentType = 'text',
        string $msgType = 'outgoing',
        bool $private = false,
        array $contentAttrs = []
    ): ApiResponse {
        $body = [
            'content' => $content,
            'content_type' => $contentType,
            'private' => $private,
            'message_type' => $msgType,
        ];

        if (count($contentAttrs) > 0) {
            $body['content_attributes'] = $contentAttrs;
        }

        $response = $this->request(
            'POST',
            "conversations/$conversationId/messages",
            $body
        );

        lkn_hn_log(
            Platforms::CHATWOOT->value . ': send message to conversation',
            [
                'conversationId' => $conversationId,
                'content' => $content,
                'contentType' => $contentType,
                'msgType' => $msgType,
                'private' => $private ,
                'contentAttrs' => $contentAttrs,
            ],
            $response,
            [$content]
        );

        $response->setOperationResult(isset($response->body['id']));

        return $response;
    }

    /**
     * @link https://www.chatwoot.com/developers/api/#tag/Contacts/operation/contactSearch
     *
     * @param string $searchQuery
     *
     * @return ApiResponse
     */
    final public function searchContact(string $searchQuery): ApiResponse
    {
        $response = $this->request('GET', "contacts/search?q=$searchQuery");

        $response->setOperationResult($response->body['meta']['count'] > 0);

        lkn_hn_log(
            Platforms::CHATWOOT->value . ': search for contact',
            [
                'searchQuery' => $searchQuery,
            ],
            $response,
        );

        return $response;
    }

    /**
     * @link https://www.chatwoot.com/developers/api/#tag/Contacts/operation/contactConversations
     *
     * @param integer $contactId
     *
     * @return ApiResponse
     */
    final public function getContactConversations(int $contactId): ApiResponse
    {
        $response = $this->request('GET', "contacts/$contactId/conversations");

        $response->setOperationResult(
            isset($response->body['payload'])
        );

        lkn_hn_log(
            Platforms::CHATWOOT->value . ': get contact conversations',
            [
                'contactId' => $contactId,
            ],
            $response,
        );

        return $response;
    }

    /**
     * @param integer     $contactId
     * @param string|null $contactSourceId
     * @param integer     $inboxId
     * @param string      $status
     *
     * @see https://www.chatwoot.com/developers/api/#tag/Conversations/operation/newConversation
     *
     * @return ApiResponse
     */
    final public function createConversation(
        int $contactId,
        string|null $contactSourceId,
        int $inboxId,
        string $status = 'open'
    ): ApiResponse {
        $response = $this->request(
            'POST',
            'conversations',
            [
                'contact_id' => $contactId,
                'source_id' => $contactSourceId,
                'inbox_id' => $inboxId,
                'status' => $status,
            ]
        );

        $response->setOperationResult(isset($response->body['id']));

        lkn_hn_log(
            Platforms::CHATWOOT->value . ': create conversation',
            [
                'contactId' => $contactId,
                'contactSourceId' => $contactSourceId,
                'inboxId' => $inboxId,
                'status' => $status,
            ],
            $response,
        );

        return $response;
    }

    /**
     * @link https://www.chatwoot.com/developers/api/#tag/Contacts/operation/contactCreate
     *
     * @param integer $inboxId
     * @param string  $name
     * @param string  $email
     * @param string  $phone
     *
     * @return ApiResponse
     */
    final public function createContact(
        int $inboxId,
        string $name = '',
        string $email = '',
        string $phone = ''
    ): ApiResponse {
        $response = $this->request(
            'POST',
            'contacts',
            [
                'inbox_id' => $inboxId,
                'name' => $name,
                'email' => $email,
                'phone_number' => $phone,
            ]
        );

        $response->setOperationResult(isset($response->body['payload']['contact']['id']));

        lkn_hn_log(
            Platforms::CHATWOOT->value . ': create contact',
            [
                'inboxId' => $inboxId,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
            ],
            $response,
        );

        return $response;
    }

    // ------------------------------- PIRULITO -------------------------------

    /**
     * @param string $searchQuery
     *
     * @return ApiResponse
     */
    public function searchContactAndGetItsIdAndItsInboxesSourceId(
        string $searchQuery
    ): ApiResponse|Result {
        $response = $this->searchContact($searchQuery);

        if (!$response->operationResult) {
            return $response;
        }

        $contactId      = $response->body['payload'][0]['id'];
        $contactInboxes = $response->body['payload'][0]['contact_inboxes'];

        $contactInboxesSourceIds = $this->getSourceIdsByInboxIds($contactInboxes);

        lkn_hn_log(
            Platforms::CHATWOOT->value . ': searchContactAndGetItsIdAndItsInboxesSourceId',
            ['searchQuery' => $searchQuery],
            $response,
        );

        if (count($contactInboxesSourceIds) === 0) {
            return new Result(
                operationResult: false,
                code: 'unable-to-match-source-id-with-inbox-id'
            );
        }

        return new Result(
            operationResult: true,
            data: [
                'contact' => [
                    'id' => $contactId,
                    'inboxesSourcesIds' => $contactInboxesSourceIds,
                ],
            ]
        );
    }

    /**
     * @param array $contactInboxes must be equal to the contact_inboxes
     *                              returned by the API.
     *
     * @return array [{inbox_id} => {source_id}, ...]
     */
    public function getSourceIdsByInboxIds(array $contactInboxes): array
    {
        $sourceIdByInboxId = [];

        foreach ($contactInboxes as $inbox) {
            $sourceIdByInboxId[$inbox['inbox']['id']] = $inbox['source_id'];
        }

        lkn_hn_log(
            Platforms::CHATWOOT->value . ': getSourceIdsByInboxIds',
            ['contactInboxes' => $contactInboxes],
            $sourceIdByInboxId,
        );

        return $sourceIdByInboxId;
    }

    /**
     * Search for an open conversation for the inbox ID of WhatsApp.
     *
     * @param integer $contactId
     * @param integer $inboxId
     *
     * @return ApiResponse|Result
     */
    final public function searchForContactOpenConversationByInboxId(
        int $contactId,
        int $inboxId
    ): ApiResponse|Result {
        $response = $this->getContactConversations($contactId);

        lkn_hn_log(
            Platforms::CHATWOOT->value . ': searchForContactOpenConversationByInboxId',
            ['contactId' => $contactId, 'inboxId' => $inboxId],
            $response,
        );

        if ($response->operationResult === false) {
            return $response;
        }

        // Get an open conversation with the inbox id of WhatsApp.
        $conversation = current(array_filter(
            $response['apiResponse']['payload'],
            function ($conversation) use ($inboxId): bool {
                return $conversation['status'] === 'open' &&
                $conversation['inbox_id'] === $inboxId;
            }
        ));

        if ($conversation === false) {
            return new Result(
                operationResult: false,
                code: 'unable-to-find-conversation'
            );
        }

        return new Result(
            operationResult: true,
            data: ['id' => $conversation['id']]
        );
    }

    /**
     * @param integer $contactId
     * @param integer $inboxId
     *
     * @return ApiResponse|Result
     */
    final public function getContactLastConversation(
        int $contactId,
        int $inboxId
    ): ApiResponse|Result {
        $response = $this->getContactConversations($contactId);

        lkn_hn_log(
            Platforms::CHATWOOT->value . ': getContactLastConversation',
            ['contactId' => $contactId, 'inboxId' => $inboxId],
            $response,
        );

        if ($response->operationResult === false) {
            return $response;
        }

        $conversationsForInbox = array_filter(
            $response->body['payload'],
            function ($conversation) use ($inboxId): bool {
                return $conversation['inbox_id'] === $inboxId;
            }
        );

        if (count($conversationsForInbox) === 0) {
            return new Result(
                operationResult: false,
                code: 'unable-to-find-conversation-for-inbox',
            );
        }

        return new Result(
            operationResult: true,
            data: ['lastConversation' => current($conversationsForInbox)]
        );
    }

    /**
     * @param array{
     *  attribute_display_name: string,
     *  attribute_display_type: int,
     *  attribute_key: string,
     *  attribute_model: int,
     * }[] $attrs
     *
     * @see https://www.chatwoot.com/developers/api/#tag/Custom-Attributes/operation/get-account-custom-attribute
     *
     * @return Result
     */
    public function createCustomAttribute(
        array $attrs,
    ): Result {
        $reqBody   = [];
        $responses = [];

        foreach ($attrs as $attr) {
            $responses[] = $this->request('POST', 'custom_attribute_definitions', $attr,);
        }

        lkn_hn_log(
            Platforms::CHATWOOT->value . ': createCustomAttribute',
            ['attrs' => $attrs],
            $responses,
        );

        return lkn_hn_result('responses', $responses);
    }
}
