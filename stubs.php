<?php

namespace Lkn\HookNotification\Core\Shared\Infrastructure {
    abstract class BaseApiClient
    {
        protected function httpRequest(string $method, string $baseUrl, string $endpoint, array $headers = [], array $body = [], array $queryParams = []): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\Baileys {
    final class BaileysApiClient extends \Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient
    {
        /**
         * @param  string $baseApiUrl
         * @param  string $apiKey
         */
        public function __construct(private readonly string $baseApiUrl, private readonly string $apiKey) {}
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
        final public function request(string $method, string $endpoint = '', array $queryParams = [], array $body = [], array $headers = []): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse {}
        public function sendTextMessage(string $toPhoneNumber, string $text) {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\Common {
    abstract class AbstractPlatform
    {
        public function __construct(public readonly \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatformSettings $platformSettings, public readonly \Lkn\HookNotification\Core\Notification\Domain\AbstractNotificationParser $notificationParser, protected readonly \Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient $apiClient) {}
        abstract public function sendNotification(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification, \Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate $template): \Lkn\HookNotification\Core\Platforms\Common\PlatformNotificationSendResult;
        protected function getPhoneNumber(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification): false|int {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\Baileys\Domain {
    final class BaileysPlatform extends \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatform
    {
        /**
         * @var BaileysSettings
         */
        public readonly \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatformSettings $platformSettings;
        public readonly \Lkn\HookNotification\Core\Notification\Domain\AbstractNotificationParser $notificationParser;
        public function sendNotification(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification, \Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate $template): \Lkn\HookNotification\Core\Platforms\Common\PlatformNotificationSendResult {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\Common {
    abstract class AbstractPlatformSettings
    {
        public function __construct(public ?int $wpCustomFieldId = null) {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\Baileys\Domain {
    class BaileysSettings extends \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatformSettings
    {
        public function __construct(public readonly string $enabled, public readonly string $apiToken, public ?int $wpCustomFieldId, public readonly string $endpoint) {}
    }
}

namespace Lkn\HookNotification\Core\Shared\Infrastructure\Interfaces {
    class BaseController
    {
        protected string $viewsBasePath;
        public \Lkn\HookNotification\Core\Shared\Infrastructure\View\View $view;
        public function __construct(\Lkn\HookNotification\Core\Shared\Infrastructure\View\View $view) {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\Chatwoot\Http\Controllers {
    final class ChatwootSettingsController extends \Lkn\HookNotification\Core\Shared\Infrastructure\Interfaces\BaseController
    {
        public readonly \Lkn\HookNotification\Core\Platforms\Chatwoot\Infrastructure\ChatwootApiClient $chatwootApiClient;
        public function __construct() {}
        public function handle(array $request): void {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\Chatwoot\Application {
    final class ChatwootNotificationListenerService
    {
        public function __construct(private \Lkn\HookNotification\Core\Platforms\Chatwoot\Domain\ChatwootPlatform $chatwootPlatform) {}
        public function run(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification) {}
    }
    final class LiveChatService
    {
        /**
         * @param  array<string, mixed> $whmcsHookParams
         */
        public function __construct(array $whmcsHookParams) {}
        public function handle(): string {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\Chatwoot\Infrastructure {
    /**
     * Implements raw methods for communicating with the API.
     *
     * Must only contain http requests to the API. Should not process the responses.
     *
     * @link https://www.chatwoot.com/developers/api/
     */
    final class ChatwootApiClient extends \Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient
    {
        public function __construct(private readonly ?string $accountId, private readonly ?string $chatwootUrl, private readonly ?string $apiAccessToken) {}
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
        final public function request(string $method, string $endpoint = '', array $body = [], array $headers = [], array $queryParams = []): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse {}
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
        final public function sendMessageToConversation(int $conversationId, string $content, string $contentType = 'text', string $msgType = 'outgoing', bool $private = false, array $contentAttrs = []): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse {}
        /**
         * @link https://www.chatwoot.com/developers/api/#tag/Contacts/operation/contactSearch
         *
         * @param string $searchQuery
         *
         * @return ApiResponse
         */
        final public function searchContact(string $searchQuery): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse {}
        /**
         * @link https://www.chatwoot.com/developers/api/#tag/Contacts/operation/contactConversations
         *
         * @param integer $contactId
         *
         * @return ApiResponse
         */
        final public function getContactConversations(int $contactId): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse {}
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
        final public function createConversation(int $contactId, string|null $contactSourceId, int $inboxId, string $status = 'open'): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse {}
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
        final public function createContact(int $inboxId, string $name = '', string $email = '', string $phone = ''): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse {}
        // ------------------------------- PIRULITO -------------------------------
        /**
         * @param string $searchQuery
         *
         * @return ApiResponse
         */
        public function searchContactAndGetItsIdAndItsInboxesSourceId(string $searchQuery): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse|\Lkn\HookNotification\Core\Shared\Infrastructure\Result {}
        /**
         * @param array $contactInboxes must be equal to the contact_inboxes
         *                              returned by the API.
         *
         * @return array [{inbox_id} => {source_id}, ...]
         */
        public function getSourceIdsByInboxIds(array $contactInboxes): array {}
        /**
         * Search for an open conversation for the inbox ID of WhatsApp.
         *
         * @param integer $contactId
         * @param integer $inboxId
         *
         * @return ApiResponse|Result
         */
        final public function searchForContactOpenConversationByInboxId(int $contactId, int $inboxId): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse|\Lkn\HookNotification\Core\Shared\Infrastructure\Result {}
        /**
         * @param integer $contactId
         * @param integer $inboxId
         *
         * @return ApiResponse|Result
         */
        final public function getContactLastConversation(int $contactId, int $inboxId): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse|\Lkn\HookNotification\Core\Shared\Infrastructure\Result {}
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
        public function createCustomAttribute(array $attrs): \Lkn\HookNotification\Core\Shared\Infrastructure\Result {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\Chatwoot\Domain {
    class LiveChatSettings extends \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatformSettings
    {
        public function __construct(public ?bool $enableLiveChat, public ?string $clientIdentifierKey, public ?string $userIdentityValidation, public ?string $liveChatScript, public ?array $clientStatsToSend, public ?array $customFieldsToSend, public ?array $liveChatModuleAttrsToSend) {}
    }
    class ChatwootSettings extends \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatformSettings
    {
        public function __construct(
            public ?bool $enabled,
            public ?string $url,
            public ?string $apiAccessToken,
            public ?string $wpInboxId,
            public ?string $fbInboxId,
            public ?string $listenToWhatsAppPlatformMode,
            public ?bool $listenSendAsPrivateNote,
            public ?int $wpCustomFieldId,
            // Live Chat
            public ?\Lkn\HookNotification\Core\Platforms\Chatwoot\Domain\LiveChatSettings $liveChatSettings,
            // Module Settings
            public ?\Lkn\HookNotification\Core\Platforms\Module\Domain\ModuleSettings $moduleSettings,
            public ?int $accountId = null
        ) {}
    }
    final class ChatwootPlatform extends \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatform
    {
        /**
         * @var ChatwootSettings
         */
        public readonly \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatformSettings $platformSettings;
        public readonly \Lkn\HookNotification\Core\Notification\Domain\AbstractNotificationParser $notificationParser;
        public function sendNotification(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification, \Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate $template): \Lkn\HookNotification\Core\Platforms\Common\PlatformNotificationSendResult {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\EvolutionApi\Http\Controllers {
    final class EvolutionApiSettingsController extends \Lkn\HookNotification\Core\Shared\Infrastructure\Interfaces\BaseController
    {
        public function __construct() {}
        public function handle(array $request): string {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\EvolutionApi\Application {
    final class EvolutionApiSetupService
    {
        public function __construct() {}
        public function setup() {}
        public function disconnectInstance(): \Lkn\HookNotification\Core\Shared\Infrastructure\Result {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\EvolutionApi\Infrastructure {
    final class EvolutionApiClient extends \Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient
    {
        /**
         * @param  string $baseApiUrl
         * @param  string $apiKey
         */
        public function __construct(private readonly ?string $baseApiUrl, private readonly ?string $apiKey) {}
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
        final public function request(string $method, string $endpoint = '', array $queryParams = [], array $body = [], array $headers = []): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse {}
        /**
         * @see https://doc.evolution-api.com/v2/api-reference/instance-controller/logout-instance
         *
         * @param string $instanceName
         *
         * @return ApiResponse
         */
        public function sendTextMessage(string $instanceName, string $text, string $toPhoneNumber) {}
        /**
         * @see https://doc.evolution-api.com/v2/api-reference/get-information
         *
         * @return ApiResponse
         */
        public function getInformation() {}
        /**
         * @see https://doc.evolution-api.com/v2/api-reference/instance-controller/create-instance-basic
         *
         * @param string $instanceName
         *
         * @return ApiResponse
         */
        public function createInstance(string $instanceName) {}
        /**
         * @see https://doc.evolution-api.com/v2/api-reference/instance-controller/fetch-instances
         *
         * @param string $instanceName
         *
         * @return ApiResponse
         */
        public function getInstance(string $instanceName) {}
        /**
         * @see https://doc.evolution-api.com/v2/api-reference/instance-controller/instance-connect
         *
         * @param string $instanceName
         *
         * @return ApiResponse
         */
        public function getWhatsAppQrCodeForInstance(string $instanceName) {}
        /**
         * @see https://doc.evolution-api.com/v2/api-reference/instance-controller/delete-instance
         *
         * @param string $instanceName
         *
         * @return ApiResponse
         */
        public function deleteInstance(string $instanceName) {}
        /**
         * @see https://doc.evolution-api.com/v2/api-reference/instance-controller/logout-instance
         *
         * @param string $instanceName
         *
         * @return ApiResponse
         */
        public function disconnectInstanceFromWp(string $instanceName) {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\EvolutionApi\Domain {
    class EvolutionApiSettings extends \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatformSettings
    {
        public function __construct(public bool $enabled, public string $apiUrl, public string $apiKey, public string $instanceName, public ?int $wpCustomFieldId = null) {}
    }
    final class EvolutionApiPlatform extends \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatform
    {
        /**
         * @var EvolutionApiSettings
         */
        public readonly \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatformSettings $platformSettings;
        public readonly \Lkn\HookNotification\Core\Notification\Domain\AbstractNotificationParser $notificationParser;
        public function sendNotification(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification, \Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate $template): \Lkn\HookNotification\Core\Platforms\Common\PlatformNotificationSendResult {}
    }
}

namespace Lkn\HookNotification\Core\Notification\Domain {
    abstract class AbstractNotificationParser
    {
        public function __construct(public null|\Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient $baseApiClient = null) {}
        abstract public function parse(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification, \Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate $template, ?\Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient $apiClient = null): array|\Lkn\HookNotification\Core\Shared\Infrastructure\Result;
    }
}

namespace Lkn\HookNotification\Core\Platforms\EvolutionApi\Domain {
    /**
     * This should return the platform-api-specific paylod based om
     *  NotificationTemplate->platformPayload.
     */
    final class EvolutionApiNotificationParser extends \Lkn\HookNotification\Core\Notification\Domain\AbstractNotificationParser
    {
        public function parse(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification, \Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate $template, ?\Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient $apiClient = null): array|\Lkn\HookNotification\Core\Shared\Infrastructure\Result {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\Common {
    interface PlatformNotificationTemplatePayload
    {
        public function fromArray(array $data): static;
        public function toArray(): array;
    }
    abstract class BasePlatformApiClient extends \Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient
    {
        abstract protected function sendNotification(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification, \Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate $template);
    }
    final class NotificationParser extends \Lkn\HookNotification\Core\Platforms\Common\AbstractNotificationParser {}
    final class PlatformController
    {
        public function updatePlatformSettings(array $request): array {}
        public function displayPlatformSettings(): array {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\Common\Application {
    final class PlatformService
    {
        /**
         * @return Platforms[]
         */
        public function getEnabledPlatforms(bool $standardOnly = false): array {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\Common\Infrastructure {
    final class PlatformFactory
    {
        public function __construct() {}
        public function make(\Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms $platform): \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatform {}
        public function makeSettings(\Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms $platform) {}
    }
    final class PlatformSettingsFactory
    {
        public static function makeBaileysSettings(array $raw): \Lkn\HookNotification\Core\Platforms\Baileys\Domain\BaileysSettings {}
        public static function makeEvolutionApiSettings(array $raw): \Lkn\HookNotification\Core\Platforms\EvolutionApi\Domain\EvolutionApiSettings {}
        public static function makeMetaWhatsAppSettings(): \Lkn\HookNotification\Core\Platforms\MetaWhatsApp\Domain\MetaWhatsAppSettings {}
        public static function makeLiveChatSettings(array $raw = []): \Lkn\HookNotification\Core\Platforms\Chatwoot\Domain\LiveChatSettings {}
        public static function makeChatwootSettings(array $raw, \Lkn\HookNotification\Core\Platforms\Chatwoot\Domain\LiveChatSettings $liveChatSettings, \Lkn\HookNotification\Core\Platforms\Module\Domain\ModuleSettings $moduleSettings): \Lkn\HookNotification\Core\Platforms\Chatwoot\Domain\ChatwootSettings {}
        public static function makeModuleSettings(array $raw): \Lkn\HookNotification\Core\Platforms\Module\Domain\ModuleSettings {}
    }
    final class PlatformApiClientFactory
    {
        public function makeBaileysClient(\Lkn\HookNotification\Core\Platforms\Baileys\Domain\BaileysSettings $settings): \Lkn\HookNotification\Core\Platforms\Baileys\BaileysApiClient {}
        public function makeEvolutionApiClient(\Lkn\HookNotification\Core\Platforms\EvolutionApi\Domain\EvolutionApiSettings $settings): \Lkn\HookNotification\Core\Platforms\EvolutionApi\Infrastructure\EvolutionApiClient {}
        public function makeMetaWhatsAppClient(\Lkn\HookNotification\Core\Platforms\MetaWhatsApp\Domain\MetaWhatsAppSettings $settings): \Lkn\HookNotification\Core\Platforms\MetaWhatsApp\Infrastructure\MetaWhatsAppApiClient {}
        public function makeChatwootClient(\Lkn\HookNotification\Core\Platforms\Chatwoot\Domain\ChatwootSettings $settings): \Lkn\HookNotification\Core\Platforms\Chatwoot\Infrastructure\ChatwootApiClient {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\Common {
    final class PlatformNotificationSendResult
    {
        /**
         * @param  NotificationReportStatus $status
         * @param  string|null              $msg
         * @param  string|null              $target This can be a phone number, WhatsApp phone number, email.
         */
        public function __construct(public \Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportStatus $status, public ?string $msg = null, public ?string $target = null) {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\MetaWhatsApp\Interface {
    final class MetaWhatsAppSettingsController
    {
        //
    }
}

namespace Lkn\HookNotification\Core\Platforms\MetaWhatsApp\Http\Controllers {
    final class MetaWhatsAppSettingsController extends \Lkn\HookNotification\Core\Shared\Infrastructure\Interfaces\BaseController
    {
        public function __construct() {}
        public function handle(array $request) {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\MetaWhatsApp\Application {
    final class MetaWhatsAppService
    {
        public function __construct() {}
        public function getMessageTemplatesForView(): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\MetaWhatsApp\Infrastructure {
    /**
     * Holds methods that used to comunicate with the WhatsApp business API and
     * cloud API.
     */
    final class MetaWhatsAppApiClient extends \Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient
    {
        public function __construct(private readonly ?string $apiVersion, private readonly ?string $phoneNumberId, private readonly ?string $userAccessToken, private readonly ?string $businessAccountId) {}
        public function areSettingsFilled() {}
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
        final public function request(string $method, string $endpoint, array $body = [], array $headers = [], array $queryParams = []): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse {}
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
        final public function apiCloud(string $method, string $endpoint, array $body = [], array $headers = [], array $queryParams = []): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse {}
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
        final public function apiBusiness(string $method, string $endpoint, array $body = [], array $headers = [], array $queryParams = []): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse {}
        /**
         * @see https://developers.facebook.com/docs/marketing-api/reference/business
         *
         * @return ApiResponse
         */
        public function getPhoneNumberStatus(): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse {}
        /**
         * @since 3.0.0
         *
         * @param array $params
         *
         * @link https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates/#retrieve-templates
         *
         * @return ApiResponse
         */
        public function getMessageTemplates(array $params = []): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse {}
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
        public function sendMessageTemplate(string $toPhoneNumber, string $msgTemplateName, array $msgTemplateComponents, string $msgTemplateLangCode): \Lkn\HookNotification\Core\Shared\Infrastructure\ApiResponse {}
    }
}

namespace Lkn\HookNotification\Core\Platforms\MetaWhatsApp\Domain {
    /**
     * This should return the platform-api-specific paylod based om
     * NotificationTemplate->platformPayload.
     *
     * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#components-object
     */
    final class MetaWhatsAppNotificationParser extends \Lkn\HookNotification\Core\Notification\Domain\AbstractNotificationParser
    {
        public function parse(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification, \Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate $template, ?\Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient $apiClient = null): array|\Lkn\HookNotification\Core\Shared\Infrastructure\Result {}
    }
    class MetaWhatsAppSettings extends \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatformSettings
    {
        public function __construct(public readonly ?bool $enabled, public readonly ?string $userAccessToken, public readonly ?string $businessAccountId, public readonly ?string $phoneNumberId, public readonly ?string $apiVersion, public ?int $wpCustomFieldId, public ?bool $showInvoiceReminderBtn, public ?int $wpCustomFieldIdForTicket, public ?string $defaultMsgTemplateLang) {}
    }
    final class MetaWhatsAppPlatform extends \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatform
    {
        /**
         * @var MetaWhatsAppSettings
         */
        public readonly \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatformSettings $platformSettings;
        /**
         * @var MetaWhatsAppNotificationParser
         */
        public readonly \Lkn\HookNotification\Core\Notification\Domain\AbstractNotificationParser $notificationParser;
        public function sendNotification(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification, \Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate $template): \Lkn\HookNotification\Core\Platforms\Common\PlatformNotificationSendResult {}
    }
}

namespace Lkn\HookNotification\Core\Notification\Http\Controllers {
    final class NotificationController extends \Lkn\HookNotification\Core\Shared\Infrastructure\Interfaces\BaseController
    {
        public function __construct(\Lkn\HookNotification\Core\Shared\Infrastructure\View\View $view) {}
        public function viewNotification(string $notificationCode, string $editingLocale, array $request): string {}
        /**
         * @param  array<mixed> $request
         *
         * @return void
         */
        public function viewNotificationsTable(array $request): void {}
    }
}

namespace Lkn\HookNotification\Core\Shared\Infrastructure {
    /**
     * If you need to support several types of Singletons in your app, you can
     * define the basic features of the Singleton in a base class, while moving the
     * actual business logic (like logging) to subclasses.
     *
     * @see https://refactoring.guru/design-patterns/singleton/php/example#example-1
     */
    class Singleton
    {
        /**
         * Singleton's constructor should not be public. However, it can't be
         * private either if we want to allow subclassing.
         */
        protected function __construct() {}
        /**
         * Cloning and unserialization are not permitted for singletons.
         */
        protected function __clone() {}
        public function __wakeup() {}
        /**
         * The method you use to get the Singleton's instance.
         */
        public static function getInstance(): static {}
    }
}

namespace Lkn\HookNotification\Core\Notification\Application {
    /**
     * This class contains the logic for instantiating AbstractNotificaions from files
     * and from built_in_notifications_recipes.php.
     */
    final class NotificationFactory extends \Lkn\HookNotification\Core\Shared\Infrastructure\Singleton
    {
        public function makeByCode(string $notificationCode): ?\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification {}
        /**
         * @param  boolean $mergeEnabledTemplates
         *
         * @return AbstractNotification[]
         */
        public function makeAll(bool $mergeEnabledTemplates) {}
        /**
         * @param  Hooks $hook
         *
         * @return AbstractNotification[]
         */
        public function makeAllForHook(\Lkn\HookNotification\Core\Shared\Infrastructure\Hooks $hook, bool $onlyEnabled = false): array {}
        /**
         * @return AbstractNotification[]
         */
        public function makeEnabledNotifs(): array {}
    }
    final class NotificationPlatformResolver
    {
        public function __construct() {}
        public function resolve(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification) {}
    }
}

namespace Lkn\HookNotification\Core\Notification\Application\Services {
    final class NotificationService
    {
        public function __construct() {}
        public function handleUpdate(string $notificationCode, array $request): \Lkn\HookNotification\Core\Shared\Infrastructure\Result {}
        /**
         * This method is coupled to Core/Notification/Http/Views/template_editors/meta_wp_template_editor.tpl.
         *
         * @param array{
         *     header-parameter?: string,
         *     body-parameters: array<int, string>,
         *     button-parameters: array<int, string>,
         *     message-template-lang: string,
         *     message-template: string,
         *     header-format: string
         * } $request
         *
         * @return Result<array{
         *     template: string,
         *     platformPayload: array{
         *         msgTemplateLang: string,
         *         header?: array<int, array{key: int, value: string, type: 'TEXT'|'text'}>,
         *         body: array<int, array{key: int, value: string, type: 'text'}>,
         *         button?: array<int, array{
         *             index: int,
         *             type: 'url',
         *             params: array<int, array{key: int, value: string}>
         *         }>
         *     }
         * }>
         */
        public function handleWhatsAppPlatformPayloadForm(array $request): \Lkn\HookNotification\Core\Shared\Infrastructure\Result {}
        public function getNotificationsForView() {}
        public function buildNotification(string $notificationCode): ?\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification {}
        public function handleTemplateDelete(string $notificationCode, string $templateLocale): \Lkn\HookNotification\Core\Shared\Infrastructure\Result {}
        public function isNotificationEnabled(string $notificationCode): bool {}
    }
    final class NotificationSender extends \Lkn\HookNotification\Core\Shared\Infrastructure\Singleton
    {
        /**
         * @param  AbstractNotification $notification
         * @param  array<mixed>|null    $whmcsHookParams
         *
         * @return null|Result|PlatformNotificationSendResult
         */
        public function dispatchNotification(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification, ?array $whmcsHookParams): null|\Lkn\HookNotification\Core\Shared\Infrastructure\Result|\Lkn\HookNotification\Core\Platforms\Common\PlatformNotificationSendResult {}
        /**
         * @param  AbstractNotification $notification
         * @param  null|array<mixed>    $whmcsHookParams
         * @param null|integer         $queueId
         *
         * @return Result|PlatformNotificationSendResult
         */
        public function send(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification, ?array $whmcsHookParams, ?int $queueId = null): \Lkn\HookNotification\Core\Shared\Infrastructure\Result|\Lkn\HookNotification\Core\Platforms\Common\PlatformNotificationSendResult {}
    }
    final class NotificationViewService
    {
        public function __construct(\Lkn\HookNotification\Core\Shared\Infrastructure\View\View $view) {}
        public function findTemplateByLang(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification, string $lang): ?\Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate {}
        public function getTemplateEditorForPlatform(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification, ?\Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate $template, bool $disableTemplateEditorChanges = false): string {}
    }
}

namespace Lkn\HookNotification\Core\Notification\Infrastructure {
    final class ManualNotificationHookListener
    {
        public function __construct() {}
        public function listenFor(\Lkn\HookNotification\Core\Shared\Infrastructure\Hooks $hook): void {}
    }
}

namespace Lkn\HookNotification\Core\Notification\Infrastructure\NotificationTemplateRenderers {
    final class MetaWhatsAppTemplateRenderer
    {
        public function __construct(\Lkn\HookNotification\Core\Shared\Infrastructure\View\View $view) {}
        /**
         * @param  AbstractNotification      $notificationTemplate
         * @param  NotificationTemplate|null $template                     This has the platform_payload field so this class knows which parameter to select.
         * @param boolean                   $disableTemplateEditorChanges
         *
         * @return string
         */
        public function render(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notificationTemplate, ?\Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate $template, bool $disableTemplateEditorChanges = false): string {}
    }
}

namespace Lkn\HookNotification\Core\Notification\Infrastructure {
    /**
     * This class is responsible for receiving an array of AbstractNotifications and
     * listen to their $hook with add_hook.
     *
     * It should pass a closure for add_hook that should be responsible for:
     * 1. Using $notificationPlatformResolver for:
     *  - Identify the correct template for the client.
     *  - Identify the correct platform the the template.
     */
    final class NotificationHookListener
    {
        public function __construct() {}
        /**
         * @return void
         */
        public function listen(): void {}
    }
}

namespace Lkn\HookNotification\Core\Shared\Infrastructure\Repository {
    /**
     * This should be inherited by all infrastructure repositories.
     */
    abstract class BaseRepository
    {
        /**
         * Use this to mount queries to the database.
         *
         */
        public \WHMCS\Database\Capsule $query;
        public function __construct() {}
    }
}

namespace Lkn\HookNotification\Core\Notification\Infrastructure\Repositories {
    class NotificationRepository extends \Lkn\HookNotification\Core\Shared\Infrastructure\Repository\BaseRepository
    {
        public function upsertNotification(string $notiifcationCode, string $platform, string $locale, string $template, array $platformPayload) {}
        /**
         * @return array<string, array{
         *     lang: string,
         *     tpl: string,
         *     platform: string,
         *     platform_payload: array{
         *         header: array<int, array{
         *             key: string,
         *             value: string,
         *             type: string
         *         }>,
         *         body: array<int, array{
         *             key: string,
         *             value: string,
         *             type: string
         *         }>,
         *         button: array<int, array{
         *             index: string,
         *             type: string,
         *             params: array<int, array{
         *                 key: string,
         *                 value: string
         *             }>
         *         }>
         *     }
         * }>
         */
        public function getEnabledNotifications() {}
        public function createNotificationTemplate(string $notificationCode, string $platform, string $locale, string $template, ?array $platformPayload = null) {}
        // public function getEnabledNotifications()
        // {
        //     $standardNotifs = array_column(
        //         $this->query
        //             ->table('mod_lkn_hook_notification_localized_tpls')
        //             ->select('notif_code')
        //             ->distinct()
        //             ->get()
        //             ->toArray(),
        //         'notif_code'
        //     );
        //     $wpNotifs = array_column(
        //         lkn_hn_config(Settings::WP_MSG_TEMPLATE_ASSOCS),
        //         'notification'
        //     );
        //     return array_merge($standardNotifs, $wpNotifs);
        // }
        public function deleteNotificationTemplate(string $notificationCode, string $templateLocale): bool {}
    }
}

namespace Lkn\HookNotification\Core\Notification\Domain {
    interface NotificationObserverInterface
    {
        /**
         * Fired when a notification is sent.
         *
         * @param  AbstractNotification $notification
         * @param  NotificationTemplate $template
         * @param  AbstractPlatform     $platform
         * @return void
         */
        public function onNotificationSent(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification, \Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate $template, \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatform $platform): void;
    }
}

namespace Lkn\HookNotification\Core\Notification\Infrastructure\Observers {
    final class ChatwootNotificationObserver implements \Lkn\HookNotification\Core\Notification\Domain\NotificationObserverInterface
    {
        public function onNotificationSent(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification, \Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate $template, \Lkn\HookNotification\Core\Platforms\Common\AbstractPlatform $platform): void {}
    }
    final class NotificationObserverFactory extends \Lkn\HookNotification\Core\Shared\Infrastructure\Singleton
    {
        /**
         * @return array<\Lkn\HookNotification\Core\Notification\Domain\NotificationObserverInterface>
         */
        public static function make(): array {}
    }
}

namespace Lkn\HookNotification\Core\Notification\Domain {
    abstract class AbstractNotification
    {
        public array $whmcsHookParams;
        public \Lkn\HookNotification\Core\Notification\Domain\Client $client;
        public ?int $categoryId;
        /**
         * @var \Closure(): int
         */
        public $findClientId;
        protected int $clientId;
        /**
         * @var ?\Closure(): int
         */
        public $findCategoryId;
        /**
         * @var array<NotificationTemplate>
         */
        public array $templates;
        public int $priority;
        /**
         * @param  string                          $code           Must be unique.
         * @param  NotificationReportCategory      $category
         * @param  null|Hooks                      $hook
         * @param  NotificationParameterCollection $parameters
         * @param  \Closure                        $findClientId
         * @param  \Closure                        $findCategoryId
         */
        public function __construct(public readonly string $code, public readonly ?\Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory $category, public readonly ?\Lkn\HookNotification\Core\Shared\Infrastructure\Hooks $hook, public readonly \Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection $parameters, $findClientId, $findCategoryId = null, public readonly ?string $description = null) {}
        /**
         * IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT!
         *
         * This method should be called before sending the notification.
         *
         * It initializes properties that require the $whmcsHookParams.
         *
         * @param  null|array $whmcsHookParams
         *
         * @return void
         */
        public function finishInit(?array $whmcsHookParams) {}
        /**
         * @param  NotificationTemplate[] $templates
         *
         * @return void
         */
        public function setTemplates(array $templates) {}
        /**
         * Used for file-based notification to define custom logic for identifying if the notification
         * should run or not.
         *
         * @return boolean Default to true.
         */
        public function shouldRun(): bool {}
        public function fillTemplate(\Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate $template): string {}
    }
    /**
     * Used for notifications that runs on cron hooks.
     */
    abstract class AbstractCronNotification extends \Lkn\HookNotification\Core\Notification\Domain\AbstractNotification
    {
        /**
         * @return array<mixed>
         */
        abstract public function getPayload(): array;
    }
    /**
     * Used for creating notification instances based n built_in_notifications_recipes.php.
     */
    final class BuiltInNotification extends \Lkn\HookNotification\Core\Notification\Domain\AbstractNotification {}
    abstract class AbstractManualNotification extends \Lkn\HookNotification\Core\Notification\Domain\AbstractNotification
    {
        public readonly bool $isManual;
        /**
         * @param  string                          $code           Must be unique.
         * @param  NotificationReportCategory      $category
         * @param  null|Hooks|array                $hook
         * @param  NotificationParameterCollection $parameters
         * @param  callable                        $findClientId
         * @param  callable                        $findCategoryId
         */
        public function __construct(string $code, \Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory $category, null|\Lkn\HookNotification\Core\Shared\Infrastructure\Hooks|array $hook, \Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection $parameters, $findClientId, $findCategoryId) {}
    }
    class NotificationParameter
    {
        /**
         * @param string  $code
         * @param string  $label
         * @param Closure $valueGetter
         */
        public function __construct(public string $code, public string $label, public \Closure $valueGetter) {}
    }
    final class NotificationTemplate
    {
        /**
         * @param  Platforms $platform
         * @param  string    $lang
         * @param  string    $template
         * @param  array     $platformPayload Use this to save platform-specific
         *                                    parameters.
         */
        public function __construct(public ?\Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms $platform, public ?string $lang, public string $template, public array $platformPayload = []) {}
        public function getUsedParameterCodes(null|string $template = null) {}
        // Should move this to MetaWhatsAppNotificationTemplate
        public function getParamCodeForPos(string $component, int $position): ?string {}
    }
    class NotificationParameterCollection
    {
        /**
         * @param NotificationParameter[] $params
         */
        public function __construct(public array $params) {}
        /**
         * @param  array $paramCodes
         *
         * @return NotificationParameter[]
         */
        public function getParametersByCode(array $paramCodes): array {}
        public function getValueGetterForParameter(string $paramCode): false|\Closure {}
        public function fixThisBindOnValueGetters(\Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $object): void {}
    }
    final class Client
    {
        public ?int $wpPhoneNumber = null;
        public readonly ?int $whmcsPhoneNumber;
        public readonly string $locale;
        /**
         * Is null when the client is not registered.
         *
         * @var string|null
         */
        public readonly ?string $countryCode;
        public function __construct(public readonly int $id) {}
        public function validateWpPhoneNumber(int $customFieldId): false|int {}
        public function validateWhmcsPhoneNumber(): false|int {}
        public function getWpPhoneNumberOrWhmcsPhoneNumber(?int $platformSpecificWpCustomFieldId): false|int {}
        /**
         * @param  integer|null $clientId
         * @param  integer      $customFieldId
         *
         * @return string
         */
        public function getCustomField(?int $clientId, int $customFieldId): string {}
    }
}

namespace Lkn\HookNotification\Core\NotificationReport\Http\Controllers {
    final class NotificationReportController extends \Lkn\HookNotification\Core\Shared\Infrastructure\Interfaces\BaseController
    {
        public function __construct(\Lkn\HookNotification\Core\Shared\Infrastructure\View\View $view) {}
        public function viewReports(array $request): void {}
    }
}

namespace Lkn\HookNotification\Core\NotificationReport\Application {
    final class NotificationReportService
    {
        public function __construct() {}
        /**
         * @param  integer $reportsPerPage
         * @param  integer $currentPage
         *
         * @return NotificationReport[]
         */
        public function getReportsForView(int $reportsPerPage, int $currentPage): array {}
        public function createReport(int $clientId, ?int $categoryId, ?\Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory $reportCategory, \Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportStatus $reportStatus, ?string $reportMsg, ?\Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms $platform, string $notificationCode, ?\Lkn\HookNotification\Core\Shared\Infrastructure\Hooks $hook, ?int $queueId = null, ?string $target = null) {}
        public function getReportsForCategory(\Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory $category, int $categoryId): array {}
        public function getStatistics(): array {}
    }
}

namespace Lkn\HookNotification\Core\NotificationReport\Infrastructure {
    final class NotificationReportRepository extends \Lkn\HookNotification\Core\Shared\Infrastructure\Repository\BaseRepository
    {
        public function paginate(int $offset, int $limit) {}
        public function insertReport(int $clientId, ?int $categoryId, ?\Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory $reportCategory, \Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportStatus $reportStatus, ?string $reportMsg, ?\Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms $platform, string $notificationCode, ?\Lkn\HookNotification\Core\Shared\Infrastructure\Hooks $hook, ?int $queueId, ?string $target): int {}
        public function getReportsForCategory(\Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory $category, int $categoryId): array {}
        public function getReportsForLastHour() {}
        public function getFailedReports() {}
        public function getTopNotificationsForLastHour() {}
    }
}

namespace Lkn\HookNotification\Core\NotificationReport\Domain {
    final class NotificationReport
    {
        public function __construct(public readonly int $id, public readonly ?int $clientId, public readonly ?int $categoryId, public readonly ?\Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory $category, public readonly ?\Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportStatus $status, public readonly ?string $msg, public readonly ?\Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms $platform, public readonly string $notificationCode, public readonly ?\Lkn\HookNotification\Core\Shared\Infrastructure\Hooks $notificationHook, public readonly \DateTime $createdAt) {}
    }
}

namespace Lkn\HookNotification\Core\Shared\Validators {
    final class PhoneNumberValidator extends \Lkn\HookNotification\Core\Shared\Infrastructure\Singleton
    {
        public static function isValid(int $phone, string $countryCode): bool {}
    }
}

namespace Lkn\HookNotification\Core\Shared\Application {
    /**
     * This class should use {Platform}SetupService when a platform settings changes.
     */
    final class SettingsService
    {
        public function __construct() {}
        /**
         * @param  Platforms   $platform
         * @param  string|null $subpage
         * @return array
         */
        public function getSettingsForView(\Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms $platform, ?string $subpage = null): array {}
        /**
         * @param  Platforms                           $platform
         * @param  string|null                         $subpage
         * @param  array<string, string|array<string>> $incomingSettings
         *
         * @return \Lkn\HookNotification\Core\Shared\Infrastructure\Result
         */
        public function updateSettings(\Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms $platform, ?string $subpage, array $incomingSettings): \Lkn\HookNotification\Core\Shared\Infrastructure\Result {}
    }
}

namespace Lkn\HookNotification\Core\Shared\Infrastructure\Repository {
    final class SettingsRepository extends \Lkn\HookNotification\Core\Shared\Infrastructure\Repository\BaseRepository
    {
        /**
         * @param  array     $newValuesBySetting [setting => value]
         * @param  Platforms $platform
         *
         * @return \Lkn\HookNotification\Core\Shared\Infrastructure\Result
         */
        public function massUpsert(\Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms $platform, array $newValuesBySetting) {}
        public function getSettingsForPlatform(\Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms $platform): array {}
        public function updateSettingsForPlatform(\Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms $platform, \Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings $setting, mixed $newValue) {}
    }
    final class ClientRepository extends \Lkn\HookNotification\Core\Shared\Infrastructure\Repository\BaseRepository
    {
        public function getCustomField(int $clientId, int $customFieldId): ?string {}
        public function getWhmcsPhoneNumber(int $clientId): ?string {}
        public function getClientCountry(int $clientId): ?string {}
        /**
         * @param  integer $clientId
         *
         * @return array{locale: string, langCode: string}
         */
        public function getClientLang(int $clientId): array {}
    }
}

namespace Lkn\HookNotification\Core\Shared\Infrastructure\Setup {
    final class DatabaseUpgrade
    {
        public static function v230(): void {}
        public static function v200(): void {}
        public static function v310(): void {}
        public static function v320(): void {}
        public static function v330(): void {}
        public static function v370(): void {}
        public static function v380(): void {}
        public static function v390(): void {}
        public static function v400() {}
        public static function v412(): void {}
        public static function v430(): void {}
    }
    final class DatabaseSetup
    {
        public static function activate(): array {}
        public static function deactivate(): array {}
    }
}

namespace Lkn\HookNotification\Core\Shared\Infrastructure {
    /**
     * @template T
     */
    class Result
    {
        /**
         * @var T|null
         */
        public ?array $data;
        /**
         * @param string|null  $code
         * @param T|null       $data
         * @param string|null  $msg
         * @param array|null   $errors
         * @param boolean|null $operationResult
         */
        public function __construct(public ?string $code = null, ?array $data = null, public ?string $msg = null, public ?array $errors = [], public readonly null|bool $operationResult = null) {}
        public function toArray(): array {}
    }
    final class ApiResponse
    {
        public readonly null|bool $operationResult;
        /**
         * @param  integer                                      $httpStatusCode
         * @param  array<int|string, mixed>|null|boolean|string $body
         */
        public function __construct(public readonly int $httpStatusCode, public readonly array|null|bool|string $body) {}
        public function setOperationResult(bool $result) {}
        /**
         * @return array<int|string, array<string, array<mixed>|bool|int|string|null>|bool>
         */
        public function toArray(): array {}
    }
}

namespace Lkn\HookNotification\Core\Shared\Infrastructure\View {
    /**
     * This class is highly tied to the templates (.tpl) files of the project.
     */
    final class View
    {
        public function __construct() {}
        public function setTemplateDir(string $templateDir) {}
        public function registerPlugin(string $type, string $name, callable $callback) {}
        public function assign(string $name, string|array $value) {}
        /**
         * Adds an alert to the page.
         *
         * Related to layout/alert.tpl
         *
         * @see https://getbootstrap.com/docs/3.4/components/#alerts
         *
         * @param  string $type  - success, info, warning, danger
         * @param  string $msg
         * @param  string $error
         *
         * @return self
         */
        public function alert(string $type, string $msg, ?string $error = null) {}
        /**
         * Renders the page.
         *
         * @param  string $filename
         * @param  array  $params
         *
         * @return self
         */
        public function view(string $filename, array $params = []): self {}
        public function render(): string {}
    }
}

namespace Lkn\HookNotification\Core\Shared\Infrastructure\I18n {
    final class I18n extends \Lkn\HookNotification\Core\Shared\Infrastructure\Singleton
    {
        public static function load(string $langCode): void {}
        /**
         * @param  string $language
         *
         * @return array<string, string>
         */
        public static function getTranslationsForCurrentLanguage(string $language): array {}
        /**
         * @param  string        $key
         * @param  array<string> $replacements
         *
         * @return string
         */
        public static function get(string $key, array $replacements = []): string {}
    }
}

namespace setasign\Fpdi {
    /**
     * The FpdiTrait
     *
     * This trait offers the core functionalities of FPDI. By passing them to a trait we can reuse it with e.g. TCPDF in a
     * very easy way.
     */
    trait FpdiTrait
    {
        /**
         * The pdf reader instances.
         *
         * @var PdfReader[]
         */
        protected $readers = [];
        /**
         * Instances created internally.
         *
         * @var array
         */
        protected $createdReaders = [];
        /**
         * The current reader id.
         *
         * @var string|null
         */
        protected $currentReaderId;
        /**
         * Data of all imported pages.
         *
         * @var array
         */
        protected $importedPages = [];
        /**
         * A map from object numbers of imported objects to new assigned object numbers by FPDF.
         *
         * @var array
         */
        protected $objectMap = [];
        /**
         * An array with information about objects, which needs to be copied to the resulting document.
         *
         * @var array
         */
        protected $objectsToCopy = [];
        /**
         * Release resources and file handles.
         *
         * This method is called internally when the document is created successfully. By default it only cleans up
         * stream reader instances which were created internally.
         *
         * @param bool $allReaders
         */
        public function cleanUp($allReaders = false) {}
        /**
         * Set the minimal PDF version.
         *
         * @param string $pdfVersion
         */
        protected function setMinPdfVersion($pdfVersion) {}
        /** @noinspection PhpUndefinedClassInspection */
        /**
         * Get a new pdf parser instance.
         *
         * @param StreamReader $streamReader
         * @param array $parserParams Individual parameters passed to the parser instance.
         * @return PdfParser|FpdiPdfParser
         */
        protected function getPdfParserInstance(\setasign\Fpdi\PdfParser\StreamReader $streamReader, array $parserParams = []) {}
        /**
         * Get an unique reader id by the $file parameter.
         *
         * @param string|resource|PdfReader|StreamReader $file An open file descriptor, a path to a file, a PdfReader
         *                                                     instance or a StreamReader instance.
         * @param array $parserParams Individual parameters passed to the parser instance.
         * @return string
         */
        protected function getPdfReaderId($file, array $parserParams = []) {}
        /**
         * Get a pdf reader instance by its id.
         *
         * @param string $id
         * @return PdfReader
         */
        protected function getPdfReader($id) {}
        /**
         * Set the source PDF file.
         *
         * @param string|resource|StreamReader $file Path to the file or a stream resource or a StreamReader instance.
         * @return int The page count of the PDF document.
         * @throws PdfParserException
         */
        public function setSourceFile($file) {}
        /**
         * Set the source PDF file with parameters which are passed to the parser instance.
         *
         * This method allows us to pass e.g. authentication information to the parser instance.
         *
         * @param string|resource|StreamReader $file Path to the file or a stream resource or a StreamReader instance.
         * @param array $parserParams Individual parameters passed to the parser instance.
         * @return int The page count of the PDF document.
         * @throws CrossReferenceException
         * @throws PdfParserException
         * @throws PdfTypeException
         */
        public function setSourceFileWithParserParams($file, array $parserParams = []) {}
        /**
         * Imports a page.
         *
         * @param int $pageNumber The page number.
         * @param string $box The page boundary to import. Default set to PageBoundaries::CROP_BOX.
         * @param bool $groupXObject Define the form XObject as a group XObject to support transparency (if used).
         * @param bool $importExternalLinks Define whether external links are imported or not.
         * @return string A unique string identifying the imported page.
         * @throws CrossReferenceException
         * @throws FilterException
         * @throws PdfParserException
         * @throws PdfTypeException
         * @throws PdfReaderException
         * @see PageBoundaries
         */
        public function importPage($pageNumber, $box = \setasign\Fpdi\PdfReader\PageBoundaries::CROP_BOX, $groupXObject = true, $importExternalLinks = false) {}
        /**
         * Draws an imported page onto the page.
         *
         * Give only one of the size parameters (width, height) to calculate the other one automatically in view to the
         * aspect ratio.
         *
         * @param mixed $pageId The page id
         * @param float|int|array $x The abscissa of upper-left corner. Alternatively you could use an assoc array
         *                           with the keys "x", "y", "width", "height", "adjustPageSize".
         * @param float|int $y The ordinate of upper-left corner.
         * @param float|int|null $width The width.
         * @param float|int|null $height The height.
         * @param bool $adjustPageSize
         * @return array The size.
         * @see Fpdi::getTemplateSize()
         */
        public function useImportedPage($pageId, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false) {}
        /**
         * This method will add additional data to the last created link/annotation.
         *
         * It is separated because TCPDF uses its own logic to handle link annotations.
         * This method is overwritten in the TCPDF implementation.
         *
         * @param array $externalLink
         * @param float|int $xPt
         * @param float|int $scaleX
         * @param float|int $yPt
         * @param float|int $newHeightPt
         * @param float|int $scaleY
         * @param array $importedPage
         * @return void
         */
        protected function adjustLastLink($externalLink, $xPt, $scaleX, $yPt, $newHeightPt, $scaleY, $importedPage) {}
        /**
         * Get the size of an imported page.
         *
         * Give only one of the size parameters (width, height) to calculate the other one automatically in view to the
         * aspect ratio.
         *
         * @param mixed $tpl The template id
         * @param float|int|null $width The width.
         * @param float|int|null $height The height.
         * @return array|bool An array with following keys: width, height, 0 (=width), 1 (=height), orientation (L or P)
         */
        public function getImportedPageSize($tpl, $width = null, $height = null) {}
        /**
         * Writes a PdfType object to the resulting buffer.
         *
         * @param PdfType $value
         * @throws PdfTypeException
         */
        protected function writePdfType(\setasign\Fpdi\PdfParser\Type\PdfType $value) {}
    }
}

namespace setasign\Fpdi\Tcpdf {
    /**
     * Class Fpdi
     *
     * This class let you import pages of existing PDF documents into a reusable structure for TCPDF.
     *
     * @method _encrypt_data(int $n, string $s) string
     */
    class Fpdi extends \TCPDF
    {
        use \setasign\Fpdi\FpdiTrait {
            writePdfType as fpdiWritePdfType;
            useImportedPage as fpdiUseImportedPage;
        }
        /**
         * FPDI version
         *
         * @string
         */
        const VERSION = '2.6.2';
        /**
         * A counter for template ids.
         *
         * @var int
         */
        protected $templateId = 0;
        /**
         * The currently used object number.
         *
         * @var int|null
         */
        protected $currentObjectNumber;
        protected function _enddoc() {}
        /**
         * Get the next template id.
         *
         * @return int
         */
        protected function getNextTemplateId() {}
        /**
         * Draws an imported page onto the page or another template.
         *
         * Give only one of the size parameters (width, height) to calculate the other one automatically in view to the
         * aspect ratio.
         *
         * @param mixed $tpl The template id
         * @param float|int|array $x The abscissa of upper-left corner. Alternatively you could use an assoc array
         *                           with the keys "x", "y", "width", "height", "adjustPageSize".
         * @param float|int $y The ordinate of upper-left corner.
         * @param float|int|null $width The width.
         * @param float|int|null $height The height.
         * @param bool $adjustPageSize
         * @return array The size
         * @see FpdiTrait::getTemplateSize()
         */
        public function useTemplate($tpl, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false) {}
        /**
         * Draws an imported page onto the page.
         *
         * Give only one of the size parameters (width, height) to calculate the other one automatically in view to the
         * aspect ratio.
         *
         * @param mixed $pageId The page id
         * @param float|int|array $x The abscissa of upper-left corner. Alternatively you could use an assoc array
         *                           with the keys "x", "y", "width", "height", "adjustPageSize".
         * @param float|int $y The ordinate of upper-left corner.
         * @param float|int|null $width The width.
         * @param float|int|null $height The height.
         * @param bool $adjustPageSize
         * @return array The size.
         * @see Fpdi::getTemplateSize()
         */
        public function useImportedPage($pageId, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false) {}
        /**
         * Get the size of an imported page.
         *
         * Give only one of the size parameters (width, height) to calculate the other one automatically in view to the
         * aspect ratio.
         *
         * @param mixed $tpl The template id
         * @param float|int|null $width The width.
         * @param float|int|null $height The height.
         * @return array|bool An array with following keys: width, height, 0 (=width), 1 (=height), orientation (L or P)
         */
        public function getTemplateSize($tpl, $width = null, $height = null) {}
        /**
         * @inheritdoc
         * @return string
         */
        protected function _getxobjectdict() {}
        /**
         * @inheritdoc
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        protected function _putxobjects() {}
        /**
         * Append content to the buffer of TCPDF.
         *
         * @param string $s
         * @param bool $newLine
         */
        protected function _put($s, $newLine = true) {}
        /**
         * Begin a new object and return the object number.
         *
         * @param int|string $objid Object ID (leave empty to get a new ID).
         * @return int object number
         */
        protected function _newobj($objid = '') {}
        /**
         * Writes a PdfType object to the resulting buffer.
         *
         * @param PdfType $value
         * @throws PdfTypeException
         */
        protected function writePdfType(\setasign\Fpdi\PdfParser\Type\PdfType $value) {}
        /**
         * This method will add additional data to the last created link/annotation.
         *
         * It will copy styling properties (supported by TCPDF) of the imported link.
         *
         * @param array $externalLink
         * @param float|int $xPt
         * @param float|int $scaleX
         * @param float|int $yPt
         * @param float|int $newHeightPt
         * @param float|int $scaleY
         * @param array $importedPage
         * @return void
         */
        protected function adjustLastLink($externalLink, $xPt, $scaleX, $yPt, $newHeightPt, $scaleY, $importedPage) {}
    }
}

namespace setasign\Fpdi {
    /**
     * Trait FpdfTplTrait
     *
     * This trait adds a templating feature to FPDF and tFPDF.
     */
    trait FpdfTplTrait
    {
        /**
         * Data of all created templates.
         *
         * @var array
         */
        protected $templates = [];
        /**
         * The template id for the currently created template.
         *
         * @var null|int
         */
        protected $currentTemplateId;
        /**
         * A counter for template ids.
         *
         * @var int
         */
        protected $templateId = 0;
        /**
         * Set the page format of the current page.
         *
         * @param array $size An array with two values defining the size.
         * @param string $orientation "L" for landscape, "P" for portrait.
         * @throws \BadMethodCallException
         */
        public function setPageFormat($size, $orientation) {}
        /**
         * Draws a template onto the page or another template.
         *
         * Give only one of the size parameters (width, height) to calculate the other one automatically in view to the
         * aspect ratio.
         *
         * @param mixed $tpl The template id
         * @param array|float|int $x The abscissa of upper-left corner. Alternatively you could use an assoc array
         *                           with the keys "x", "y", "width", "height", "adjustPageSize".
         * @param float|int $y The ordinate of upper-left corner.
         * @param float|int|null $width The width.
         * @param float|int|null $height The height.
         * @param bool $adjustPageSize
         * @return array The size
         * @see FpdfTplTrait::getTemplateSize()
         */
        public function useTemplate($tpl, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false) {}
        /**
         * Get the size of a template.
         *
         * Give only one of the size parameters (width, height) to calculate the other one automatically in view to the
         * aspect ratio.
         *
         * @param mixed $tpl The template id
         * @param float|int|null $width The width.
         * @param float|int|null $height The height.
         * @return array|bool An array with following keys: width, height, 0 (=width), 1 (=height), orientation (L or P)
         */
        public function getTemplateSize($tpl, $width = null, $height = null) {}
        /**
         * Begins a new template.
         *
         * @param float|int|null $width The width of the template. If null, the current page width is used.
         * @param float|int|null $height The height of the template. If null, the current page height is used.
         * @param bool $groupXObject Define the form XObject as a group XObject to support transparency (if used).
         * @return int A template identifier.
         */
        public function beginTemplate($width = null, $height = null, $groupXObject = false) {}
        /**
         * Ends a template.
         *
         * @return bool|int|null A template identifier.
         */
        public function endTemplate() {}
        /**
         * Get the next template id.
         *
         * @return int
         */
        protected function getNextTemplateId() {}
        /* overwritten FPDF methods: */
        /**
         * @inheritdoc
         */
        public function AddPage($orientation = '', $size = '', $rotation = 0) {}
        /**
         * @inheritdoc
         */
        public function Link($x, $y, $w, $h, $link) {}
        /**
         * @inheritdoc
         */
        public function SetLink($link, $y = 0, $page = -1) {}
        /**
         * @inheritdoc
         */
        public function SetDrawColor($r, $g = null, $b = null) {}
        /**
         * @inheritdoc
         */
        public function SetFillColor($r, $g = null, $b = null) {}
        /**
         * @inheritdoc
         */
        public function SetLineWidth($width) {}
        /**
         * @inheritdoc
         */
        public function SetFont($family, $style = '', $size = 0) {}
        /**
         * @inheritdoc
         */
        public function SetFontSize($size) {}
        protected function _putimages() {}
        /**
         * @inheritdoc
         */
        protected function _putxobjectdict() {}
        /**
         * @inheritdoc
         */
        public function _out($s) {}
    }
}

namespace setasign\Fpdi\Tfpdf {
    /**
     * Class FpdfTpl
     *
     * We need to change some access levels and implement the setPageFormat() method to bring back compatibility to tFPDF.
     */
    class FpdfTpl extends \tFPDF
    {
        use \setasign\Fpdi\FpdfTplTrait;
    }
}

namespace setasign\Fpdi {
    /**
     * This trait is used for the implementation of FPDI in FPDF and tFPDF.
     */
    trait FpdfTrait
    {
        protected function _enddoc() {}
        /**
         * Draws an imported page or a template onto the page or another template.
         *
         * Give only one of the size parameters (width, height) to calculate the other one automatically in view to the
         * aspect ratio.
         *
         * @param mixed $tpl The template id
         * @param float|int|array $x The abscissa of upper-left corner. Alternatively you could use an assoc array
         *                           with the keys "x", "y", "width", "height", "adjustPageSize".
         * @param float|int $y The ordinate of upper-left corner.
         * @param float|int|null $width The width.
         * @param float|int|null $height The height.
         * @param bool $adjustPageSize
         * @return array The size
         * @see Fpdi::getTemplateSize()
         */
        public function useTemplate($tpl, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false) {}
        /**
         * Get the size of an imported page or template.
         *
         * Give only one of the size parameters (width, height) to calculate the other one automatically in view to the
         * aspect ratio.
         *
         * @param mixed $tpl The template id
         * @param float|int|null $width The width.
         * @param float|int|null $height The height.
         * @return array|bool An array with following keys: width, height, 0 (=width), 1 (=height), orientation (L or P)
         */
        public function getTemplateSize($tpl, $width = null, $height = null) {}
        /**
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        protected function _putimages() {}
        /**
         * @inheritdoc
         */
        protected function _putxobjectdict() {}
        /**
         * @param int $n
         * @return void
         * @throws PdfParser\Type\PdfTypeException
         */
        protected function _putlinks($n) {}
        protected function _put($s, $newLine = true) {}
    }
}

namespace setasign\Fpdi\Tfpdf {
    /**
     * Class Fpdi
     *
     * This class let you import pages of existing PDF documents into a reusable structure for tFPDF.
     */
    class Fpdi extends \setasign\Fpdi\Tfpdf\FpdfTpl
    {
        use \setasign\Fpdi\FpdiTrait;
        use \setasign\Fpdi\FpdfTrait;
        /**
         * FPDI version
         *
         * @string
         */
        const VERSION = '2.6.2';
    }
}

namespace {
    /*******************************************************************************
     * FPDF                                                                         *
     *                                                                              *
     * Version: 1.86                                                                *
     * Date:    2023-06-25                                                          *
     * Author:  Olivier PLATHEY                                                     *
     *******************************************************************************/
    class FPDF
    {
        const VERSION = '1.86';
        protected $page;
        // current page number
        protected $n;
        // current object number
        protected $offsets;
        // array of object offsets
        protected $buffer;
        // buffer holding in-memory PDF
        protected $pages;
        // array containing pages
        protected $state;
        // current document state
        protected $compress;
        // compression flag
        protected $iconv;
        // whether iconv is available
        protected $k;
        // scale factor (number of points in user unit)
        protected $DefOrientation;
        // default orientation
        protected $CurOrientation;
        // current orientation
        protected $StdPageSizes;
        // standard page sizes
        protected $DefPageSize;
        // default page size
        protected $CurPageSize;
        // current page size
        protected $CurRotation;
        // current page rotation
        protected $PageInfo;
        // page-related data
        protected $wPt, $hPt;
        // dimensions of current page in points
        protected $w, $h;
        // dimensions of current page in user unit
        protected $lMargin;
        // left margin
        protected $tMargin;
        // top margin
        protected $rMargin;
        // right margin
        protected $bMargin;
        // page break margin
        protected $cMargin;
        // cell margin
        protected $x, $y;
        // current position in user unit
        protected $lasth;
        // height of last printed cell
        protected $LineWidth;
        // line width in user unit
        protected $fontpath;
        // directory containing fonts
        protected $CoreFonts;
        // array of core font names
        protected $fonts;
        // array of used fonts
        protected $FontFiles;
        // array of font files
        protected $encodings;
        // array of encodings
        protected $cmaps;
        // array of ToUnicode CMaps
        protected $FontFamily;
        // current font family
        protected $FontStyle;
        // current font style
        protected $underline;
        // underlining flag
        protected $CurrentFont;
        // current font info
        protected $FontSizePt;
        // current font size in points
        protected $FontSize;
        // current font size in user unit
        protected $DrawColor;
        // commands for drawing color
        protected $FillColor;
        // commands for filling color
        protected $TextColor;
        // commands for text color
        protected $ColorFlag;
        // indicates whether fill and text colors are different
        protected $WithAlpha;
        // indicates whether alpha channel is used
        protected $ws;
        // word spacing
        protected $images;
        // array of used images
        protected $PageLinks;
        // array of links in pages
        protected $links;
        // array of internal links
        protected $AutoPageBreak;
        // automatic page breaking
        protected $PageBreakTrigger;
        // threshold used to trigger page breaks
        protected $InHeader;
        // flag set when processing header
        protected $InFooter;
        // flag set when processing footer
        protected $AliasNbPages;
        // alias for total number of pages
        protected $ZoomMode;
        // zoom display mode
        protected $LayoutMode;
        // layout display mode
        protected $metadata;
        // document properties
        protected $CreationDate;
        // document creation date
        protected $PDFVersion;
        // PDF version number
        /*******************************************************************************
         *                               Public methods                                 *
         *******************************************************************************/
        function __construct($orientation = 'P', $unit = 'mm', $size = 'A4') {}
        function SetMargins($left, $top, $right = \null) {}
        function SetLeftMargin($margin) {}
        function SetTopMargin($margin) {}
        function SetRightMargin($margin) {}
        function SetAutoPageBreak($auto, $margin = 0) {}
        function SetDisplayMode($zoom, $layout = 'default') {}
        function SetCompression($compress) {}
        function SetTitle($title, $isUTF8 = \false) {}
        function SetAuthor($author, $isUTF8 = \false) {}
        function SetSubject($subject, $isUTF8 = \false) {}
        function SetKeywords($keywords, $isUTF8 = \false) {}
        function SetCreator($creator, $isUTF8 = \false) {}
        function AliasNbPages($alias = '{nb}') {}
        function Error($msg) {}
        function Close() {}
        function AddPage($orientation = '', $size = '', $rotation = 0) {}
        function Header() {}
        function Footer() {}
        function PageNo() {}
        function SetDrawColor($r, $g = \null, $b = \null) {}
        function SetFillColor($r, $g = \null, $b = \null) {}
        function SetTextColor($r, $g = \null, $b = \null) {}
        function GetStringWidth($s) {}
        function SetLineWidth($width) {}
        function Line($x1, $y1, $x2, $y2) {}
        function Rect($x, $y, $w, $h, $style = '') {}
        function AddFont($family, $style = '', $file = '', $dir = '') {}
        function SetFont($family, $style = '', $size = 0) {}
        function SetFontSize($size) {}
        function AddLink() {}
        function SetLink($link, $y = 0, $page = -1) {}
        function Link($x, $y, $w, $h, $link) {}
        function Text($x, $y, $txt) {}
        function AcceptPageBreak() {}
        function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = \false, $link = '') {}
        function MultiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = \false) {}
        function Write($h, $txt, $link = '') {}
        function Ln($h = \null) {}
        function Image($file, $x = \null, $y = \null, $w = 0, $h = 0, $type = '', $link = '') {}
        function GetPageWidth() {}
        function GetPageHeight() {}
        function GetX() {}
        function SetX($x) {}
        function GetY() {}
        function SetY($y, $resetX = \true) {}
        function SetXY($x, $y) {}
        function Output($dest = '', $name = '', $isUTF8 = \false) {}
        /*******************************************************************************
         *                              Protected methods                               *
         *******************************************************************************/
        protected function _checkoutput() {}
        protected function _getpagesize($size) {}
        protected function _beginpage($orientation, $size, $rotation) {}
        protected function _endpage() {}
        protected function _loadfont($path) {}
        protected function _isascii($s) {}
        protected function _httpencode($param, $value, $isUTF8) {}
        protected function _UTF8encode($s) {}
        protected function _UTF8toUTF16($s) {}
        protected function _escape($s) {}
        protected function _textstring($s) {}
        protected function _dounderline($x, $y, $txt) {}
        protected function _parsejpg($file) {}
        protected function _parsepng($file) {}
        protected function _parsepngstream($f, $file) {}
        protected function _readstream($f, $n) {}
        protected function _readint($f) {}
        protected function _parsegif($file) {}
        protected function _out($s) {}
        protected function _put($s) {}
        protected function _getoffset() {}
        protected function _newobj($n = \null) {}
        protected function _putstream($data) {}
        protected function _putstreamobject($data) {}
        protected function _putlinks($n) {}
        protected function _putpage($n) {}
        protected function _putpages() {}
        protected function _putfonts() {}
        protected function _tounicodecmap($uv) {}
        protected function _putimages() {}
        protected function _putimage(&$info) {}
        protected function _putxobjectdict() {}
        protected function _putresourcedict() {}
        protected function _putresources() {}
        protected function _putinfo() {}
        protected function _putcatalog() {}
        protected function _putheader() {}
        protected function _puttrailer() {}
        protected function _enddoc() {}
    }
}

namespace setasign\Fpdi {
    /**
     * Class FpdfTpl
     *
     * This class adds a templating feature to FPDF.
     */
    class FpdfTpl extends \FPDF
    {
        use \setasign\Fpdi\FpdfTplTrait;
    }
    /**
     * Class Fpdi
     *
     * This class let you import pages of existing PDF documents into a reusable structure for FPDF.
     */
    class Fpdi extends \setasign\Fpdi\FpdfTpl
    {
        use \setasign\Fpdi\FpdiTrait;
        use \setasign\Fpdi\FpdfTrait;
        /**
         * FPDI version
         *
         * @string
         */
        const VERSION = '2.6.2';
    }
    /**
     * Class TcpdfFpdi
     *
     * This class let you import pages of existing PDF documents into a reusable structure for TCPDF.
     *
     * @deprecated Class was moved to \setasign\Fpdi\Tcpdf\Fpdi
     */
    class TcpdfFpdi extends \setasign\Fpdi\Tcpdf\Fpdi
    {
        // this class is moved to \setasign\Fpdi\Tcpdf\Fpdi
    }
}

namespace setasign\Fpdi\Math {
    /**
     * A simple 2D-Vector class
     */
    class Vector
    {
        /**
         * @var float
         */
        protected $x;
        /**
         * @var float
         */
        protected $y;
        /**
         * @param int|float $x
         * @param int|float $y
         */
        public function __construct($x = 0.0, $y = 0.0) {}
        /**
         * @return float
         */
        public function getX() {}
        /**
         * @return float
         */
        public function getY() {}
        /**
         * @param Matrix $matrix
         * @return Vector
         */
        public function multiplyWithMatrix(\setasign\Fpdi\Math\Matrix $matrix) {}
    }
    /**
     * A simple 2D-Matrix class
     */
    class Matrix
    {
        /**
         * @var float
         */
        protected $a;
        /**
         * @var float
         */
        protected $b;
        /**
         * @var float
         */
        protected $c;
        /**
         * @var float
         */
        protected $d;
        /**
         * @var float
         */
        protected $e;
        /**
         * @var float
         */
        protected $f;
        /**
         * @param int|float $a
         * @param int|float $b
         * @param int|float $c
         * @param int|float $d
         * @param int|float $e
         * @param int|float $f
         */
        public function __construct($a = 1, $b = 0, $c = 0, $d = 1, $e = 0, $f = 0) {}
        /**
         * @return float[]
         */
        public function getValues() {}
        /**
         * @param Matrix $by
         * @return Matrix
         */
        public function multiply(self $by) {}
    }
}

namespace setasign\Fpdi\PdfParser {
    /**
     * A stream reader class
     */
    class StreamReader
    {
        /**
         * Creates a stream reader instance by a string value.
         *
         * @param string $content
         * @param int $maxMemory
         * @return StreamReader
         */
        public static function createByString($content, $maxMemory = 2097152) {}
        /**
         * Creates a stream reader instance by a filename.
         *
         * @param string $filename
         * @return StreamReader
         */
        public static function createByFile($filename) {}
        /**
         * Defines whether the stream should be closed when the stream reader instance is deconstructed or not.
         *
         * @var bool
         */
        protected $closeStream;
        /**
         * The stream resource.
         *
         * @var resource
         */
        protected $stream;
        /**
         * The byte-offset position in the stream.
         *
         * @var int
         */
        protected $position;
        /**
         * The byte-offset position in the buffer.
         *
         * @var int
         */
        protected $offset;
        /**
         * The buffer length.
         *
         * @var int
         */
        protected $bufferLength;
        /**
         * The total length of the stream.
         *
         * @var int
         */
        protected $totalLength;
        /**
         * The buffer.
         *
         * @var string
         */
        protected $buffer;
        /**
         * StreamReader constructor.
         *
         * @param resource $stream
         * @param bool $closeStream Defines whether to close the stream resource if the instance is destructed or not.
         */
        public function __construct($stream, $closeStream = false) {}
        /**
         * The destructor.
         */
        public function __destruct() {}
        /**
         * Closes the file handle.
         */
        public function cleanUp() {}
        /**
         * Returns the byte length of the buffer.
         *
         * @param bool $atOffset
         * @return int
         */
        public function getBufferLength($atOffset = false) {}
        /**
         * Get the current position in the stream.
         *
         * @return int
         */
        public function getPosition() {}
        /**
         * Returns the current buffer.
         *
         * @param bool $atOffset
         * @return string
         */
        public function getBuffer($atOffset = true) {}
        /**
         * Gets a byte at a specific position in the buffer.
         *
         * If the position is invalid the method will return false.
         *
         * If the $position parameter is set to null the value of $this->offset will be used.
         *
         * @param int|null $position
         * @return string|bool
         */
        public function getByte($position = null) {}
        /**
         * Returns a byte at a specific position, and set the offset to the next byte position.
         *
         * If the position is invalid the method will return false.
         *
         * If the $position parameter is set to null the value of $this->offset will be used.
         *
         * @param int|null $position
         * @return string|bool
         */
        public function readByte($position = null) {}
        /**
         * Read bytes from the current or a specific offset position and set the internal pointer to the next byte.
         *
         * If the position is invalid the method will return false.
         *
         * If the $position parameter is set to null the value of $this->offset will be used.
         *
         * @param int $length
         * @param int|null $position
         * @return string|false
         */
        public function readBytes($length, $position = null) {}
        /**
         * Read a line from the current position.
         *
         * @param int $length
         * @return string|bool
         */
        public function readLine($length = 1024) {}
        /**
         * Set the offset position in the current buffer.
         *
         * @param int $offset
         */
        public function setOffset($offset) {}
        /**
         * Returns the current offset in the current buffer.
         *
         * @return int
         */
        public function getOffset() {}
        /**
         * Add an offset to the current offset.
         *
         * @param int $offset
         */
        public function addOffset($offset) {}
        /**
         * Make sure that there is at least one character beyond the current offset in the buffer.
         *
         * @return bool
         */
        public function ensureContent() {}
        /**
         * Returns the stream.
         *
         * @return resource
         */
        public function getStream() {}
        /**
         * Gets the total available length.
         *
         * @return int
         */
        public function getTotalLength() {}
        /**
         * Resets the buffer to a position and re-read the buffer with the given length.
         *
         * If the $pos parameter is negative the start buffer position will be the $pos'th position from
         * the end of the file.
         *
         * If the $pos parameter is negative and the absolute value is bigger then the totalLength of
         * the file $pos will set to zero.
         *
         * @param int|null $pos Start position of the new buffer
         * @param int $length Length of the new buffer. Mustn't be negative
         */
        public function reset($pos = 0, $length = 200) {}
        /**
         * Ensures bytes in the buffer with a specific length and location in the file.
         *
         * @param int $pos
         * @param int $length
         * @see reset()
         */
        public function ensure($pos, $length) {}
        /**
         * Forcefully read more data into the buffer.
         *
         * @param int $minLength
         * @return bool Returns false if the stream reaches the end
         */
        public function increaseLength($minLength = 100) {}
    }
}

namespace setasign\Fpdi {
    /**
     * Base exception class for the FPDI package.
     */
    class FpdiException extends \Exception {}
}

namespace setasign\Fpdi\PdfParser {
    /**
     * Exception for the pdf parser class
     */
    class PdfParserException extends \setasign\Fpdi\FpdiException
    {
        /**
         * @var int
         */
        const NOT_IMPLEMENTED = 0x1;
        /**
         * @var int
         */
        const IMPLEMENTED_IN_FPDI_PDF_PARSER = 0x2;
        /**
         * @var int
         */
        const INVALID_DATA_TYPE = 0x3;
        /**
         * @var int
         */
        const FILE_HEADER_NOT_FOUND = 0x4;
        /**
         * @var int
         */
        const PDF_VERSION_NOT_FOUND = 0x5;
        /**
         * @var int
         */
        const INVALID_DATA_SIZE = 0x6;
    }
}

namespace setasign\Fpdi\PdfParser\CrossReference {
    /**
     * ReaderInterface for cross-reference readers.
     */
    interface ReaderInterface
    {
        /**
         * Get an offset by an object number.
         *
         * @param int $objectNumber
         * @return int|bool False if the offset was not found.
         */
        public function getOffsetFor($objectNumber);
        /**
         * Get the trailer related to this cross reference.
         *
         * @return PdfDictionary
         */
        public function getTrailer();
    }
    /**
     * Exception used by the CrossReference and Reader classes.
     */
    class CrossReferenceException extends \setasign\Fpdi\PdfParser\PdfParserException
    {
        /**
         * @var int
         */
        const INVALID_DATA = 0x101;
        /**
         * @var int
         */
        const XREF_MISSING = 0x102;
        /**
         * @var int
         */
        const ENTRIES_TOO_LARGE = 0x103;
        /**
         * @var int
         */
        const ENTRIES_TOO_SHORT = 0x104;
        /**
         * @var int
         */
        const NO_ENTRIES = 0x105;
        /**
         * @var int
         */
        const NO_TRAILER_FOUND = 0x106;
        /**
         * @var int
         */
        const NO_STARTXREF_FOUND = 0x107;
        /**
         * @var int
         */
        const NO_XREF_FOUND = 0x108;
        /**
         * @var int
         */
        const UNEXPECTED_END = 0x109;
        /**
         * @var int
         */
        const OBJECT_NOT_FOUND = 0x10a;
        /**
         * @var int
         */
        const COMPRESSED_XREF = 0x10b;
        /**
         * @var int
         */
        const ENCRYPTED = 0x10c;
    }
    /**
     * Abstract class for cross-reference reader classes.
     */
    abstract class AbstractReader
    {
        /**
         * @var PdfParser
         */
        protected $parser;
        /**
         * @var PdfDictionary
         */
        protected $trailer;
        /**
         * AbstractReader constructor.
         *
         * @param PdfParser $parser
         * @throws CrossReferenceException
         * @throws PdfTypeException
         */
        public function __construct(\setasign\Fpdi\PdfParser\PdfParser $parser) {}
        /**
         * Get the trailer dictionary.
         *
         * @return PdfDictionary
         */
        public function getTrailer() {}
        /**
         * Read the trailer dictionary.
         *
         * @throws CrossReferenceException
         * @throws PdfTypeException
         */
        protected function readTrailer() {}
    }
    /**
     * Class LineReader
     *
     * This reader class read all cross-reference entries in a single run.
     * It supports reading cross-references with e.g. invalid data (e.g. entries with a length < or > 20 bytes).
     */
    class LineReader extends \setasign\Fpdi\PdfParser\CrossReference\AbstractReader implements \setasign\Fpdi\PdfParser\CrossReference\ReaderInterface
    {
        /**
         * The object offsets.
         *
         * @var array
         */
        protected $offsets;
        /**
         * LineReader constructor.
         *
         * @param PdfParser $parser
         * @throws CrossReferenceException
         */
        public function __construct(\setasign\Fpdi\PdfParser\PdfParser $parser) {}
        /**
         * @inheritdoc
         * @return int|false
         */
        public function getOffsetFor($objectNumber) {}
        /**
         * Get all found offsets.
         *
         * @return array
         */
        public function getOffsets() {}
        /**
         * Extracts the cross reference data from the stream reader.
         *
         * @param StreamReader $reader
         * @return string
         * @throws CrossReferenceException
         */
        protected function extract(\setasign\Fpdi\PdfParser\StreamReader $reader) {}
        /**
         * Read the cross-reference entries.
         *
         * @param string $xrefContent
         * @throws CrossReferenceException
         */
        protected function read($xrefContent) {}
    }
    /**
     * Class CrossReference
     *
     * This class processes the standard cross reference of a PDF document.
     */
    class CrossReference
    {
        /**
         * The byte length in which the "startxref" keyword should be searched.
         *
         * @var int
         */
        public static $trailerSearchLength = 5500;
        /**
         * @var int
         */
        protected $fileHeaderOffset = 0;
        /**
         * @var PdfParser
         */
        protected $parser;
        /**
         * @var ReaderInterface[]
         */
        protected $readers = [];
        /**
         * CrossReference constructor.
         *
         * @param PdfParser $parser
         * @throws CrossReferenceException
         * @throws PdfTypeException
         */
        public function __construct(\setasign\Fpdi\PdfParser\PdfParser $parser, $fileHeaderOffset = 0) {}
        /**
         * Get the size of the cross reference.
         *
         * @return integer
         */
        public function getSize() {}
        /**
         * Get the trailer dictionary.
         *
         * @return PdfDictionary
         */
        public function getTrailer() {}
        /**
         * Get the cross reference readser instances.
         *
         * @return ReaderInterface[]
         */
        public function getReaders() {}
        /**
         * Get the offset by an object number.
         *
         * @param int $objectNumber
         * @return integer|bool
         */
        public function getOffsetFor($objectNumber) {}
        /**
         * Get an indirect object by its object number.
         *
         * @param int $objectNumber
         * @return PdfIndirectObject
         * @throws CrossReferenceException
         */
        public function getIndirectObject($objectNumber) {}
        /**
         * Read the cross-reference table at a given offset.
         *
         * Internally the method will try to evaluate the best reader for this cross-reference.
         *
         * @param int $offset
         * @return ReaderInterface
         * @throws CrossReferenceException
         * @throws PdfTypeException
         */
        protected function readXref($offset) {}
        /**
         * Get a cross-reference reader instance.
         *
         * @param PdfToken|PdfIndirectObject $initValue
         * @return ReaderInterface|bool
         * @throws CrossReferenceException
         * @throws PdfTypeException
         */
        protected function initReaderInstance($initValue) {}
        /**
         * Check for encryption.
         *
         * @param PdfDictionary $dictionary
         * @throws CrossReferenceException
         */
        protected function checkForEncryption(\setasign\Fpdi\PdfParser\Type\PdfDictionary $dictionary) {}
        /**
         * Find the start position for the first cross-reference.
         *
         * @return int The byte-offset position of the first cross-reference.
         * @throws CrossReferenceException
         */
        protected function findStartXref() {}
    }
    /**
     * Class FixedReader
     *
     * This reader allows a very less overhead parsing of single entries of the cross-reference, because the main entries
     * are only read when needed and not in a single run.
     */
    class FixedReader extends \setasign\Fpdi\PdfParser\CrossReference\AbstractReader implements \setasign\Fpdi\PdfParser\CrossReference\ReaderInterface
    {
        /**
         * @var StreamReader
         */
        protected $reader;
        /**
         * Data of subsections.
         *
         * @var array
         */
        protected $subSections;
        /**
         * FixedReader constructor.
         *
         * @param PdfParser $parser
         * @throws CrossReferenceException
         */
        public function __construct(\setasign\Fpdi\PdfParser\PdfParser $parser) {}
        /**
         * Get all subsection data.
         *
         * @return array
         */
        public function getSubSections() {}
        /**
         * @inheritdoc
         * @return int|false
         */
        public function getOffsetFor($objectNumber) {}
        /**
         * Read the cross-reference.
         *
         * This reader will only read the subsections in this method. The offsets were resolved individually by this
         * information.
         *
         * @throws CrossReferenceException
         */
        protected function read() {}
        /**
         * Fixes an invalid object number shift.
         *
         * This method can be used to repair documents with an invalid subsection header:
         *
         * <code>
         * xref
         * 1 7
         * 0000000000 65535 f
         * 0000000009 00000 n
         * 0000412075 00000 n
         * 0000412172 00000 n
         * 0000412359 00000 n
         * 0000412417 00000 n
         * 0000412468 00000 n
         * </code>
         *
         * It shall only be called on the first table.
         *
         * @return bool
         */
        public function fixFaultySubSectionShift() {}
    }
}

namespace setasign\Fpdi\PdfParser {
    /**
     * A PDF parser class
     */
    class PdfParser
    {
        /**
         * @var StreamReader
         */
        protected $streamReader;
        /**
         * @var Tokenizer
         */
        protected $tokenizer;
        /**
         * The file header.
         *
         * @var string
         */
        protected $fileHeader;
        /**
         * The offset to the file header.
         *
         * @var int
         */
        protected $fileHeaderOffset;
        /**
         * @var CrossReference|null
         */
        protected $xref;
        /**
         * All read objects.
         *
         * @var array
         */
        protected $objects = [];
        /**
         * PdfParser constructor.
         *
         * @param StreamReader $streamReader
         */
        public function __construct(\setasign\Fpdi\PdfParser\StreamReader $streamReader) {}
        /**
         * Removes cycled references.
         *
         * @internal
         */
        public function cleanUp() {}
        /**
         * Get the stream reader instance.
         *
         * @return StreamReader
         */
        public function getStreamReader() {}
        /**
         * Get the tokenizer instance.
         *
         * @return Tokenizer
         */
        public function getTokenizer() {}
        /**
         * Resolves the file header.
         *
         * @throws PdfParserException
         * @return int
         */
        protected function resolveFileHeader() {}
        /**
         * Get the cross-reference instance.
         *
         * @return CrossReference
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        public function getCrossReference() {}
        /**
         * Get the PDF version.
         *
         * @return int[] An array of major and minor version.
         * @throws PdfParserException
         */
        public function getPdfVersion() {}
        /**
         * Get the catalog dictionary.
         *
         * @return PdfDictionary
         * @throws Type\PdfTypeException
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        public function getCatalog() {}
        /**
         * Get an indirect object by its object number.
         *
         * @param int $objectNumber
         * @param bool $cache
         * @return PdfIndirectObject
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        public function getIndirectObject($objectNumber, $cache = false) {}
        /**
         * Read a PDF value.
         *
         * @param null|bool|string $token
         * @param null|string $expectedType
         * @return false|PdfArray|PdfBoolean|PdfDictionary|PdfHexString|PdfIndirectObject|PdfIndirectObjectReference|PdfName|PdfNull|PdfNumeric|PdfStream|PdfString|PdfToken
         * @throws Type\PdfTypeException
         */
        public function readValue($token = null, $expectedType = null) {}
        /**
         * @return PdfString
         */
        protected function parsePdfString() {}
        /**
         * @return false|PdfHexString
         */
        protected function parsePdfHexString() {}
        /**
         * @return bool|PdfDictionary
         * @throws PdfTypeException
         */
        protected function parsePdfDictionary() {}
        /**
         * @return PdfName
         */
        protected function parsePdfName() {}
        /**
         * @return false|PdfArray
         * @throws PdfTypeException
         */
        protected function parsePdfArray() {}
        /**
         * @param int $objectNumber
         * @param int $generationNumber
         * @return false|PdfIndirectObject
         * @throws Type\PdfTypeException
         */
        protected function parsePdfIndirectObject($objectNumber, $generationNumber) {}
        /**
         * Ensures that the token will evaluate to an expected object type (or not).
         *
         * @param string $token
         * @param string|null $expectedType
         * @return bool
         * @throws Type\PdfTypeException
         */
        protected function ensureExpectedType($token, $expectedType) {}
    }
}

namespace setasign\Fpdi\PdfParser\Type {
    /**
     * A class defining a PDF data type
     */
    class PdfType
    {
        /**
         * Resolves a PdfType value to its value.
         *
         * This method is used to evaluate indirect and direct object references until a final value is reached.
         *
         * @param PdfType $value
         * @param PdfParser $parser
         * @param bool $stopAtIndirectObject
         * @return PdfType
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        public static function resolve(\setasign\Fpdi\PdfParser\Type\PdfType $value, \setasign\Fpdi\PdfParser\PdfParser $parser, $stopAtIndirectObject = false) {}
        /**
         * Ensure that a value is an instance of a specific PDF type.
         *
         * @param string $type
         * @param PdfType $value
         * @param string $errorMessage
         * @return mixed
         * @throws PdfTypeException
         */
        protected static function ensureType($type, $value, $errorMessage) {}
        /**
         * Flatten indirect object references to direct objects.
         *
         * @param PdfType $value
         * @param PdfParser $parser
         * @return PdfType
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        public static function flatten(\setasign\Fpdi\PdfParser\Type\PdfType $value, \setasign\Fpdi\PdfParser\PdfParser $parser) {}
        /**
         * The value of the PDF type.
         *
         * @var mixed
         */
        public $value;
    }
    /**
     * Class representing a PDF stream object
     */
    class PdfStream extends \setasign\Fpdi\PdfParser\Type\PdfType
    {
        /**
         * Parses a stream from a stream reader.
         *
         * @param PdfDictionary $dictionary
         * @param StreamReader $reader
         * @param PdfParser|null $parser Optional to keep backwards compatibility
         * @return self
         * @throws PdfTypeException
         */
        public static function parse(\setasign\Fpdi\PdfParser\Type\PdfDictionary $dictionary, \setasign\Fpdi\PdfParser\StreamReader $reader, $parser = null) {}
        /**
         * Helper method to create an instance.
         *
         * @param PdfDictionary $dictionary
         * @param string $stream
         * @return self
         */
        public static function create(\setasign\Fpdi\PdfParser\Type\PdfDictionary $dictionary, $stream) {}
        /**
         * Ensures that the passed value is a PdfStream instance.
         *
         * @param mixed $stream
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($stream) {}
        /**
         * The stream or its byte-offset position.
         *
         * @var int|string
         */
        protected $stream;
        /**
         * The stream reader instance.
         *
         * @var StreamReader|null
         */
        protected $reader;
        /**
         * The PDF parser instance.
         *
         * @var PdfParser
         */
        protected $parser;
        /**
         * Get the stream data.
         *
         * @param bool $cache Whether cache the stream data or not.
         * @return bool|string
         * @throws PdfTypeException
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        public function getStream($cache = false) {}
        /**
         * Extract the stream "manually".
         *
         * @return string
         * @throws PdfTypeException
         */
        protected function extractStream() {}
        /**
         * Get all filters defined for this stream.
         *
         * @return PdfType[]
         * @throws PdfTypeException
         */
        public function getFilters() {}
        /**
         * Get the unfiltered stream data.
         *
         * @return string
         * @throws FilterException
         * @throws PdfParserException
         */
        public function getUnfilteredStream() {}
    }
    /**
     * Class representing an indirect object reference
     */
    class PdfIndirectObjectReference extends \setasign\Fpdi\PdfParser\Type\PdfType
    {
        /**
         * Helper method to create an instance.
         *
         * @param int $objectNumber
         * @param int $generationNumber
         * @return self
         */
        public static function create($objectNumber, $generationNumber) {}
        /**
         * Ensures that the passed value is a PdfIndirectObject instance.
         *
         * @param mixed $value
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($value) {}
        /**
         * The generation number.
         *
         * @var int
         */
        public $generationNumber;
    }
    /**
     * Class representing a hexadecimal encoded PDF string object
     */
    class PdfHexString extends \setasign\Fpdi\PdfParser\Type\PdfType
    {
        /**
         * Parses a hexadecimal string object from the stream reader.
         *
         * @param StreamReader $streamReader
         * @return false|self
         */
        public static function parse(\setasign\Fpdi\PdfParser\StreamReader $streamReader) {}
        /**
         * Helper method to create an instance.
         *
         * @param string $string The hex encoded string.
         * @return self
         */
        public static function create($string) {}
        /**
         * Ensures that the passed value is a PdfHexString instance.
         *
         * @param mixed $hexString
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($hexString) {}
    }
    /**
     * Class representing a boolean PDF object
     */
    class PdfBoolean extends \setasign\Fpdi\PdfParser\Type\PdfType
    {
        /**
         * Helper method to create an instance.
         *
         * @param bool $value
         * @return self
         */
        public static function create($value) {}
        /**
         * Ensures that the passed value is a PdfBoolean instance.
         *
         * @param mixed $value
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($value) {}
    }
    /**
     * Class representing a numeric PDF object
     */
    class PdfNumeric extends \setasign\Fpdi\PdfParser\Type\PdfType
    {
        /**
         * Helper method to create an instance.
         *
         * @param int|float $value
         * @return PdfNumeric
         */
        public static function create($value) {}
        /**
         * Ensures that the passed value is a PdfNumeric instance.
         *
         * @param mixed $value
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($value) {}
    }
    /**
     * Class representing a PDF array object
     *
     * @property array $value The value of the PDF type.
     */
    class PdfArray extends \setasign\Fpdi\PdfParser\Type\PdfType
    {
        /**
         * Parses an array of the passed tokenizer and parser.
         *
         * @param Tokenizer $tokenizer
         * @param PdfParser $parser
         * @return false|self
         * @throws PdfTypeException
         */
        public static function parse(\setasign\Fpdi\PdfParser\Tokenizer $tokenizer, \setasign\Fpdi\PdfParser\PdfParser $parser) {}
        /**
         * Helper method to create an instance.
         *
         * @param PdfType[] $values
         * @return self
         */
        public static function create(array $values = []) {}
        /**
         * Ensures that the passed array is a PdfArray instance with a (optional) specific size.
         *
         * @param mixed $array
         * @param null|int $size
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($array, $size = null) {}
    }
    /**
     * Class representing a PDF name object
     */
    class PdfName extends \setasign\Fpdi\PdfParser\Type\PdfType
    {
        /**
         * Parses a name object from the passed tokenizer and stream-reader.
         *
         * @param Tokenizer $tokenizer
         * @param StreamReader $streamReader
         * @return self
         */
        public static function parse(\setasign\Fpdi\PdfParser\Tokenizer $tokenizer, \setasign\Fpdi\PdfParser\StreamReader $streamReader) {}
        /**
         * Unescapes a name string.
         *
         * @param string $value
         * @return string
         */
        public static function unescape($value) {}
        /**
         * Helper method to create an instance.
         *
         * @param string $string
         * @return self
         */
        public static function create($string) {}
        /**
         * Ensures that the passed value is a PdfName instance.
         *
         * @param mixed $name
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($name) {}
    }
    /**
     * Exception class for pdf type classes
     */
    class PdfTypeException extends \setasign\Fpdi\PdfParser\PdfParserException
    {
        /**
         * @var int
         */
        const NO_NEWLINE_AFTER_STREAM_KEYWORD = 0x601;
    }
    /**
     * Class representing a PDF string object
     */
    class PdfString extends \setasign\Fpdi\PdfParser\Type\PdfType
    {
        /**
         * Parses a string object from the stream reader.
         *
         * @param StreamReader $streamReader
         * @return self
         */
        public static function parse(\setasign\Fpdi\PdfParser\StreamReader $streamReader) {}
        /**
         * Helper method to create an instance.
         *
         * @param string $value The string needs to be escaped accordingly.
         * @return self
         */
        public static function create($value) {}
        /**
         * Ensures that the passed value is a PdfString instance.
         *
         * @param mixed $string
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($string) {}
        /**
         * Escapes sequences in a string according to the PDF specification.
         *
         * @param string $s
         * @return string
         */
        public static function escape($s) {}
        /**
         * Unescapes escaped sequences in a PDF string according to the PDF specification.
         *
         * @param string $s
         * @return string
         */
        public static function unescape($s) {}
    }
    /**
     * Class representing a PDF null object
     */
    class PdfNull extends \setasign\Fpdi\PdfParser\Type\PdfType
    {
        // empty body
    }
    /**
     * Class representing PDF token object
     */
    class PdfToken extends \setasign\Fpdi\PdfParser\Type\PdfType
    {
        /**
         * Helper method to create an instance.
         *
         * @param string $token
         * @return self
         */
        public static function create($token) {}
        /**
         * Ensures that the passed value is a PdfToken instance.
         *
         * @param mixed $token
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($token) {}
    }
    /**
     * Class representing a PDF dictionary object
     */
    class PdfDictionary extends \setasign\Fpdi\PdfParser\Type\PdfType
    {
        /**
         * Parses a dictionary of the passed tokenizer, stream-reader and parser.
         *
         * @param Tokenizer $tokenizer
         * @param StreamReader $streamReader
         * @param PdfParser $parser
         * @return bool|self
         * @throws PdfTypeException
         */
        public static function parse(\setasign\Fpdi\PdfParser\Tokenizer $tokenizer, \setasign\Fpdi\PdfParser\StreamReader $streamReader, \setasign\Fpdi\PdfParser\PdfParser $parser) {}
        /**
         * Helper method to create an instance.
         *
         * @param PdfType[] $entries The keys are the name entries of the dictionary.
         * @return self
         */
        public static function create(array $entries = []) {}
        /**
         * Get a value by its key from a dictionary or a default value.
         *
         * @param mixed $dictionary
         * @param string $key
         * @param PdfType|null $default
         * @return PdfNull|PdfType
         * @throws PdfTypeException
         */
        public static function get($dictionary, $key, $default = null) {}
        /**
         * Ensures that the passed value is a PdfDictionary instance.
         *
         * @param mixed $dictionary
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($dictionary) {}
    }
    /**
     * Class representing an indirect object
     */
    class PdfIndirectObject extends \setasign\Fpdi\PdfParser\Type\PdfType
    {
        /**
         * Parses an indirect object from a tokenizer, parser and stream-reader.
         *
         * @param int $objectNumber
         * @param int $objectGenerationNumber
         * @param PdfParser $parser
         * @param Tokenizer $tokenizer
         * @param StreamReader $reader
         * @return self|false
         * @throws PdfTypeException
         */
        public static function parse($objectNumber, $objectGenerationNumber, \setasign\Fpdi\PdfParser\PdfParser $parser, \setasign\Fpdi\PdfParser\Tokenizer $tokenizer, \setasign\Fpdi\PdfParser\StreamReader $reader) {}
        /**
         * Helper method to create an instance.
         *
         * @param int $objectNumber
         * @param int $generationNumber
         * @param PdfType $value
         * @return self
         */
        public static function create($objectNumber, $generationNumber, \setasign\Fpdi\PdfParser\Type\PdfType $value) {}
        /**
         * Ensures that the passed value is a PdfIndirectObject instance.
         *
         * @param mixed $indirectObject
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($indirectObject) {}
        /**
         * The object number.
         *
         * @var int
         */
        public $objectNumber;
        /**
         * The generation number.
         *
         * @var int
         */
        public $generationNumber;
    }
}

namespace setasign\Fpdi\PdfParser {
    /**
     * A tokenizer class.
     */
    class Tokenizer
    {
        /**
         * @var StreamReader
         */
        protected $streamReader;
        /**
         * A token stack.
         *
         * @var string[]
         */
        protected $stack = [];
        /**
         * Tokenizer constructor.
         *
         * @param StreamReader $streamReader
         */
        public function __construct(\setasign\Fpdi\PdfParser\StreamReader $streamReader) {}
        /**
         * Get the stream reader instance.
         *
         * @return StreamReader
         */
        public function getStreamReader() {}
        /**
         * Clear the token stack.
         */
        public function clearStack() {}
        /**
         * Push a token onto the stack.
         *
         * @param string $token
         */
        public function pushStack($token) {}
        /**
         * Get next token.
         *
         * @return false|string
         */
        public function getNextToken() {}
        /**
         * Leap white spaces.
         *
         * @return boolean
         */
        public function leapWhiteSpaces() {}
    }
}

namespace setasign\Fpdi\PdfParser\Filter {
    /**
     * Interface for filters
     */
    interface FilterInterface
    {
        /**
         * Decode a string.
         *
         * @param string $data The input string
         * @return string
         */
        public function decode($data);
    }
    /**
     * Exception for filters
     */
    class FilterException extends \setasign\Fpdi\PdfParser\PdfParserException
    {
        const UNSUPPORTED_FILTER = 0x201;
        const NOT_IMPLEMENTED = 0x202;
    }
    /**
     * Exception for Ascii85 filter class
     */
    class Ascii85Exception extends \setasign\Fpdi\PdfParser\Filter\FilterException
    {
        /**
         * @var integer
         */
        const ILLEGAL_CHAR_FOUND = 0x301;
        /**
         * @var integer
         */
        const ILLEGAL_LENGTH = 0x302;
    }
    /**
     * Exception for flate filter class
     */
    class FlateException extends \setasign\Fpdi\PdfParser\Filter\FilterException
    {
        /**
         * @var integer
         */
        const NO_ZLIB = 0x401;
        /**
         * @var integer
         */
        const DECOMPRESS_ERROR = 0x402;
    }
    /**
     * Class for handling zlib/deflate encoded data
     */
    class Flate implements \setasign\Fpdi\PdfParser\Filter\FilterInterface
    {
        /**
         * Checks whether the zlib extension is loaded.
         *
         * Used for testing purpose.
         *
         * @return boolean
         * @internal
         */
        protected function extensionLoaded() {}
        /**
         * Decodes a flate compressed string.
         *
         * @param string|false $data The input string
         * @return string
         * @throws FlateException
         */
        public function decode($data) {}
    }
    /**
     * Class for handling ASCII base-85 encoded data
     */
    class Ascii85 implements \setasign\Fpdi\PdfParser\Filter\FilterInterface
    {
        /**
         * Decode ASCII85 encoded string.
         *
         * @param string $data The input string
         * @return string
         * @throws Ascii85Exception
         */
        public function decode($data) {}
    }
    /**
     * Exception for LZW filter class
     */
    class LzwException extends \setasign\Fpdi\PdfParser\Filter\FilterException
    {
        /**
         * @var integer
         */
        const LZW_FLAVOUR_NOT_SUPPORTED = 0x501;
    }
    /**
     * Class for handling ASCII hexadecimal encoded data
     */
    class AsciiHex implements \setasign\Fpdi\PdfParser\Filter\FilterInterface
    {
        /**
         * Converts an ASCII hexadecimal encoded string into its binary representation.
         *
         * @param string $data The input string
         * @return string
         */
        public function decode($data) {}
        /**
         * Converts a string into ASCII hexadecimal representation.
         *
         * @param string $data The input string
         * @param boolean $leaveEOD
         * @return string
         */
        public function encode($data, $leaveEOD = false) {}
    }
    /**
     * Class for handling LZW encoded data
     */
    class Lzw implements \setasign\Fpdi\PdfParser\Filter\FilterInterface
    {
        /**
         * @var null|string
         */
        protected $data;
        /**
         * @var array
         */
        protected $sTable = [];
        /**
         * @var int
         */
        protected $dataLength = 0;
        /**
         * @var int
         */
        protected $tIdx;
        /**
         * @var int
         */
        protected $bitsToGet = 9;
        /**
         * @var int
         */
        protected $bytePointer;
        /**
         * @var int
         */
        protected $nextData = 0;
        /**
         * @var int
         */
        protected $nextBits = 0;
        /**
         * @var array
         */
        protected $andTable = [511, 1023, 2047, 4095];
        /**
         * Method to decode LZW compressed data.
         *
         * @param string $data The compressed data
         * @return string The uncompressed data
         * @throws LzwException
         */
        public function decode($data) {}
        /**
         * Initialize the string table.
         */
        protected function initsTable() {}
        /**
         * Add a new string to the string table.
         *
         * @param string $oldString
         * @param string $newString
         */
        protected function addStringToTable($oldString, $newString = '') {}
        /**
         * Returns the next 9, 10, 11 or 12 bits.
         *
         * @return int
         */
        protected function getNextCode() {}
    }
}

namespace setasign\Fpdi {
    /**
     * A simple graphic state class which holds the current transformation matrix.
     */
    class GraphicsState
    {
        /**
         * @var Matrix
         */
        protected $ctm;
        /**
         * @param Matrix|null $ctm
         */
        public function __construct($ctm = null) {}
        /**
         * @param Matrix $matrix
         * @return $this
         */
        public function add(\setasign\Fpdi\Math\Matrix $matrix) {}
        /**
         * @param int|float $x
         * @param int|float $y
         * @param int|float $angle
         * @return $this
         */
        public function rotate($x, $y, $angle) {}
        /**
         * @param int|float $shiftX
         * @param int|float $shiftY
         * @return $this
         */
        public function translate($shiftX, $shiftY) {}
        /**
         * @param int|float $scaleX
         * @param int|float $scaleY
         * @return $this
         */
        public function scale($scaleX, $scaleY) {}
        /**
         * @param Vector $vector
         * @return Vector
         */
        public function toUserSpace(\setasign\Fpdi\Math\Vector $vector) {}
    }
}

namespace setasign\Fpdi\PdfReader {
    /**
     * A PDF reader class
     */
    class PdfReader
    {
        /**
         * @var PdfParser
         */
        protected $parser;
        /**
         * @var int
         */
        protected $pageCount;
        /**
         * Indirect objects of resolved pages.
         *
         * @var PdfIndirectObjectReference[]|PdfIndirectObject[]
         */
        protected $pages = [];
        /**
         * PdfReader constructor.
         *
         * @param PdfParser $parser
         */
        public function __construct(\setasign\Fpdi\PdfParser\PdfParser $parser) {}
        /**
         * PdfReader destructor.
         */
        public function __destruct() {}
        /**
         * Get the pdf parser instance.
         *
         * @return PdfParser
         */
        public function getParser() {}
        /**
         * Get the PDF version.
         *
         * @return string
         * @throws PdfParserException
         */
        public function getPdfVersion() {}
        /**
         * Get the page count.
         *
         * @return int
         * @throws PdfTypeException
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        public function getPageCount() {}
        /**
         * Get a page instance.
         *
         * @param int|numeric-string $pageNumber
         * @return Page
         * @throws PdfTypeException
         * @throws CrossReferenceException
         * @throws PdfParserException
         * @throws \InvalidArgumentException
         */
        public function getPage($pageNumber) {}
        /**
         * Walk the page tree and resolve all indirect objects of all pages.
         *
         * @param bool $readAll
         * @throws CrossReferenceException
         * @throws PdfParserException
         * @throws PdfTypeException
         */
        protected function readPages($readAll = false) {}
    }
    /**
     * An abstract class for page boundary constants and some helper methods
     */
    abstract class PageBoundaries
    {
        /**
         * MediaBox
         *
         * The media box defines the boundaries of the physical medium on which the page is to be printed.
         *
         * @see PDF 32000-1:2008 - 14.11.2 Page Boundaries
         * @var string
         */
        const MEDIA_BOX = 'MediaBox';
        /**
         * CropBox
         *
         * The crop box defines the region to which the contents of the page shall be clipped (cropped) when displayed or
         * printed.
         *
         * @see PDF 32000-1:2008 - 14.11.2 Page Boundaries
         * @var string
         */
        const CROP_BOX = 'CropBox';
        /**
         * BleedBox
         *
         * The bleed box defines the region to which the contents of the page shall be clipped when output in a
         * production environment.
         *
         * @see PDF 32000-1:2008 - 14.11.2 Page Boundaries
         * @var string
         */
        const BLEED_BOX = 'BleedBox';
        /**
         * TrimBox
         *
         * The trim box defines the intended dimensions of the finished page after trimming.
         *
         * @see PDF 32000-1:2008 - 14.11.2 Page Boundaries
         * @var string
         */
        const TRIM_BOX = 'TrimBox';
        /**
         * ArtBox
         *
         * The art box defines the extent of the page’s meaningful content (including potential white space) as intended
         * by the page’s creator.
         *
         * @see PDF 32000-1:2008 - 14.11.2 Page Boundaries
         * @var string
         */
        const ART_BOX = 'ArtBox';
        /**
         * All page boundaries
         *
         * @var array
         */
        public static $all = array(self::MEDIA_BOX, self::CROP_BOX, self::BLEED_BOX, self::TRIM_BOX, self::ART_BOX);
        /**
         * Checks if a name is a valid page boundary name.
         *
         * @param string $name The boundary name
         * @return boolean A boolean value whether the name is valid or not.
         */
        public static function isValidName($name) {}
    }
}

namespace setasign\Fpdi\PdfReader\DataStructure {
    /**
     * Class representing a rectangle
     */
    class Rectangle
    {
        /**
         * @var int|float
         */
        protected $llx;
        /**
         * @var int|float
         */
        protected $lly;
        /**
         * @var int|float
         */
        protected $urx;
        /**
         * @var int|float
         */
        protected $ury;
        /**
         * Create a rectangle instance by a PdfArray.
         *
         * @param PdfArray|mixed $array
         * @param PdfParser $parser
         * @return Rectangle
         * @throws PdfTypeException
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        public static function byPdfArray($array, \setasign\Fpdi\PdfParser\PdfParser $parser) {}
        public static function byVectors(\setasign\Fpdi\Math\Vector $ll, \setasign\Fpdi\Math\Vector $ur) {}
        /**
         * Rectangle constructor.
         *
         * @param float|int $ax
         * @param float|int $ay
         * @param float|int $bx
         * @param float|int $by
         */
        public function __construct($ax, $ay, $bx, $by) {}
        /**
         * Get the width of the rectangle.
         *
         * @return float|int
         */
        public function getWidth() {}
        /**
         * Get the height of the rectangle.
         *
         * @return float|int
         */
        public function getHeight() {}
        /**
         * Get the lower left abscissa.
         *
         * @return float|int
         */
        public function getLlx() {}
        /**
         * Get the lower left ordinate.
         *
         * @return float|int
         */
        public function getLly() {}
        /**
         * Get the upper right abscissa.
         *
         * @return float|int
         */
        public function getUrx() {}
        /**
         * Get the upper right ordinate.
         *
         * @return float|int
         */
        public function getUry() {}
        /**
         * Get the rectangle as an array.
         *
         * @return array
         */
        public function toArray() {}
        /**
         * Get the rectangle as a PdfArray.
         *
         * @return PdfArray
         */
        public function toPdfArray() {}
    }
}

namespace setasign\Fpdi\PdfReader {
    /**
     * Exception for the pdf reader class
     */
    class PdfReaderException extends \setasign\Fpdi\FpdiException
    {
        /**
         * @var int
         */
        const KIDS_EMPTY = 0x101;
        /**
         * @var int
         */
        const UNEXPECTED_DATA_TYPE = 0x102;
        /**
         * @var int
         */
        const MISSING_DATA = 0x103;
    }
    /**
     * Class representing a page of a PDF document
     */
    class Page
    {
        /**
         * @var PdfIndirectObject
         */
        protected $pageObject;
        /**
         * @var PdfDictionary
         */
        protected $pageDictionary;
        /**
         * @var PdfParser
         */
        protected $parser;
        /**
         * Inherited attributes
         *
         * @var null|array
         */
        protected $inheritedAttributes;
        /**
         * Page constructor.
         *
         * @param PdfIndirectObject $page
         * @param PdfParser $parser
         */
        public function __construct(\setasign\Fpdi\PdfParser\Type\PdfIndirectObject $page, \setasign\Fpdi\PdfParser\PdfParser $parser) {}
        /**
         * Get the indirect object of this page.
         *
         * @return PdfIndirectObject
         */
        public function getPageObject() {}
        /**
         * Get the dictionary of this page.
         *
         * @return PdfDictionary
         * @throws PdfParserException
         * @throws PdfTypeException
         * @throws CrossReferenceException
         */
        public function getPageDictionary() {}
        /**
         * Get a page attribute.
         *
         * @param string $name
         * @param bool $inherited
         * @return PdfType|null
         * @throws PdfParserException
         * @throws PdfTypeException
         * @throws CrossReferenceException
         */
        public function getAttribute($name, $inherited = true) {}
        /**
         * Get the rotation value.
         *
         * @return int
         * @throws PdfParserException
         * @throws PdfTypeException
         * @throws CrossReferenceException
         */
        public function getRotation() {}
        /**
         * Get a boundary of this page.
         *
         * @param string $box
         * @param bool $fallback
         * @return bool|Rectangle
         * @throws PdfParserException
         * @throws PdfTypeException
         * @throws CrossReferenceException
         * @see PageBoundaries
         */
        public function getBoundary($box = \setasign\Fpdi\PdfReader\PageBoundaries::CROP_BOX, $fallback = true) {}
        /**
         * Get the width and height of this page.
         *
         * @param string $box
         * @param bool $fallback
         * @return array|bool
         * @throws PdfParserException
         * @throws PdfTypeException
         * @throws CrossReferenceException
         */
        public function getWidthAndHeight($box = \setasign\Fpdi\PdfReader\PageBoundaries::CROP_BOX, $fallback = true) {}
        /**
         * Get the raw content stream.
         *
         * @return string
         * @throws PdfReaderException
         * @throws PdfTypeException
         * @throws FilterException
         * @throws PdfParserException
         */
        public function getContentStream() {}
        /**
         * Get information of all external links on this page.
         *
         * All coordinates are normalized in view to rotation and translation of the boundary-box, so that their
         * origin is lower-left.
         *
         * The URI is the binary value of the PDF string object. It can be in PdfDocEncoding or in UTF-16BE encoding.
         *
         * @return array
         */
        public function getExternalLinks($box = \setasign\Fpdi\PdfReader\PageBoundaries::CROP_BOX) {}
    }
}

namespace {
    class PDF extends \FPDF
    {
        protected $B = 0;
        protected $I = 0;
        protected $U = 0;
        protected $HREF = '';
        function WriteHTML($html) {}
        function OpenTag($tag, $attr) {}
        function CloseTag($tag) {}
        function SetStyle($tag, $enable) {}
        function PutLink($URL, $txt) {}
    }
    /*******************************************************************************
     * Class to parse and subset TrueType fonts                                     *
     *                                                                              *
     * Version: 1.11                                                                *
     * Date:    2021-04-18                                                          *
     * Author:  Olivier PLATHEY                                                     *
     *******************************************************************************/
    class TTFParser
    {
        protected $f;
        protected $tables;
        protected $numberOfHMetrics;
        protected $numGlyphs;
        protected $glyphNames;
        protected $indexToLocFormat;
        protected $subsettedChars;
        protected $subsettedGlyphs;
        public $chars;
        public $glyphs;
        public $unitsPerEm;
        public $xMin, $yMin, $xMax, $yMax;
        public $postScriptName;
        public $embeddable;
        public $bold;
        public $typoAscender;
        public $typoDescender;
        public $capHeight;
        public $italicAngle;
        public $underlinePosition;
        public $underlineThickness;
        public $isFixedPitch;
        function __construct($file) {}
        function __destruct() {}
        function Parse() {}
        function ParseOffsetTable() {}
        function ParseHead() {}
        function ParseHhea() {}
        function ParseMaxp() {}
        function ParseHmtx() {}
        function ParseLoca() {}
        function ParseGlyf() {}
        function ParseCmap() {}
        function ParseName() {}
        function ParseOS2() {}
        function ParsePost() {}
        function Subset($chars) {}
        function AddGlyph($id) {}
        function Build() {}
        function BuildCmap() {}
        function BuildHhea() {}
        function BuildHmtx() {}
        function BuildLoca() {}
        function BuildGlyf() {}
        function BuildMaxp() {}
        function BuildPost() {}
        function BuildFont() {}
        function LoadTable($tag) {}
        function SetTable($tag, $data) {}
        function Seek($tag) {}
        function Skip($n) {}
        function Read($n) {}
        function ReadUShort() {}
        function ReadShort() {}
        function ReadULong() {}
        function CheckSum($s) {}
        function Error($msg) {}
    }
}

namespace {
    /**
     * Uses localAPI GetInvoice to get the invoice balance.
     *
     * @see https://developers.whmcs.com/api-reference/getinvoice/
     *
     * @param integer $invoiceId
     * @param boolean $formatFriendly
     *
     * @return float|null
     */
    function getInvoiceBalance(int $invoiceId, bool $formatFriendly = \true): null|string|float {}
    /**
     * @param integer $invoiceId
     * @param boolean $formatFriendly
     *
     * @return null|string|float
     */
    function getInvoiceTotal(int $invoiceId, bool $formatFriendly = \true): null|string|float {}
    /**
     * @param integer $invoiceId
     * @param boolean $formatFriendly
     *
     * @return null|string|float
     */
    function getInvoiceSubtotal(int $invoiceId, bool $formatFriendly = \true): null|string|float {}
    /**
     * @param integer $invoiceId
     * @param float   $value
     *
     * @return string
     */
    function formatInvoiceValue(int $invoiceId, float $value): string {}
    /**
     * Gets the info from the tblcurrencies database table.
     *
     * @param integer $invoiceId
     */
    function getInvoiceCurrency(int $invoiceId): \stdClass {}
    /**
     * WHMCS format id are related to the formats as follow:
     *
     * 1 - 1234.56
     * 2 - 1,234.56
     * 3 - 1.234,56
     * 4 - 1,234
     *
     * @param integer $formatId
     *
     * @return array an array as: [decimal => '', thousands => ''].
     */
    function getCurrencySeparatorsFromFormatId(int $formatId): array {}
    function getClientFullNameByClientId(int $id): string {}
    function getClientFirstTwoNamesByClientId(int $id): string {}
    function getClientFirstNameByClientId(int $id): string {}
    function getClientEmailByClientId(int $clientId): string {}
    function getInvoicePdfUrlByInvocieId(int $id, bool $returnNullOtherwise = \false, bool $returnSystemPath = \false): ?string {}
    function getInvoiceImgUrlByInvoiceId(int $invoiceId, bool $returnSystemPath = \false): ?string {}
    /**
     * Line items "type" and "domain".
     *
     * @param integer $orderId
     *
     * @return string
     */
    function getOrderItemsDescripByOrderId(int $orderId): string {}
    function getInvoiceItemsByInvoiceId(int $invoiceId): string {}
    /**
     * Tries to get the items of the invoice first using getOrderItemsDescripByOrderId.
     * If an empty string is returned, the uses getInvoiceItemsByInvoiceId.
     *
     * @param integer $invoiceId
     *
     * @return string
     */
    function getItemsRelatedToInvoice(int $invoiceId): string {}
    /**
     * @param integer $invoiceId
     *
     * @return array [0 => Description 1, 1 => Description 2]
     */
    function getInvoiceItemsDescriptionsByInvoiceId(int $invoiceId): array {}
    function getInvoiceDueDateByInvoiceId(int $invoiceId): string {}
    function getServiceProductNameByProductId(int $productId): string {}
    function getHostDomainByHostId(int $hostId): string {}
    function getClientIdByInvoiceId(int $invoiceId): int {}
    function getClientIdByOrderId(int $orderId): int {}
    function getClientIdByModuleId(int $moduleId): int {}
    function getOrderIdByInvoiceId(int $invoiceId): ?int {}
    function getClientIdByTicketId(int $ticketId): ?int {}
    function getTicket(int $ticketId, string $column): string {}
    function getTicketMask(int $ticketId): string {}
    function getTicketSubject(int $ticketId): string {}
    function getTicketStatus(int $ticketId): string {}
    function getTicketEmail(int $ticketId): string {}
    function getTicketNameColumn(int $ticketId): string {}
    function getTicketWhatsAppCfValue(int $ticketId): ?int {}
    /**
     * @return array An array of items like: (
     *               [id] =>
     *               [type] =>
     *               [relid] =>
     *               [description] =>
     *               [amount] =>
     *               [taxed] =>
     *               [product_id] =>
     *               )
     *               Some items may not have a product_id since it must be a manually-added product or a taxe.
     */
    function getInvoiceItems(int $invoiceId) {}
    function systemUrl(): string {}
    function moduleUrl(): string {}
    function get_passsword_reset_url_for_user(string $email): string {}
    function get_user_password_reset_token_by_user_email(string $email): string {}
    /**
     * @return array{
     *     label: string,
     *     locale: string,
     *     locale_expanded: string,
     *     country_code: string
     * }
     */
    function lkn_hn_get_language_locales_for_view(): array {}
    function lkn_hn_get_client_countries_for_view() {}
    function lkn_hn_get_products_for_view(): array {}
    /**
     * @return array<array{label: string, value: string}>
     */
    function lkn_hn_get_client_custom_fields_for_view(): array {}
    function define_i18n_lang() {}
    /**
     * This should work for both PHP and Smarty templates.
     *
     * @param  array|string $text
     *
     * @return string returns $text if it is not found on the current language.
     */
    function lkn_hn_lang(array|string $text, array|\Smarty_Internal_Template $params = []): string {}
    function lkn_hn_log(string $action, array|object|string|null $request, array|object|string|null $response = '', array $masks = []) {}
    function lkn_hn_config(\Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings $setting) {}
    function lkn_hn_config_set(\Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms $platform, \Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings $setting, $value) {}
    function lkn_hn_result(string $code, mixed $data = \null, ?string $msg = \null, array $errors = []): \Lkn\HookNotification\Core\Shared\Infrastructure\Result {}
    function lkn_hn_get_system_locale(): string {}
    function lkn_hn_get_admin_root_url(string $resource = ''): string {}
    function lkn_hn_normalize_person_name(string $name): string {}
    function lkn_hn_remove_phone_number(string $value): string {}
    function lkn_hn_safe_json_encode(array $json, int $additionlFlags = 0) {}
    function lkn_hn_redirect_to_404(): void {}
    function lkn_hn_mask_value(string $contact): string {}
    function Message($txt, $severity = '') {}
    function Notice($txt) {}
    function Warning($txt) {}
    function Error($txt) {}
    function LoadMap($enc) {}
    function GetInfoFromTrueType($file, $embed, $subset, $map) {}
    function GetInfoFromType1($file, $embed, $map) {}
    function MakeFontDescriptor($info) {}
    function MakeWidthArray($widths) {}
    function MakeFontEncoding($map) {}
    function MakeUnicodeArray($map) {}
    function SaveToFile($file, $s, $mode) {}
    function MakeDefinitionFile($file, $type, $enc, $embed, $subset, $map, $info) {}
    function MakeFont($fontfile, $enc = 'cp1252', $embed = \true, $subset = \true) {}
}

namespace Lkn\HookNotification\Core\Shared\Infrastructure\Config {

    enum Settings: string
    {
        case WP_MSG_TEMPLATE_ASSOCS                 = 'msg_templates_assoc';
        case WP_MESSAGE_TEMPLATES                   = 'message_templates';
        case WP_CUSTOM_FIELD_ID_OLD                 = 'custom_field_id';
        case WP_BUSINESS_ACCOUNT_ID                 = 'business_account_id';
        case WP_PHONE_NUMBER_ID                     = 'business_phone_number_id';
        case WP_USER_ACCESS_TOKEN                   = 'user_access_token';
        case WP_SHOW_INVOICE_REMINDER_BTN_WHEN_PAID = 'show_invoice_reminder_btn';
        case WP_USE_TICKET_WHATSAPP_CF_WHEN_SET     = 'wp_use_ticket_whatsapp_cf_when_set';
        case WP_MSG_TEMPLATE_LANG                   = 'wp_msg_template_lang';
        case WP_VERSION                             = 'wp_api_version';
        case WP_META_ENABLE                         = 'wp_meta_enable';

        case CW_ACCOUNT_ID         = 'account_id';
        case CW_URL                = 'url';
        case CW_WHATSAPP_INBOX_ID  = 'wp_inbox_id';
        case CW_FACEBOOK_INBOX_ID  = 'fb_inbox_id';
        case CW_API_ACCESS_TOKEN   = 'api_access_token';
        case CW_LISTEN_WHATSAPP    = 'listen_wp';
        case CW_ACTIVE_NOTIFS      = 'active_notifs';
        case CW_ENABLE_LIVE_CHAT   = 'cw_enable_live_chat';
        case CW_LIVE_CHAT_SCRIPT   = 'cw_live_chat_script';
        case CW_WP_CUSTOM_FIELD_ID = 'cw_wp_custom_field_id';
    /**
         * This one is generated on DatabaseSetup.
         */
        case CW_CLIENT_IDENTIFIER_KEY          = 'cw_client_identifier_key';
        case CW_LIVE_CHAT_USER_IDENTITY_TOKEN  = 'cw_live_chat_user_identity_token';
        case CW_CLIENT_STATS_TO_SEND           = 'cw_live_chat_client_stats_to_send';
        case CW_CUSTOM_FIELDS_TO_SEND          = 'cw_live_chat_custom_fields_to_send';
        case CW_LIVE_CHAT_MODULE_ATTRS_TO_SEND = 'cw_live_chat_modules_attrs_to_send';
        case CW_ENABLED                        = 'enable_chatwoot';
        case CW_PRIVATE_NOTE_MODE              = 'cw_private_note_mode';

        case WP_EVO_ENABLE                    = 'enable_wp_evo';
        case WP_EVO_INSTANCE_NAME             = 'wp_evo_instance_name';
        case WP_EVO_API_URL                   = 'api_url';
        case WP_EVO_API_KEY                   = 'api_key';
        case WP_EVO_WP_NUMBER_CUSTOM_FIELD_ID = 'wp_number_custom_field_id';
        case WP_EVO_ACTIVE_NOTIFS             = 'active_wp_evo_notifs';

        case BAILEYS_ENABLE             = 'enable_baileys';
        case BAILEYS_ENDPOINT_URL       = 'baileys_endpoint_url';
        case BAILEYS_API_KEY            = 'baileys_api_key';
        case BAILEYS_WP_CUSTOM_FIELD_ID = 'baileys_wp_custom_field_id';

        case ENABLE_LOG                        = 'enable_log';
        case LKN_LICENSE                       = 'lkn_license';
        case DEFAULT_CLIENT_NAME               = 'default_client_name';
        case LATEST_VERSION                    = 'latest_version';
        case NEW_VERSION_DISMISS_ON_ADMIN_HOME = 'new_version_dismiss_on_admin_home';
        case DISMISS_INSTALLATION_WELCOME      = 'dismiss_installation_welcome';
        case OBJECT_PAGES_TO_SHOW_REPORTS      = 'object_pages_to_show_reports';
        case MODULE_PREVIOUS_VERSION           = 'mod_previous_version';
        case MODULE_DISMISS_V400_ALERT         = 'mod_dimiss_v400_alert';

        case TICKET_WP_CUSTOM_FIELD_ID = 'ticket_wp_custom_field_id';
        case WP_CUSTOM_FIELD_ID        = 'wp_custom_field_id';

        case LANGUAGE = 'language';

        case BULK_ENABLE = 'bulk_enable';

        case BD_CUSTOM_FIELD_ID = 'bd_custom_field_id';
    }

    enum Platforms: string
    {
        case WHATSAPP       = 'wp';
        case WP_EVO         = 'wp-evo';
        case BAILEYS        = 'baileys';
        case CHATWOOT       = 'cw';
        case MODULE         = 'mod';
        case BULK_MESSAGING = 'bulk';

        public function label(): string
        {
            return match ($this) {
                self::WHATSAPP => lkn_hn_lang('WhatsApp Meta'),
                self::CHATWOOT => lkn_hn_lang('Chatwoot'),
                self::WP_EVO => lkn_hn_lang('WhatsApp Evolution'),
                self::BAILEYS => lkn_hn_lang('WhatsApp Baileys'),
                self::MODULE => lkn_hn_lang('Module'),
                self::BULK_MESSAGING => lkn_hn_lang('Bulk Messaging'),
            };
        }
    }
}

namespace Lkn\HookNotification\Core\Shared\Infrastructure {
    enum Hooks: string
    {
        case ACCEPT_QUOTE                                    = 'AcceptQuote';
        case ADD_INVOICE_LATE_FEE                            = 'AddInvoiceLateFee';
        case ADD_INVOICE_PAYMENT                             = 'AddInvoicePayment';
        case ADD_TRANSACTION                                 = 'AddTransaction';
        case AFTER_INVOICING_GENERATE_INVOICE_ITEMS          = 'AfterInvoicingGenerateInvoiceItems';
        case CANCEL_AND_REFUND_ORDER                         = 'CancelAndRefundOrder';
        case INVOICE_CANCELLED                               = 'InvoiceCancelled';
        case INVOICE_CHANGE_GATEWAY                          = 'InvoiceChangeGateway';
        case INVOICE_CREATED                                 = 'InvoiceCreated';
        case INVOICE_CREATION                                = 'InvoiceCreation';
        case INVOICE_CREATION_PRE_EMAIL                      = 'InvoiceCreationPreEmail';
        case INVOICE_PAID                                    = 'InvoicePaid';
        case INVOICE_PAID_PRE_EMAIL                          = 'InvoicePaidPreEmail';
        case INVOICE_PAYMENT_REMINDER                        = 'InvoicePaymentReminder';
        case INVOICE_REFUNDED                                = 'InvoiceRefunded';
        case INVOICE_SPLIT                                   = 'InvoiceSplit';
        case INVOICE_UNPAID                                  = 'InvoiceUnpaid';
        case LOG_TRANSACTION                                 = 'LogTransaction';
        case MANUAL_REFUND                                   = 'ManualRefund';
        case PRE_INVOICING_GENERATE_INVOICE_ITEMS            = 'PreInvoicingGenerateInvoiceItems';
        case QUOTE_CREATED                                   = 'QuoteCreated';
        case QUOTE_STATUS_CHANGE                             = 'QuoteStatusChange';
        case UPDATE_INVOICE_TOTAL                            = 'UpdateInvoiceTotal';
        case VIEW_INVOICE_DETAILS_PAGE                       = 'ViewInvoiceDetailsPage';
        case ACCEPT_ORDER                                    = 'AcceptOrder';
        case ADDON_FRAUD                                     = 'AddonFraud';
        case AFTER_CALCULATE_CART_TOTALS                     = 'AfterCalculateCartTotals';
        case AFTER_FRAUD_CHECK                               = 'AfterFraudCheck';
        case AFTER_SHOPPING_CART_CHECKOUT                    = 'AfterShoppingCartCheckout';
        case CANCEL_ORDER                                    = 'CancelOrder';
        case CART_ITEMS_TAX                                  = 'CartItemsTax';
        case CART_SUBDOMAIN_VALIDATION                       = 'CartSubdomainValidation';
        case CART_TOTAL_ADJUSTMENT                           = 'CartTotalAdjustment';
        case DELETE_ORDER                                    = 'DeleteOrder';
        case FRAUD_CHECK_AWAITING_USER_INPUT                 = 'FraudCheckAwaitingUserInput';
        case FRAUD_CHECK_FAILED                              = 'FraudCheckFailed';
        case FRAUD_CHECK_PASSED                              = 'FraudCheckPassed';
        case FRAUD_ORDER                                     = 'FraudOrder';
        case ORDER_ADDON_PRICING_OVERRIDE                    = 'OrderAddonPricingOverride';
        case ORDER_DOMAIN_PRICING_OVERRIDE                   = 'OrderDomainPricingOverride';
        case ORDER_PAID                                      = 'OrderPaid';
        case ORDER_PRODUCT_PRICING_OVERRIDE                  = 'OrderProductPricingOverride';
        case ORDER_PRODUCT_UPGRADE_OVERRIDE                  = 'OrderProductUpgradeOverride';
        case OVERRIDE_ORDER_NUMBER_GENERATION                = 'OverrideOrderNumberGeneration';
        case PENDING_ORDER                                   = 'PendingOrder';
        case PRE_CALCULATE_CART_TOTALS                       = 'PreCalculateCartTotals';
        case PRE_FRAUD_CHECK                                 = 'PreFraudCheck';
        case PRE_SHOPPING_CART_CHECKOUT                      = 'PreShoppingCartCheckout';
        case RUN_FRAUD_CHECK                                 = 'RunFraudCheck';
        case SHOPPING_CART_CHECKOUT_COMPLETE_PAGE            = 'ShoppingCartCheckoutCompletePage';
        case SHOPPING_CART_VALIDATE_CHECKOUT                 = 'ShoppingCartValidateCheckout';
        case SHOPPING_CART_VALIDATE_DOMAIN                   = 'ShoppingCartValidateDomain';
        case SHOPPING_CART_VALIDATE_DOMAINS_CONFIG           = 'ShoppingCartValidateDomainsConfig';
        case SHOPPING_CART_VALIDATE_PRODUCT_UPDATE           = 'ShoppingCartValidateProductUpdate';
        case CANCELLATION_REQUEST                            = 'CancellationRequest';
        case PRE_SERVICE_EDIT                                = 'PreServiceEdit';
        case SERVICE_DELETE                                  = 'ServiceDelete';
        case SERVICE_EDIT                                    = 'ServiceEdit';
        case SERVICE_RECURRING_COMPLETED                     = 'ServiceRecurringCompleted';
        case AFTER_MODULE_CHANGE_PACKAGE                     = 'AfterModuleChangePackage';
        case AFTER_MODULE_CHANGE_PACKAGE_FAILED              = 'AfterModuleChangePackageFailed';
        case AFTER_MODULE_CHANGE_PASSWORD                    = 'AfterModuleChangePassword';
        case AFTER_MODULE_CHANGE_PASSWORD_FAILED             = 'AfterModuleChangePasswordFailed';
        case AFTER_MODULE_CREATE                             = 'AfterModuleCreate';
        case AFTER_MODULE_CREATE_FAILED                      = 'AfterModuleCreateFailed';
        case AFTER_MODULE_CUSTOM                             = 'AfterModuleCustom';
        case AFTER_MODULE_CUSTOM_FAILED                      = 'AfterModuleCustomFailed';
        case AFTER_MODULE_DEPROVISION_ADD_ON_FEATURE         = 'AfterModuleDeprovisionAddOnFeature';
        case AFTER_MODULE_DEPROVISION_ADD_ON_FEATURE_FAILED  = 'AfterModuleDeprovisionAddOnFeatureFailed';
        case AFTER_MODULE_PROVISION_ADD_ON_FEATURE           = 'AfterModuleProvisionAddOnFeature';
        case AFTER_MODULE_PROVISION_ADD_ON_FEATURE_FAILED    = 'AfterModuleProvisionAddOnFeatureFailed';
        case AFTER_MODULE_SUSPEND                            = 'AfterModuleSuspend';
        case AFTER_MODULE_SUSPEND_ADD_ON_FEATURE             = 'AfterModuleSuspendAddOnFeature';
        case AFTER_MODULE_SUSPEND_ADD_ON_FEATURE_FAILED      = 'AfterModuleSuspendAddOnFeatureFailed';
        case AFTER_MODULE_SUSPEND_FAILED                     = 'AfterModuleSuspendFailed';
        case AFTER_MODULE_TERMINATE                          = 'AfterModuleTerminate';
        case AFTER_MODULE_TERMINATE_FAILED                   = 'AfterModuleTerminateFailed';
        case AFTER_MODULE_UNSUSPEND                          = 'AfterModuleUnsuspend';
        case AFTER_MODULE_UNSUSPEND_ADD_ON_FEATURE           = 'AfterModuleUnsuspendAddOnFeature';
        case AFTER_MODULE_UNSUSPEND_ADD_ON_FEATURE_FAILED    = 'AfterModuleUnsuspendAddOnFeatureFailed';
        case AFTER_MODULE_UNSUSPEND_FAILED                   = 'AfterModuleUnsuspendFailed';
        case OVERRIDE_MODULE_USERNAME_GENERATION             = 'OverrideModuleUsernameGeneration';
        case PRE_MODULE_CHANGE_PACKAGE                       = 'PreModuleChangePackage';
        case PRE_MODULE_CHANGE_PASSWORD                      = 'PreModuleChangePassword';
        case PRE_MODULE_CREATE                               = 'PreModuleCreate';
        case PRE_MODULE_CUSTOM                               = 'PreModuleCustom';
        case PRE_MODULE_DEPROVISION_ADD_ON_FEATURE           = 'PreModuleDeprovisionAddOnFeature';
        case PRE_MODULE_PROVISION_ADD_ON_FEATURE             = 'PreModuleProvisionAddOnFeature';
        case PRE_MODULE_RENEW                                = 'PreModuleRenew';
        case PRE_MODULE_SUSPEND                              = 'PreModuleSuspend';
        case PRE_MODULE_SUSPEND_ADD_ON_FEATURE               = 'PreModuleSuspendAddOnFeature';
        case PRE_MODULE_TERMINATE                            = 'PreModuleTerminate';
        case PRE_MODULE_UNSUSPEND                            = 'PreModuleUnsuspend';
        case PRE_MODULE_UNSUSPEND_ADD_ON_FEATURE             = 'PreModuleUnsuspendAddOnFeature';
        case DOMAIN_DELETE                                   = 'DomainDelete';
        case DOMAIN_EDIT                                     = 'DomainEdit';
        case DOMAIN_TRANSFER_COMPLETED                       = 'DomainTransferCompleted';
        case DOMAIN_TRANSFER_FAILED                          = 'DomainTransferFailed';
        case DOMAIN_VALIDATION                               = 'DomainValidation';
        case PRE_DOMAIN_REGISTER                             = 'PreDomainRegister';
        case PRE_DOMAIN_TRANSFER                             = 'PreDomainTransfer';
        case PRE_REGISTRAR_REGISTER_DOMAIN                   = 'PreRegistrarRegisterDomain';
        case PRE_REGISTRAR_RENEW_DOMAIN                      = 'PreRegistrarRenewDomain';
        case PRE_REGISTRAR_TRANSFER_DOMAIN                   = 'PreRegistrarTransferDomain';
        case TOP_LEVEL_DOMAIN_ADD                            = 'TopLevelDomainAdd';
        case TOP_LEVEL_DOMAIN_DELETE                         = 'TopLevelDomainDelete';
        case TOP_LEVEL_DOMAIN_PRICING_UPDATE                 = 'TopLevelDomainPricingUpdate';
        case TOP_LEVEL_DOMAIN_UPDATE                         = 'TopLevelDomainUpdate';
        case AFTER_REGISTRAR_GET_CONTACT_DETAILS             = 'AfterRegistrarGetContactDetails';
        case AFTER_REGISTRAR_GET_DNS                         = 'AfterRegistrarGetDNS';
        case AFTER_REGISTRAR_GET_EPP_CODE                    = 'AfterRegistrarGetEPPCode';
        case AFTER_REGISTRAR_GET_NAMESERVERS                 = 'AfterRegistrarGetNameservers';
        case AFTER_REGISTRAR_REGISTER                        = 'AfterRegistrarRegister';
        case AFTER_REGISTRAR_REGISTRATION                    = 'AfterRegistrarRegistration';
        case AFTER_REGISTRAR_REGISTRATION_FAILED             = 'AfterRegistrarRegistrationFailed';
        case AFTER_REGISTRAR_RENEW                           = 'AfterRegistrarRenew';
        case AFTER_REGISTRAR_RENEWAL                         = 'AfterRegistrarRenewal';
        case AFTER_REGISTRAR_RENEWAL_FAILED                  = 'AfterRegistrarRenewalFailed';
        case AFTER_REGISTRAR_REQUEST_DELETE                  = 'AfterRegistrarRequestDelete';
        case AFTER_REGISTRAR_SAVE_CONTACT_DETAILS            = 'AfterRegistrarSaveContactDetails';
        case AFTER_REGISTRAR_SAVE_DNS                        = 'AfterRegistrarSaveDNS';
        case AFTER_REGISTRAR_SAVE_NAMESERVERS                = 'AfterRegistrarSaveNameservers';
        case AFTER_REGISTRAR_TRANSFER                        = 'AfterRegistrarTransfer';
        case AFTER_REGISTRAR_TRANSFER_FAILED                 = 'AfterRegistrarTransferFailed';
        case PRE_REGISTRAR_GET_CONTACT_DETAILS               = 'PreRegistrarGetContactDetails';
        case PRE_REGISTRAR_GET_DNS                           = 'PreRegistrarGetDNS';
        case PRE_REGISTRAR_GET_EPP_CODE                      = 'PreRegistrarGetEPPCode';
        case PRE_REGISTRAR_GET_NAMESERVERS                   = 'PreRegistrarGetNameservers';
        case PRE_REGISTRAR_REQUEST_DELETE                    = 'PreRegistrarRequestDelete';
        case PRE_REGISTRAR_SAVE_CONTACT_DETAILS              = 'PreRegistrarSaveContactDetails';
        case PRE_REGISTRAR_SAVE_DNS                          = 'PreRegistrarSaveDNS';
        case PRE_REGISTRAR_SAVE_NAMESERVERS                  = 'PreRegistrarSaveNameservers';
        case ADDON                                           = 'Addon';
        case ADDON_ACTIVATED                                 = 'AddonActivated';
        case ADDON_ACTIVATION                                = 'AddonActivation';
        case ADDON_ADD                                       = 'AddonAdd';
        case ADDON_CANCELLED                                 = 'AddonCancelled';
        case ADDON_CONFIG                                    = 'AddonConfig';
        case ADDON_CONFIG_SAVE                               = 'AddonConfigSave';
        case ADDON_DELETED                                   = 'AddonDeleted';
        case ADDON_EDIT                                      = 'AddonEdit';
        case ADDON_RENEWAL                                   = 'AddonRenewal';
        case ADDON_SUSPENDED                                 = 'AddonSuspended';
        case ADDON_TERMINATED                                = 'AddonTerminated';
        case ADDON_UNSUSPENDED                               = 'AddonUnsuspended';
        case AFTER_ADDON_UPGRADE                             = 'AfterAddonUpgrade';
        case LICENSING_ADDON_REISSUE                         = 'LicensingAddonReissue';
        case LICENSING_ADDON_VERIFY                          = 'LicensingAddonVerify';
        case PRODUCT_ADDON_DELETE                            = 'ProductAddonDelete';
        case AFTER_CLIENT_MERGE                              = 'AfterClientMerge';
        case CLIENT_ADD                                      = 'ClientAdd';
        case CLIENT_ALERT                                    = 'ClientAlert';
        case CLIENT_CHANGE_PASSWORD                          = 'ClientChangePassword';
        case CLIENT_CLOSE                                    = 'ClientClose';
        case CLIENT_DELETE                                   = 'ClientDelete';
        case CLIENT_DETAILS_VALIDATION                       = 'ClientDetailsValidation';
        case CLIENT_EDIT                                     = 'ClientEdit';
        case PRE_DELETE_CLIENT                               = 'PreDeleteClient';
        case USER_ADD                                        = 'UserAdd';
        case USER_CHANGE_PASSWORD                            = 'UserChangePassword';
        case USER_EDIT                                       = 'UserEdit';
        case USER_EMAIL_VERIFICATION_COMPLETE                = 'UserEmailVerificationComplete';
        case CONTACT_ADD                                     = 'ContactAdd';
        case CONTACT_DELETE                                  = 'ContactDelete';
        case CONTACT_DETAILS_VALIDATION                      = 'ContactDetailsValidation';
        case CONTACT_EDIT                                    = 'ContactEdit';
        case AFTER_PRODUCT_UPGRADE                           = 'AfterProductUpgrade';
        case PRODUCT_DELETE                                  = 'ProductDelete';
        case PRODUCT_EDIT                                    = 'ProductEdit';
        case SERVER_ADD                                      = 'ServerAdd';
        case SERVER_DELETE                                   = 'ServerDelete';
        case SERVER_EDIT                                     = 'ServerEdit';
        case ADMIN_AREA_VIEW_TICKET_PAGE                     = 'AdminAreaViewTicketPage';
        case ADMIN_AREA_VIEW_TICKET_PAGE_SIDEBAR             = 'AdminAreaViewTicketPageSidebar';
        case ADMIN_SUPPORT_TICKET_PAGE_PRE_TICKETS           = 'AdminSupportTicketPagePreTickets';
        case CLIENT_AREA_PAGE_SUBMIT_TICKET                  = 'ClientAreaPageSubmitTicket';
        case CLIENT_AREA_PAGE_SUPPORT_TICKETS                = 'ClientAreaPageSupportTickets';
        case CLIENT_AREA_PAGE_VIEW_TICKET                    = 'ClientAreaPageViewTicket';
        case SUBMIT_TICKET_ANSWER_SUGGESTIONS                = 'SubmitTicketAnswerSuggestions';
        case TICKET_ADD_NOTE                                 = 'TicketAddNote';
        case TICKET_ADMIN_REPLY                              = 'TicketAdminReply';
        case TICKET_CLOSE                                    = 'TicketClose';
        case TICKET_DELETE                                   = 'TicketDelete';
        case TICKET_DELETE_REPLY                             = 'TicketDeleteReply';
        case TICKET_DEPARTMENT_CHANGE                        = 'TicketDepartmentChange';
        case TICKET_FLAGGED                                  = 'TicketFlagged';
        case TICKET_MERGE                                    = 'TicketMerge';
        case TICKET_OPEN                                     = 'TicketOpen';
        case TICKET_OPEN_ADMIN                               = 'TicketOpenAdmin';
        case TICKET_OPEN_VALIDATION                          = 'TicketOpenValidation';
        case TICKET_PIPING                                   = 'TicketPiping';
        case TICKET_PRIORITY_CHANGE                          = 'TicketPriorityChange';
        case TICKET_SPLIT                                    = 'TicketSplit';
        case TICKET_STATUS_CHANGE                            = 'TicketStatusChange';
        case TICKET_SUBJECT_CHANGE                           = 'TicketSubjectChange';
        case TICKET_USER_REPLY                               = 'TicketUserReply';
        case TRANSLITERATE_TICKET_TEXT                       = 'TransliterateTicketText';
        case ANNOUNCEMENT_ADD                                = 'AnnouncementAdd';
        case ANNOUNCEMENT_EDIT                               = 'AnnouncementEdit';
        case FILE_DOWNLOAD                                   = 'FileDownload';
        case NETWORK_ISSUE_ADD                               = 'NetworkIssueAdd';
        case NETWORK_ISSUE_CLOSE                             = 'NetworkIssueClose';
        case NETWORK_ISSUE_DELETE                            = 'NetworkIssueDelete';
        case NETWORK_ISSUE_EDIT                              = 'NetworkIssueEdit';
        case NETWORK_ISSUE_REOPEN                            = 'NetworkIssueReopen';
        case CLIENT_LOGIN_SHARE                              = 'ClientLoginShare';
        case USER_LOGIN                                      = 'UserLogin';
        case USER_LOGOUT                                     = 'UserLogout';
        case CLIENT_AREA_DOMAIN_DETAILS                      = 'ClientAreaDomainDetails';
        case CLIENT_AREA_HOMEPAGE                            = 'ClientAreaHomepage';
        case CLIENT_AREA_HOMEPAGE_PANELS                     = 'ClientAreaHomepagePanels';
        case CLIENT_AREA_NAVBARS                             = 'ClientAreaNavbars';
        case CLIENT_AREA_PAGE                                = 'ClientAreaPage';
        case CLIENT_AREA_PAGE_ADD_CONTACT                    = 'ClientAreaPageAddContact';
        case CLIENT_AREA_PAGE_ADD_FUNDS                      = 'ClientAreaPageAddFunds';
        case CLIENT_AREA_PAGE_ADDON_MODULE                   = 'ClientAreaPageAddonModule';
        case CLIENT_AREA_PAGE_AFFILIATES                     = 'ClientAreaPageAffiliates';
        case CLIENT_AREA_PAGE_ANNOUNCEMENTS                  = 'ClientAreaPageAnnouncements';
        case CLIENT_AREA_PAGE_BANNED                         = 'ClientAreaPageBanned';
        case CLIENT_AREA_PAGE_BULK_DOMAIN_MANAGEMENT         = 'ClientAreaPageBulkDomainManagement';
        case CLIENT_AREA_PAGE_CANCELLATION                   = 'ClientAreaPageCancellation';
        case CLIENT_AREA_PAGE_CART                           = 'ClientAreaPageCart';
        case CLIENT_AREA_PAGE_CHANGE_PASSWORD                = 'ClientAreaPageChangePassword';
        case CLIENT_AREA_PAGE_CONFIGURE_SSL                  = 'ClientAreaPageConfigureSSL';
        case CLIENT_AREA_PAGE_CONTACT                        = 'ClientAreaPageContact';
        case CLIENT_AREA_PAGE_CONTACTS                       = 'ClientAreaPageContacts';
        case CLIENT_AREA_PAGE_CREDIT_CARD                    = 'ClientAreaPageCreditCard';
        case CLIENT_AREA_PAGE_CREDIT_CARD_CHECKOUT           = 'ClientAreaPageCreditCardCheckout';
        case CLIENT_AREA_PAGE_DOMAIN_ADDONS                  = 'ClientAreaPageDomainAddons';
        case CLIENT_AREA_PAGE_DOMAIN_CONTACTS                = 'ClientAreaPageDomainContacts';
        case CLIENT_AREA_PAGE_DOMAIN_DNS_MANAGEMENT          = 'ClientAreaPageDomainDNSManagement';
        case CLIENT_AREA_PAGE_DOMAIN_DETAILS                 = 'ClientAreaPageDomainDetails';
        case CLIENT_AREA_PAGE_DOMAIN_EPP_CODE                = 'ClientAreaPageDomainEPPCode';
        case CLIENT_AREA_PAGE_DOMAIN_EMAIL_FORWARDING        = 'ClientAreaPageDomainEmailForwarding';
        case CLIENT_AREA_PAGE_DOMAIN_REGISTER_NAMESERVERS    = 'ClientAreaPageDomainRegisterNameservers';
        case CLIENT_AREA_PAGE_DOMAINS                        = 'ClientAreaPageDomains';
        case CLIENT_AREA_PAGE_DOWNLOADS                      = 'ClientAreaPageDownloads';
        case CLIENT_AREA_PAGE_EMAILS                         = 'ClientAreaPageEmails';
        case CLIENT_AREA_PAGE_HOME                           = 'ClientAreaPageHome';
        case CLIENT_AREA_PAGE_INVOICES                       = 'ClientAreaPageInvoices';
        case CLIENT_AREA_PAGE_KNOWLEDGEBASE                  = 'ClientAreaPageKnowledgebase';
        case CLIENT_AREA_PAGE_LOGIN                          = 'ClientAreaPageLogin';
        case CLIENT_AREA_PAGE_LOGOUT                         = 'ClientAreaPageLogout';
        case CLIENT_AREA_PAGE_MASS_PAY                       = 'ClientAreaPageMassPay';
        case CLIENT_AREA_PAGE_NETWORK_ISSUES                 = 'ClientAreaPageNetworkIssues';
        case CLIENT_AREA_PAGE_PASSWORD_RESET                 = 'ClientAreaPagePasswordReset';
        case CLIENT_AREA_PAGE_PRODUCT_DETAILS                = 'ClientAreaPageProductDetails';
        case CLIENT_AREA_PAGE_PRODUCTS_SERVICES              = 'ClientAreaPageProductsServices';
        case CLIENT_AREA_PAGE_PROFILE                        = 'ClientAreaPageProfile';
        case CLIENT_AREA_PAGE_QUOTES                         = 'ClientAreaPageQuotes';
        case CLIENT_AREA_PAGE_REGISTER                       = 'ClientAreaPageRegister';
        case CLIENT_AREA_PAGE_SECURITY                       = 'ClientAreaPageSecurity';
        case CLIENT_AREA_PAGE_SERVER_STATUS                  = 'ClientAreaPageServerStatus';
        case CLIENT_AREA_PAGE_UNSUBSCRIBE                    = 'ClientAreaPageUnsubscribe';
        case CLIENT_AREA_PAGE_UPGRADE                        = 'ClientAreaPageUpgrade';
        case CLIENT_AREA_PAGE_VIEW_EMAIL                     = 'ClientAreaPageViewEmail';
        case CLIENT_AREA_PAGE_VIEW_INVOICE                   = 'ClientAreaPageViewInvoice';
        case CLIENT_AREA_PAGE_VIEW_QUOTE                     = 'ClientAreaPageViewQuote';
        case CLIENT_AREA_PAYMENT_METHODS                     = 'ClientAreaPaymentMethods';
        case CLIENT_AREA_PRIMARY_NAVBAR                      = 'ClientAreaPrimaryNavbar';
        case CLIENT_AREA_PRIMARY_SIDEBAR                     = 'ClientAreaPrimarySidebar';
        case CLIENT_AREA_PRODUCT_DETAILS                     = 'ClientAreaProductDetails';
        case CLIENT_AREA_PRODUCT_DETAILS_PRE_MODULE_TEMPLATE = 'ClientAreaProductDetailsPreModuleTemplate';
        case CLIENT_AREA_REGISTER                            = 'ClientAreaRegister';
        case CLIENT_AREA_SECONDARY_NAVBAR                    = 'ClientAreaSecondaryNavbar';
        case CLIENT_AREA_SECONDARY_SIDEBAR                   = 'ClientAreaSecondarySidebar';
        case CLIENT_AREA_SIDEBARS                            = 'ClientAreaSidebars';
        case ADMIN_AREA_CLIENT_SUMMARY_ACTION_LINKS          = 'AdminAreaClientSummaryActionLinks';
        case ADMIN_AREA_CLIENT_SUMMARY_PAGE                  = 'AdminAreaClientSummaryPage';
        case ADMIN_AREA_PAGE                                 = 'AdminAreaPage';
        case ADMIN_AREA_VIEW_QUOTE_PAGE                      = 'AdminAreaViewQuotePage';
        case ADMIN_CLIENT_DOMAINS_TAB_FIELDS                 = 'AdminClientDomainsTabFields';
        case ADMIN_CLIENT_DOMAINS_TAB_FIELDS_SAVE            = 'AdminClientDomainsTabFieldsSave';
        case ADMIN_CLIENT_FILE_UPLOAD                        = 'AdminClientFileUpload';
        case ADMIN_CLIENT_PROFILE_TAB_FIELDS                 = 'AdminClientProfileTabFields';
        case ADMIN_CLIENT_PROFILE_TAB_FIELDS_SAVE            = 'AdminClientProfileTabFieldsSave';
        case ADMIN_CLIENT_SERVICES_TAB_FIELDS                = 'AdminClientServicesTabFields';
        case ADMIN_CLIENT_SERVICES_TAB_FIELDS_SAVE           = 'AdminClientServicesTabFieldsSave';
        case ADMIN_HOMEPAGE                                  = 'AdminHomepage';
        case ADMIN_LOGIN                                     = 'AdminLogin';
        case ADMIN_LOGOUT                                    = 'AdminLogout';
        case ADMIN_PREDEFINED_ADDONS                         = 'AdminPredefinedAddons';
        case ADMIN_PRODUCT_CONFIG_FIELDS                     = 'AdminProductConfigFields';
        case ADMIN_PRODUCT_CONFIG_FIELDS_SAVE                = 'AdminProductConfigFieldsSave';
        case ADMIN_SERVICE_EDIT                              = 'AdminServiceEdit';
        case AUTH_ADMIN                                      = 'AuthAdmin';
        case AUTH_ADMIN_API                                  = 'AuthAdminApi';
        case INVOICE_CREATION_ADMIN_AREA                     = 'InvoiceCreationAdminArea';
        case PRE_ADMIN_SERVICE_EDIT                          = 'PreAdminServiceEdit';
        case VIEW_ORDER_DETAILS_PAGE                         = 'ViewOrderDetailsPage';
        case ADMIN_AREA_FOOTER_OUTPUT                        = 'AdminAreaFooterOutput';
        case ADMIN_AREA_HEAD_OUTPUT                          = 'AdminAreaHeadOutput';
        case ADMIN_AREA_HEADER_OUTPUT                        = 'AdminAreaHeaderOutput';
        case ADMIN_INVOICES_CONTROLS_OUTPUT                  = 'AdminInvoicesControlsOutput';
        case CLIENT_AREA_DOMAIN_DETAILS_OUTPUT               = 'ClientAreaDomainDetailsOutput';
        case CLIENT_AREA_FOOTER_OUTPUT                       = 'ClientAreaFooterOutput';
        case CLIENT_AREA_HEAD_OUTPUT                         = 'ClientAreaHeadOutput';
        case CLIENT_AREA_HEADER_OUTPUT                       = 'ClientAreaHeaderOutput';
        case CLIENT_AREA_PRODUCT_DETAILS_OUTPUT              = 'ClientAreaProductDetailsOutput';
        case FORMAT_DATE_FOR_CLIENT_AREA_OUTPUT              = 'FormatDateForClientAreaOutput';
        case FORMAT_DATE_TIME_FOR_CLIENT_AREA_OUTPUT         = 'FormatDateTimeForClientAreaOutput';
        case REPORT_VIEW_POST_OUTPUT                         = 'ReportViewPostOutput';
        case REPORT_VIEW_PRE_OUTPUT                          = 'ReportViewPreOutput';
        case SHOPPING_CART_CHECKOUT_OUTPUT                   = 'ShoppingCartCheckoutOutput';
        case SHOPPING_CART_CONFIGURE_PRODUCT_ADDONS_OUTPUT   = 'ShoppingCartConfigureProductAddonsOutput';
        case SHOPPING_CART_VIEW_CART_OUTPUT                  = 'ShoppingCartViewCartOutput';
        case AFTER_CRON_JOB                                  = 'AfterCronJob';
        case DAILY_CRON_JOB                                  = 'DailyCronJob';
        case DAILY_CRON_JOB_PRE_EMAIL                        = 'DailyCronJobPreEmail';
        case POP_EMAIL_COLLECTION_CRON_COMPLETED             = 'PopEmailCollectionCronCompleted';
        case POST_AUTOMATION_TASK                            = 'PostAutomationTask';
        case PRE_AUTOMATION_TASK                             = 'PreAutomationTask';
        case PRE_CRON_JOB                                    = 'PreCronJob';
        case AFFILIATE_ACTIVATION                            = 'AffiliateActivation';
        case AFFILIATE_CLICKTHRU                             = 'AffiliateClickthru';
        case AFFILIATE_COMMISSION                            = 'AffiliateCommission';
        case AFFILIATE_WITHDRAWAL_REQUEST                    = 'AffiliateWithdrawalRequest';
        case AFTER_CONFIG_OPTIONS_UPGRADE                    = 'AfterConfigOptionsUpgrade';
        case CC_UPDATE                                       = 'CCUpdate';
        case CALC_AFFILIATE_COMMISSION                       = 'CalcAffiliateCommission';
        case CUSTOM_FIELD_LOAD                               = 'CustomFieldLoad';
        case CUSTOM_FIELD_SAVE                               = 'CustomFieldSave';
        case EMAIL_PRE_LOG                                   = 'EmailPreLog';
        case EMAIL_PRE_SEND                                  = 'EmailPreSend';
        case EMAIL_TPL_MERGE_FIELDS                          = 'EmailTplMergeFields';
        case FETCH_CURRENCY_EXCHANGE_RATES                   = 'FetchCurrencyExchangeRates';
        case INTELLIGENT_SEARCH                              = 'IntelligentSearch';
        case LINK_TRACKER                                    = 'LinkTracker';
        case LOG_ACTIVITY                                    = 'LogActivity';
        case NOTIFICATION_PRE_SEND                           = 'NotificationPreSend';
        case PAY_METHOD_MIGRATION                            = 'PayMethodMigration';
        case PRE_EMAIL_SEND_REDUCE_RECIPIENTS                = 'PreEmailSendReduceRecipients';
        case PRE_UPGRADE_CHECKOUT                            = 'PreUpgradeCheckout';
        case PREMIUM_PRICE_OVERRIDE                          = 'PremiumPriceOverride';
        case PREMIUM_PRICE_RECALCULATION_OVERRIDE            = 'PremiumPriceRecalculationOverride';
        case LKN_HN_MANUAL                                   = 'LKNHNManual';
        case BULK                                            = 'LKNHNBulk';
    }
}
