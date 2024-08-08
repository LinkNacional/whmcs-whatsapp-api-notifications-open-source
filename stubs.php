<?php

namespace Lkn\HookNotification\Domains\Notifications {
    interface NotificationInterface
    {
        public function run(): bool;

        public function sendMessage(): array|bool;
    }
    /**
     * @since 3.0.0
     */
    abstract class AbstractNotification implements \Lkn\HookNotification\Domains\Notifications\NotificationInterface
    {
        /**
         * Must be the name of the notification folder.
         *
         * It is unique and has the purpose of identifying the notification.
         *
         * Sometimes may be equal to the $hook property, but it is has not the
         * meaning.
         *
         * @since 3.0.0
         * @var string
         */
        public string $notificationCode;
        /**
         * Must be the abbreviation of the platform for which the notification
         * was made.
         *
         * Must be 'cw' or 'wp'.
         *
         * @since 3.0.0
         * @var \Lkn\HookNotification\Config\Platforms
         */
        public \Lkn\HookNotification\Config\Platforms $platform;
        /**
         * If $platform is multi-channel like Chatwoot, then the channel can be specified here.
         *
         * @since 3.2.0
         * @var ?string
         */
        public ?string $channel = null;
        /**
         * The WHMCS hook name by which the notification is fired.
         *
         * Sometimes may be equal to the $notificationCode property, but it is not
         * the same.
         *
         * Provide a array of Hooks[] if the notification is needed to run on multiple hooks.
         *
         * @since 3.0.0
         * @var Hooks|Hooks[]|null
         * @link https://developers.whmcs.com/hooks/hook-index/
         */
        public \Lkn\HookNotification\Config\Hooks|array|null $hook = null;
        /**
         * The raw data that WHMCS passes to the notifications' hook.
         *
         * It is automatically filled when using the Messenger class.
         *
         * @since 3.0.0
         * @var array
         * @link https://developers.whmcs.com/hooks/hook-index/
         */
        public array $hookParams;
        /**
         * The translations for the current module language.
         *
         * The translations file is automatically loaded from the folder lang inside the notification's folder.
         *
         * @since 3.2.0
         * @var array
         */
        public array $lang;
        public string $notificationFolderPath;
        /**
         * The domain of the notification.
         *
         * This will be sent to the report.
         *
         * @since 3.2.0
         * @var ReportCategory
         */
        public \Lkn\HookNotification\Config\ReportCategory $reportCategory;
        /**
         * The unique ID of the notification domain.
         *
         * This may be a invoice id, a ticket id or a service id.
         *
         * @since 3.2.0
         * @var ?string
         */
        public ?int $reportCategoryId;
        /**
         * The client ID, if any, related to the notification.
         *
         * This ID is sent to the report.
         *
         * @since 3.2.0
         * @var int
         */
        public int $clientId;
        /**
         * If true, the Messenger class will automatically call $this->report() on success or on error.
         *
         * @since 3.2.0
         * @var bool
         */
        public bool $enableAutoReport = true;

        public function setReportCategoryId(int $reportCategoryId): void
        {
        }

        public function setReportCategory(\Lkn\HookNotification\Config\ReportCategory $reportCategory): void
        {
        }

        public function __construct()
        {
        }

        /**
         * Saves the notification sent or error status in the database
         *
         * Automatically saves the following properties:
         *
         * $this->platform
         *
         * $this->notificationCode
         *
         * $this->clientId
         *
         * $this->reportCategory
         *
         * $this->reportCategoryId
         *
         * $this->hook
         *
         * $this->channel
         *
         * These properties will appear in the Reports page.
         *
         * @since 3.2.0
         *
         * @param bool $success tells if the notification was sent or not.
         *
         * @return void
         */
        public function report(bool $success): void
        {
        }

        /**
         * For logs purposes.
         *
         * @since 3.0.0
         *
         * @return string "{$this->platform}:{$this->notificationCode}"
         */
        protected function getNotificationLogName(): string
        {
        }

        /**
         * @since 3.0.0
         *
         * @param array $hookParams the parameters provided by WHMCS. Each WHMCS hook
         *                          has its parameters: take a look at:
         *                          https://developers.whmcs.com/hooks/hook-index/
         */
        final public function setHookParams(array $hookParams): void
        {
        }

        protected function loadTranslations()
        {
        }
    }
}

namespace Lkn\HookNotification\Helpers {
    trait NotificationParamParseTrait
    {
        public static function getClientWhatsAppNumber(int $clientId): ?string
        {
        }

        /**
         * Uses localAPI GetInvoice to get the invoice balance.
         *
         * @see https://developers.whmcs.com/api-reference/getinvoice/
         *
         * @param int  $invoiceId
         * @param bool $formatFriendly
         *
         * @return float|null
         */
        public static function getInvoiceBalance(int $invoiceId, bool $formatFriendly = true): null|string|float
        {
        }

        /**
         * @param int  $invoiceId
         * @param bool $formatFriendly
         *
         * @return null|string|float
         */
        public static function getInvoiceTotal(int $invoiceId, bool $formatFriendly = true): null|string|float
        {
        }

        /**
         * @param int  $invoiceId
         * @param bool $formatFriendly
         *
         * @return null|string|float
         */
        public static function getInvoiceSubtotal(int $invoiceId, bool $formatFriendly = true): null|string|float
        {
        }

        /**
         * @param int   $invoiceId
         * @param float $value
         *
         * @return string
         */
        public static function formatInvoiceValue(int $invoiceId, float $value): string
        {
        }

        /**
         * Gets the info from the tblcurrencies database table.
         *
         * @param int $invoiceId
         */
        public static function getInvoiceCurrency(int $invoiceId): \stdClass
        {
        }

        /**
         * WHMCS format id are related to the formats as follow:
         *
         * 1 - 1234.56
         * 2 - 1,234.56
         * 3 - 1.234,56
         * 4 - 1,234
         *
         * @param int $formatId
         *
         * @return array an array as: [decimal => '', thousands => ''].
         */
        public static function getCurrencySeparatorsFromFormatId(int $formatId): array
        {
        }

        public static function getClientFullNameByClientId(int $id): string
        {
        }

        public static function getClientFirstTwoNamesByClientId(int $id): string
        {
        }

        public static function getClientFirstNameByClientId(int $id): string
        {
        }

        public static function getClientEmailByClientId(int $clientId): string
        {
        }

        public static function getInvoicePdfUrlByInvocieId(int $id, bool $returnNullOtherwise = false): ?string
        {
        }

        /**
         * Line items "type" and "domain".
         *
         * @since 2.0.0
         *
         * @param int $orderId
         *
         * @return string
         */
        public static function getOrderItemsDescripByOrderId(int $orderId): string
        {
        }

        /**
         * @since 3.0.1
         *
         * @param int $invoiceId
         *
         * @return array [0 => Description 1, 1 => Description 2]
         */
        public static function getInvoiceItemsDescriptionsByInvoiceId(int $invoiceId): array
        {
        }

        public static function getInvoiceDueDateByInvoiceId(int $invoiceId): string
        {
        }

        public static function getServiceProductNameByProductId(int $productId): string
        {
        }

        public function getHostDomainByHostId(int $hostId): string
        {
        }

        public function getClientIdByInvoiceId(int $invoiceId): int
        {
        }

        public function getClientIdByOrderId(int $orderId): int
        {
        }

        public function getOrderIdByInvoiceId(int $invoiceId): ?int
        {
        }

        public function getClientIdByTicketId(int $ticketId): ?int
        {
        }

        public function getTicket(int $ticketId, string $column): string
        {
        }

        public function getTicketMask(int $ticketId): string
        {
        }

        public function getTicketSubject(int $ticketId): string
        {
        }

        public function getTicketEmail(int $ticketId): string
        {
        }

        public function getTicketNameColumn(int $ticketId): string
        {
        }

        public function getTicketWhatsAppCfValue(int $ticketId): ?int
        {
        }

        /**
         * @since 2.0.0
         *
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
        public function getInvoiceItems(int $invoiceId)
        {
        }
    }
}

namespace Lkn\HookNotification\Domains\Platforms\Chatwoot {
    /**
     * Provides the basic implementation a Chatwoot notification must have.
     *
     * @since 3.0.0
     */
    abstract class AbstractChatwootNotification extends \Lkn\HookNotification\Domains\Notifications\AbstractNotification
    {
        use \Lkn\HookNotification\Helpers\NotificationParamParseTrait;
        /**
         * Notification's name.
         *
         * @since 3.0.0
         * @var string
         */
        public string $name;
        /**
         * @since 3.0.0
         * @var \Lkn\HookNotification\Domains\Shared\Repositories\ChatwootApiRepository
         */
        public readonly \Lkn\HookNotification\Domains\Shared\Repositories\ChatwootApiRepository $api;
        /**
         * @since 3.0.0
         * @var int
         */
        public int $clientId;
        public array $hookParams;

        public function __construct(array $hookParams = [])
        {
        }

        /**
         * @since 3.0.0
         *
         * @param int $clientId
         *
         * @return void
         */
        final public function setClientId(int $clientId): void
        {
        }
    }
}

namespace Lkn\HookNotification\Domains\Shared\Abstracts {
    /**
     * Provides a raw curl request method.
     *
     * @since 3.0.0
     */
    abstract class AbstractHttpRequest
    {
        /**
         * @since 3.0.0
         *
         * @param string $method
         * @param string $baseUrl
         * @param string $endpoint
         * @param array  $body
         * @param array  $headers
         *
         * @return false|null|array false may be returned also due to license problems.
         */
        protected function httpRequest(string $method, string $baseUrl, string $endpoint, array $body = [], array $headers = []): false|null|array
        {
        }
    }
}

namespace Lkn\HookNotification\Domains\Platforms\Chatwoot {
    /**
     * Implements raw methods for communicating with the API.
     *
     * Must only contain http requests to the API. Should not process the responses.
     *
     * @link https://www.chatwoot.com/developers/api/
     *
     * @since 3.0.0
     */
    abstract class AbstractChatwootApi extends \Lkn\HookNotification\Domains\Shared\Abstracts\AbstractHttpRequest
    {
        /**
         * Provides a standardized way to return API responses to notifications.
         *
         * @since 3.0.0
         *
         * @param bool  $success
         * @param array $data
         *
         * @return array
         */
        protected function response(bool $success, array $data = []): array
        {
        }

        /**
         * Performs a request to Chatwoot's API.
         *
         * @since 3.0.0
         *
         * @param string $method
         * @param string $endpoint
         * @param array  $body
         * @param array  $headers
         *
         * @return array raw API response converted to array or an empty array on failure.
         */
        final public function request(string $method, string $endpoint, array $body = [], array $headers = []): false|null|array
        {
        }

        /**
         * @since 3.0.0
         *
         * @param int    $conversationId
         * @param string $content
         * @param string $contentType
         * @param string $msgType        outgoing or incoming
         * @param bool   $private
         * @param array  $contentAttrs
         *
         * @link https://www.chatwoot.com/developers/api/#tag/Messages/operation/create-a-new-message-in-a-conversation
         *
         * @return array returns the message ID.
         */
        final public function sendMessageToConversation(int $conversationId, string $content, string $contentType = 'text', string $msgType = 'outgoing', bool $private = false, array $contentAttrs = []): array
        {
        }

        /**
         * @since 3.0.0
         *
         * @link https://www.chatwoot.com/developers/api/#tag/Contacts/operation/contactSearch
         *
         * @param string $searchQuery
         *
         * @return array
         */
        final public function searchContact(string $searchQuery): array
        {
        }

        /**
         * @since 3.0.0
         *
         * @link https://www.chatwoot.com/developers/api/#tag/Contacts/operation/contactConversations
         *
         * @param int $contactId
         *
         * @return array
         */
        final public function getContactConversations(int $contactId): array
        {
        }

        /**
         * @since 3.0.0
         *
         * @param int      $contactId
         * @param int|null $contactSourceId
         * @param int      $inboxId
         *
         * @return void
         */
        final public function createConversation(int $contactId, int|null $contactSourceId, int $inboxId)
        {
        }

        /**
         * @since 3.0.0
         *
         * @link https://www.chatwoot.com/developers/api/#tag/Contacts/operation/contactCreate
         *
         * @param int    $inboxId
         * @param string $name
         * @param string $email
         * @param string $phone
         *
         * @return array
         */
        final public function createContact(int $inboxId, string $name = '', string $email = '', string $phone = ''): array
        {
        }
    }
}

namespace Lkn\HookNotification\Domains\Platforms\WhatsApp {
    /**
     * @since 3.0.0
     */
    final class WhatsAppNotificationsEvents
    {
        use \Lkn\HookNotification\Helpers\NotificationParamParseTrait;

        /**
         * @since 3.0.0
         */
        public function __construct()
        {
        }

        /**
         * @since 3.0.0
         *
         * @param int    $clientId
         * @param string $msg
         *
         * @return void
         */
        public function sendMsgToChatwootAsPrivateNote(int $clientId, string $msg): void
        {
        }

        public function sendMsgToChatwootAsPrivateNoteForUnregisteredClient(int $phoneNumber, string $msg, string $clientName, string $clientEmail, int $inboxId): void
        {
        }
    }
    /**
     * Holds methods that used to comunicate with the WhatsApp business API and
     * cloud API.
     *
     * @since 3.0.0
     */
    abstract class AbstractWhatsAppApi extends \Lkn\HookNotification\Domains\Shared\Abstracts\AbstractHttpRequest
    {
        /**
         * Performs a request to WhatsApp API.
         *
         * @since 3.0.0
         *
         * @param string $method
         * @param string $endpoint
         * @param array  $body
         * @param array  $headers
         *
         * @return false|null|array raw WhatsApp response converted to array or an empty array on failure.
         */
        final public function request(string $method, string $endpoint, array $body = [], array $headers = []): false|null|array
        {
        }

        /**
         * @since 3.0.0
         *
         * @param string $method
         * @param string $endpoint
         * @param array  $body
         * @param array  $headers
         *
         * @link https://developers.facebook.com/docs/whatsapp/cloud-api/get-started
         *
         * @return false|null|array
         */
        final public function apiCloud(string $method, string $endpoint, array $body = [], array $headers = []): false|null|array
        {
        }

        /**
         * @since 3.0.0
         *
         * @param string $method
         * @param string $endpoint
         * @param array  $body
         * @param array  $headers
         *
         * @link https://developers.facebook.com/docs/whatsapp/business-management-api
         *
         * @return false|null|array
         */
        final public function apiBusiness(string $method, string $endpoint, array $body = [], array $headers = [], string $queryParams = ''): false|null|array
        {
        }
    }
    /**
     * @since 3.0.0
     */
    abstract class AbstractWhatsAppNotifcation extends \Lkn\HookNotification\Domains\Notifications\AbstractNotification
    {
        use \Lkn\HookNotification\Helpers\NotificationParamParseTrait;
        /**
         * The array of association between a message template and a notification.
         *
         * @since 3.0.0
         * @var array
         */
        protected array $assoc = [];
        /**
         * The relation between a message template parameter position and a
         * notification parameter.
         *
         * @since 3.0.0
         * @var array
         */
        public array $parameters;
        /**
         * Instance for communicating with the WhatsApp API.
         *
         * @since 3.0.0
         * @var \Lkn\HookNotification\Domains\Shared\Repositories\WhatsAppApiRepository
         */
        protected readonly \Lkn\HookNotification\Domains\Shared\Repositories\WhatsAppApiRepository $api;
        /**
         * Events instance for handling operations related to others platforms.
         *
         * A operation may be sending the message to Chatwoot.
         *
         * @since 3.0.0
         * @var \Lkn\HookNotification\Domains\Platforms\WhatsApp\WhatsAppNotificationsEvents
         */
        protected readonly \Lkn\HookNotification\Domains\Platforms\WhatsApp\WhatsAppNotificationsEvents $eventsInstance;
        /**
         * Instance of the class that maps the $this->assoc into the WhatsApp API
         * request body.
         *
         * @since 3.0.0
         * @var \Lkn\HookNotification\Domains\Platforms\WhatsApp\MessageTemplateParser
         */
        protected readonly \Lkn\HookNotification\Domains\Platforms\WhatsApp\MessageTemplateParser $parser;
        /**
         * Keys of which evets for WhatsApp to run.
         *
         * cw_private_note is removed when the OpenTicket notification is being run
         * for a client not registered.
         *
         * @since 3.2.0
         * @var array
         */
        public array $events = ['cw_private_note'];

        public function __construct()
        {
        }

        /**
         * Should set the parameters property.
         *
         * The array must follow the structure below:
         *
         * $this->parameters = [
         *      'invoice_id' => [
         *          'label' => 'ID da fatura',
         *          'parser' => fn () => $this->hookParams['invoiceid'],
         *      ]
         * ];
         *
         * @since 3.0.0
         *
         * @return void
         */
        protected function defineParameters(): void
        {
        }

        /**
         * Responsible for getting all information related to the notification.
         *
         * Gets the client WhatsApp number and the association between the
         * notification and message template.
         *
         * @since 3.0.0
         *
         * @return void
         */
        final public function init(): void
        {
        }

        /**
         * @since 3.0.0
         *
         * @param int $clientId
         *
         * @return void
         */
        protected function setClientId(int $clientId): void
        {
        }

        /**
         * Parses and sends the message.
         *
         * Useful for simple notification that does not require too much customizations.
         *
         * @since 3.0.0
         *
         * @param ?int $whatsappNumber
         *
         * @return array|false the WhatsApp API response.
         */
        final public function sendMessage(?int $whatsappNumber = null): array|false
        {
        }
    }
    /**
     * Maps the association between a message template and a notification into
     * the WhatsApp request body required to send the message to the API.
     *
     * Knows the rules of the API.
     *
     * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages
     *
     * @since 1.0.0
     */
    final class MessageTemplateParser
    {
        /**
         * @since 3.0.0
         *
         * @param array $components  the association between the notification
         *                           parameters and the message template.
         * @param array $notifParams the notification parameters, containing a
         *                           callback for fetching the parameter value.
         *
         * @return array
         */
        final public function parse(array $components, array $notifParams): array
        {
        }
    }
}

namespace Lkn\HookNotification\Domains\Notifications {
    /**
     * @since 3.0.0
     */
    abstract class Messenger
    {
        /**
         * Runs the notification when its hook is called by WHMCS.
         *
         * @since 3.0.0
         *
         * @param string    $notificationNamespace ClassNotification::class
         * @param ?callable $condition             Optional. Must return true or false. When true, the notification is run when false, the notification is not run.
         *
         * @return void
         */
        final public static function run(string $notificationNamespace, ?callable $condition = null): void
        {
        }

        /**
         * @since 3.2.0
         *
         * Does the required verifications then run the notification.
         *
         * @param string                                                        $notificationNamespace
         * @param array                                                         $hookParams
         * @param callable|null                                                 $condition
         * @param AbstractWhatsAppNotifcation|AbstractChatwootNotification|null $instance
         *
         * @return bool
         */
        public static function runNow(string $notificationNamespace, array $hookParams = [], ?callable $condition = null, \Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation|\Lkn\HookNotification\Domains\Platforms\Chatwoot\AbstractChatwootNotification|null $instance = null): bool
        {
        }

        /**
         * @since 3.2.0
         *
         * @param string                                 $notifCode
         * @param \Lkn\HookNotification\Config\Platforms $platform
         * @param array                                  $hookParams
         * @param callable|null                          $condition
         *
         * @return array [sent => true|false] or, when an error [sent => true|false, msg => string].
         */
        public static function runManually(string $notifCode, \Lkn\HookNotification\Config\Platforms $platform, array $hookParams, ?callable $condition = null): array
        {
        }
    }
    final class UnableToRunNotificationException extends \Exception
    {
        public readonly array $context;

        public function __construct(string $message, array $context)
        {
        }

        // custom string representation of object
        public function __toString()
        {
        }
    }
}

namespace Lkn\HookNotification\Helpers {
    abstract class Logger
    {
        final public static function log(string $action, array|object|string|null $request, array|object|string|null $response = ''): void
        {
        }

        /**
         * Creates a report of the message for displaying in the reports page, in
         * the module reports page.
         *
         * @since 3.0.0
         *
         * @param string                                          $status
         * @param \Lkn\HookNotification\Config\Platforms          $platform
         * @param string                                          $notification
         * @param ?int                                            $clientId
         * @param ReportCategory                                  $object
         * @param int                                             $objectId
         * @param \Lkn\HookNotification\Config\Hooks|Hooks[]|null $hook
         * @param string|null                                     $channel
         *
         * @return void
         */
        final public static function report(string $status, \Lkn\HookNotification\Config\Platforms $platform, string $notification, ?int $clientId, ?\Lkn\HookNotification\Config\ReportCategory $category, ?int $categoryId, \Lkn\HookNotification\Config\Hooks|array|null $hook = null, ?string $channel = null): void
        {
        }
    }
    final class ExtractHeaderDocBlockFromFile
    {
        public static function run(string $path): ?array
        {
        }
    }
    final class View
    {
        public static function baseRender(string $viewPath, array $vars = []): string
        {
        }

        public static function render(string $view, array $vars = [], string $viewRootPath = ''): string
        {
        }

        public static function addNotificationOption(string $notificationNamespace): string
        {
        }

        public static function renderWithFeedback(string $view, string $type, string $msg, bool $blockPage = false): string
        {
        }

        public static function error500(string $view, string $msg)
        {
        }

        public static function error404(string $view, string $msg)
        {
        }

        public static function withValidationErrors(string $view, array $errors = [], string $msg, bool $blockPage = false): string
        {
        }
    }
    /**
     * Provides methods for fast access to the module settings.
     *
     * @since 3.0.0
     */
    abstract class Config
    {
        /**
         * Use this method to get a module setting.
         *
         * @since 3.0.0
         *
         * @param \Lkn\HookNotification\Config\Platforms $platform
         * @param \Lkn\HookNotification\Config\Settings  $setting
         *
         * @return mixed Returns a setting from the table mod_lkn_hook_notification_configs.
         */
        final public static function get(\Lkn\HookNotification\Config\Platforms $platform, \Lkn\HookNotification\Config\Settings $setting): mixed
        {
        }

        final public static function set(\Lkn\HookNotification\Config\Platforms $platform, \Lkn\HookNotification\Config\Settings $setting, mixed $value): void
        {
        }

        /**
         * Checks if a report for a object can be show according to its setting.
         *
         * @since 3.0.0
         *
         * @param string $object
         *
         * @return bool
         */
        final public static function isReportPageActive(string $object): bool
        {
        }

        final public static function getConstant(string $constant): mixed
        {
        }
    }
    final class Lang
    {
        /**
         * Returns the array of translations for the current language.
         *
         * If the module has no language set, the default is of the WHMCS.
         *
         * If the default WHMCS language is not disponible, then the default language is english.
         *
         * @since 3.2.0
         *
         * @return object
         */
        public static function getModuleLangAsObject(): object
        {
        }

        public static function text(string $key)
        {
        }

        public static function getCurrentLanguageName(): string
        {
        }

        public static function getNotificationLang(string $notifFolderPath, bool $asObject = false): array|\stdClass
        {
        }
    }
    /**
     * @since 2.0.0
     */
    abstract class Link
    {
        /**
         * @since 2.0.0
         *
         * @return string
         */
        final public static function systemUrl()
        {
        }

        /**
         * @since 2.0.0
         *
         * @return string
         */
        final public static function moduleUrl()
        {
        }

        final public static function make(string $controller, string $resource, array $params = []): string
        {
        }
    }
    abstract class Response
    {
        final public static function api(bool $success, array $data = []): void
        {
        }

        final public static function redirect(string $controller, string $resource, array $params = []): void
        {
        }
    }
    abstract class VersionUpgrade
    {
        final public static function setLatestVersion(string $version): void
        {
        }

        final public static function setDismissOnAdminHome(bool $dismiss): void
        {
        }

        final public static function getNewVersion(): ?string
        {
        }

        final public static function getDismissNewVersionAlert(): ?bool
        {
        }

        final public static function requestLatestVersion(): ?string
        {
        }
    }
    abstract class Formatter
    {
        /**
         * @since 2.0.0
         * @see https://stackoverflow.com/a/40081879/16530764
         *
         * @param array $array
         *
         * @return array
         */
        final public static function stripTagsArray(array $array): array
        {
        }

        final public static function removeNonNumber(string $value): string
        {
        }

        final public static function normalizePersonName(string $name): string
        {
        }
    }
    final class WhmcsApi
    {
        /**
         * Returns the custom fields labels and their ids like [id => , label => ].
         *
         * @since 3.2.0
         *
         * @param bool $onlyForClients
         *
         * @return array
         */
        public static function getCustomFieldsLabels(bool $onlyForClients = true): array
        {
        }

        /**
         * @since 3.2.0
         *
         * @param string $resource the piece of url to append to the end of the URL. No need to add initial /.
         *
         * @return string
         */
        public static function getAdminRootUrl(string $resource = ''): string
        {
        }
    }
}

namespace Lkn\HookNotification\Domains\Shared\Abstracts {
    /**
     * Provides useful methods to the Requests classes that uses CakePHP for
     * validating the fields.
     *
     * @since 3.0.0
     */
    abstract class AbstractRequest
    {
        /**
         * @since 3.0.0
         * @var array|false
         */
        public array|bool $errors = false;

        /**
         * @since 3.0.0
         *
         * @return array
         */
        protected function getRequestData(): array
        {
        }

        /**
         * @since 3.0.0
         *
         * @param array $array
         *
         * @return array
         */
        protected function flattenErrorsArray(array $array): array
        {
        }

        /**
         * @since 3.0.0
         * @see https://stackoverflow.com/a/40081879/16530764
         *
         * @param array $array
         *
         * @return array
         */
        protected function stripTagsArray(array $array): array
        {
        }
    }
    /**
     * @since 3.0.0
     */
    abstract class AbstractController
    {
        /**
         * Used be controllers to return the operation response as array.
         *
         * @since 3.0.0
         *
         * @param bool  $success
         * @param array $body
         *
         * @return void
         */
        protected function response(bool $success, array $body = [])
        {
        }

        /**
         * Used for controllers method to return the response as JSON.
         *
         * @since 3.0.0
         *
         * @param bool  $success
         * @param array $body
         *
         * @return void
         */
        protected function apiResponse(bool $success, array $body = []): void
        {
        }

        /**
         * Gets the request data for the endpoint.
         *
         * @since 3.0.0
         *
         * @return array
         */
        protected static function request(): array
        {
        }
    }
}

namespace Lkn\HookNotification\Domains\Shared\Repositories {
    /**
     * Holds methods that used to comunicate with the Chatwoot API.
     *
     * @since 3.0.0
     */
    final class ChatwootApiRepository extends \Lkn\HookNotification\Domains\Platforms\Chatwoot\AbstractChatwootApi
    {
        use \Lkn\HookNotification\Helpers\NotificationParamParseTrait;

        /**
         * Searchs by the client in Chatwoot and sends it the message.
         *
         * If necessary, the method creates a new contact and a new open conversation.
         *
         * @since 3.0.0
         *
         * @param int    $clientId
         * @param int    $inboxId
         * @param int    $searchBy
         * @param string $message
         * @param bool   $private
         *
         * @return array
         */
        final public function sendMessageToClient(int $clientId, int $inboxId, int $searchBy, string $message, bool $private = false): array
        {
        }

        /**
         * @since 3.0.0
         *
         * @param string $searchQuery
         *
         * @return array
         */
        public function searchContactAndGetItsIdAndItsInboxesSourceId(string $searchQuery): array
        {
        }

        /**
         * @since 3.0.0
         *
         * @param array $contactInboxes must be equal to the contact_inboxes
         *                              returned by the API.
         *
         * @return array [{inbox_id} => {source_id}, ...]
         */
        public function getSourceIdsByInboxIds(array $contactInboxes): array
        {
        }

        /**
         * Search for an open conversation for the inbox ID of WhatsApp.
         *
         * @since 3.0.0
         *
         * @param int $contactId
         * @param int $inboxId
         *
         * @return array
         */
        final public function searchForContactOpenConversationByInboxId(int $contactId, int $inboxId): array
        {
        }
    }
    /**
     * Holds methods that used to comunicate with the WhatsApp business API.
     *
     * @since 3.0.0
     */
    final class WhatsAppApiRepository extends \Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppApi
    {
        /**
         * @since 3.0.0
         *
         * @link https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates/#retrieve-templates
         *
         * @return array
         */
        public function getMessageTemplates(string $params = ''): array
        {
        }

        /**
         * @since 3.1.0
         *
         * @param string $templateName
         * @param string $params       default params are fields=name,components,status&status=APPROVED.
         *
         * @return array
         */
        public function getMessageTemplate(string $templateName, string $params = 'fields=name,components,status&status=APPROVED'): array
        {
        }
    }
    /**
     * @since 3.0.0
     */
    abstract class AbstractRepository
    {
        protected string $table;

        /**
         * @since 3.0.0
         *
         * @return \Illuminate\Database\Query\Builder
         */
        protected function query(): \Illuminate\Database\Query\Builder
        {
        }

        protected function success(?array $data = null, string $msg = ''): array
        {
        }

        protected function failure(string $msg = '', ?array $data = null): array
        {
        }
    }
    /**
     * Holds raw database operations for the table of the module settings:
     * mod_lkn_hook_notification_configs.
     *
     * @since 3.0.0
     */
    abstract class AbstractSettingsRepository extends \Lkn\HookNotification\Domains\Shared\Repositories\AbstractRepository
    {
        /**
         * @since 3.0.0
         * @var \Lkn\HookNotification\Config\Platforms
         */
        protected \Lkn\HookNotification\Config\Platforms $platform;
        /**
         * @since 3.0.0
         * @var string
         */
        protected string $table = 'mod_lkn_hook_notification_configs';

        /**
         * @since 3.0.0
         *
         * @param array $value
         *
         * @return string
         */
        public function encodeJson(array $value): string
        {
        }

        /**
         * @since 3.0.0
         *
         * @param string $value
         *
         * @return array
         */
        public function decodeJson(string $value): array|bool|null
        {
        }

        /**
         * @since 3.0.0
         *
         * @param \Lkn\HookNotification\Config\Settings $setting
         * @param string                                $value
         *
         * @return bool
         */
        public function update(\Lkn\HookNotification\Config\Settings $setting, string $value): bool
        {
        }

        /**
         * @since 3.0.0
         *
         * @param \Lkn\HookNotification\Config\Settings $setting
         *
         * @return string|null
         */
        public function getSetting(\Lkn\HookNotification\Config\Settings $setting): ?string
        {
        }

        /**
         * @since 3.0.0
         *
         * @param \Lkn\HookNotification\Config\Settings[] $settings
         *
         * @return array
         */
        protected function getSettings(array $settings): array
        {
        }

        /**
         * @since 3.0.0
         *
         * @param array $newValuesPerSetting [Settings => new_value, ...]
         *
         * @return bool
         */
        protected function updateSettings(array $newValuesPerSetting): bool
        {
        }
    }
}

namespace {
    function lknhooknotification_run_wp_events(bool $wasNotificationSent, \Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation|\Lkn\HookNotification\Domains\Platforms\Chatwoot\AbstractChatwootNotification $instance)
    {
    }
}

namespace Lkn\HookNotification\Config {
    enum Settings: string
    {
        case WP_MSG_TEMPLATE_ASSOCS = 'msg_templates_assoc';
        case WP_MESSAGE_TEMPLATES = 'message_templates';
        case WP_CUSTOM_FIELD_ID = 'custom_field_id';
        case WP_BUSINESS_ACCOUNT_ID = 'business_account_id';
        case WP_PHONE_NUMBER_ID = 'phone_number_id';
        case WP_USER_ACCESS_TOKEN = 'user_access_token';
        case WP_SHOW_INVOICE_REMINDER_BTN_WHEN_PAID = 'show_invoice_reminder_btn';
        case WP_USE_TICKET_WHATSAPP_CF_WHEN_SET = 'wp_use_ticket_whatsapp_cf_when_set';

        case CW_ACCOUNT_ID = 'account_id';
        case CW_URL = 'url';
        case CW_WHATSAPP_INBOX_ID = 'wp_inbox_id';
        case CW_FACEBOOK_INBOX_ID = 'fb_inbox_id';
        case CW_API_ACCESS_TOKEN = 'api_access_token';
        case CW_LISTEN_WHATSAPP = 'listen_wp';
        case CW_ACTIVE_NOTIFS = 'active_notifs';
        case CW_ENABLE_LIVE_CHAT = 'cw_enable_live_chat';
        case CW_LIVE_CHAT_SCRIPT = 'cw_live_chat_script';
        case CW_CLIENT_IDENTIFIER_KEY = 'cw_client_identifier_key';
        case CW_LIVE_CHAT_USER_IDENTITY_TOKEN = 'cw_live_chat_user_identity_token';
        case CW_CLIENT_STATS_TO_SEND = 'cw_live_chat_client_stats_to_send';
        case CW_CUSTOM_FIELDS_TO_SEND = 'cw_live_chat_custom_fields_to_send';
        case CW_LIVE_CHAT_MODULE_ATTRS_TO_SEND = 'cw_live_chat_modules_attrs_to_send';

        case ENABLE_LOG = 'enable_log';
        case LKN_LICENSE = 'lkn_license';
        case DEFAULT_CLIENT_NAME = 'default_client_name';
        case LATEST_VERSION = 'latest_version';
        case NEW_VERSION_DISMISS_ON_ADMIN_HOME = 'new_version_dismiss_on_admin_home';
        case DISMISS_INSTALLATION_WELCOME = 'dismiss_installation_welcome';
        case OBJECT_PAGES_TO_SHOW_REPORTS = 'object_pages_to_show_reports';

        case LANGUAGE = 'language';
    }

    /**
     * Used for specifying the domain of the notification for reporting puporses.
     *
     * @since  3.2.0
     */
    enum ReportCategory: string
    {
        case INVOICE = 'invoice';
        case TICKET = 'ticket';
        case SERVICE = 'service';
        case ORDER = 'order';
        case DOMAIN = 'domain';
    }

    /**
    * @since 1.0.0
    */
    enum Platforms: string
    {
        case WHATSAPP = 'wp';
        case CHATWOOT = 'cw';

        /**
         * Refers to the module itself.
         */
        case MODULE = 'mod';
        case ALL = 'all';

        public function value(): string
        {
            return strtolower($this->value);
        }

        public function label(): string
        {
            return ucfirst($this->value());
        }

        public function folderName(): string
        {
            return match ($this->value) {
                self::WHATSAPP->value => 'WhatsApp',
                self::CHATWOOT->value => 'Chatwoot'
            };
        }
    }

    enum Hooks: string
    {
        case ACCEPT_QUOTE = 'AcceptQuote';
        case ADD_INVOICE_LATE_FEE = 'AddInvoiceLateFee';
        case ADD_INVOICE_PAYMENT = 'AddInvoicePayment';
        case ADD_TRANSACTION = 'AddTransaction';
        case AFTER_INVOICING_GENERATE_INVOICE_ITEMS = 'AfterInvoicingGenerateInvoiceItems';
        case CANCEL_AND_REFUND_ORDER = 'CancelAndRefundOrder';
        case INVOICE_CANCELLED = 'InvoiceCancelled';
        case INVOICE_CHANGE_GATEWAY = 'InvoiceChangeGateway';
        case INVOICE_CREATED = 'InvoiceCreated';
        case INVOICE_CREATION = 'InvoiceCreation';
        case INVOICE_CREATION_PRE_EMAIL = 'InvoiceCreationPreEmail';
        case INVOICE_PAID = 'InvoicePaid';
        case INVOICE_PAID_PRE_EMAIL = 'InvoicePaidPreEmail';
        case INVOICE_PAYMENT_REMINDER = 'InvoicePaymentReminder';
        case INVOICE_REFUNDED = 'InvoiceRefunded';
        case INVOICE_SPLIT = 'InvoiceSplit';
        case INVOICE_UNPAID = 'InvoiceUnpaid';
        case LOG_TRANSACTION = 'LogTransaction';
        case MANUAL_REFUND = 'ManualRefund';
        case PRE_INVOICING_GENERATE_INVOICE_ITEMS = 'PreInvoicingGenerateInvoiceItems';
        case QUOTE_CREATED = 'QuoteCreated';
        case QUOTE_STATUS_CHANGE = 'QuoteStatusChange';
        case UPDATE_INVOICE_TOTAL = 'UpdateInvoiceTotal';
        case VIEW_INVOICE_DETAILS_PAGE = 'ViewInvoiceDetailsPage';
        case ACCEPT_ORDER = 'AcceptOrder';
        case ADDON_FRAUD = 'AddonFraud';
        case AFTER_CALCULATE_CART_TOTALS = 'AfterCalculateCartTotals';
        case AFTER_FRAUD_CHECK = 'AfterFraudCheck';
        case AFTER_SHOPPING_CART_CHECKOUT = 'AfterShoppingCartCheckout';
        case CANCEL_ORDER = 'CancelOrder';
        case CART_ITEMS_TAX = 'CartItemsTax';
        case CART_SUBDOMAIN_VALIDATION = 'CartSubdomainValidation';
        case CART_TOTAL_ADJUSTMENT = 'CartTotalAdjustment';
        case DELETE_ORDER = 'DeleteOrder';
        case FRAUD_CHECK_AWAITING_USER_INPUT = 'FraudCheckAwaitingUserInput';
        case FRAUD_CHECK_FAILED = 'FraudCheckFailed';
        case FRAUD_CHECK_PASSED = 'FraudCheckPassed';
        case FRAUD_ORDER = 'FraudOrder';
        case ORDER_ADDON_PRICING_OVERRIDE = 'OrderAddonPricingOverride';
        case ORDER_DOMAIN_PRICING_OVERRIDE = 'OrderDomainPricingOverride';
        case ORDER_PAID = 'OrderPaid';
        case ORDER_PRODUCT_PRICING_OVERRIDE = 'OrderProductPricingOverride';
        case ORDER_PRODUCT_UPGRADE_OVERRIDE = 'OrderProductUpgradeOverride';
        case OVERRIDE_ORDER_NUMBER_GENERATION = 'OverrideOrderNumberGeneration';
        case PENDING_ORDER = 'PendingOrder';
        case PRE_CALCULATE_CART_TOTALS = 'PreCalculateCartTotals';
        case PRE_FRAUD_CHECK = 'PreFraudCheck';
        case PRE_SHOPPING_CART_CHECKOUT = 'PreShoppingCartCheckout';
        case RUN_FRAUD_CHECK = 'RunFraudCheck';
        case SHOPPING_CART_CHECKOUT_COMPLETE_PAGE = 'ShoppingCartCheckoutCompletePage';
        case SHOPPING_CART_VALIDATE_CHECKOUT = 'ShoppingCartValidateCheckout';
        case SHOPPING_CART_VALIDATE_DOMAIN = 'ShoppingCartValidateDomain';
        case SHOPPING_CART_VALIDATE_DOMAINS_CONFIG = 'ShoppingCartValidateDomainsConfig';
        case SHOPPING_CART_VALIDATE_PRODUCT_UPDATE = 'ShoppingCartValidateProductUpdate';
        case CANCELLATION_REQUEST = 'CancellationRequest';
        case PRE_SERVICE_EDIT = 'PreServiceEdit';
        case SERVICE_DELETE = 'ServiceDelete';
        case SERVICE_EDIT = 'ServiceEdit';
        case SERVICE_RECURRING_COMPLETED = 'ServiceRecurringCompleted';
        case AFTER_MODULE_CHANGE_PACKAGE = 'AfterModuleChangePackage';
        case AFTER_MODULE_CHANGE_PACKAGE_FAILED = 'AfterModuleChangePackageFailed';
        case AFTER_MODULE_CHANGE_PASSWORD = 'AfterModuleChangePassword';
        case AFTER_MODULE_CHANGE_PASSWORD_FAILED = 'AfterModuleChangePasswordFailed';
        case AFTER_MODULE_CREATE = 'AfterModuleCreate';
        case AFTER_MODULE_CREATE_FAILED = 'AfterModuleCreateFailed';
        case AFTER_MODULE_CUSTOM = 'AfterModuleCustom';
        case AFTER_MODULE_CUSTOM_FAILED = 'AfterModuleCustomFailed';
        case AFTER_MODULE_DEPROVISION_ADD_ON_FEATURE = 'AfterModuleDeprovisionAddOnFeature';
        case AFTER_MODULE_DEPROVISION_ADD_ON_FEATURE_FAILED = 'AfterModuleDeprovisionAddOnFeatureFailed';
        case AFTER_MODULE_PROVISION_ADD_ON_FEATURE = 'AfterModuleProvisionAddOnFeature';
        case AFTER_MODULE_PROVISION_ADD_ON_FEATURE_FAILED = 'AfterModuleProvisionAddOnFeatureFailed';
        case AFTER_MODULE_SUSPEND = 'AfterModuleSuspend';
        case AFTER_MODULE_SUSPEND_ADD_ON_FEATURE = 'AfterModuleSuspendAddOnFeature';
        case AFTER_MODULE_SUSPEND_ADD_ON_FEATURE_FAILED = 'AfterModuleSuspendAddOnFeatureFailed';
        case AFTER_MODULE_SUSPEND_FAILED = 'AfterModuleSuspendFailed';
        case AFTER_MODULE_TERMINATE = 'AfterModuleTerminate';
        case AFTER_MODULE_TERMINATE_FAILED = 'AfterModuleTerminateFailed';
        case AFTER_MODULE_UNSUSPEND = 'AfterModuleUnsuspend';
        case AFTER_MODULE_UNSUSPEND_ADD_ON_FEATURE = 'AfterModuleUnsuspendAddOnFeature';
        case AFTER_MODULE_UNSUSPEND_ADD_ON_FEATURE_FAILED = 'AfterModuleUnsuspendAddOnFeatureFailed';
        case AFTER_MODULE_UNSUSPEND_FAILED = 'AfterModuleUnsuspendFailed';
        case OVERRIDE_MODULE_USERNAME_GENERATION = 'OverrideModuleUsernameGeneration';
        case PRE_MODULE_CHANGE_PACKAGE = 'PreModuleChangePackage';
        case PRE_MODULE_CHANGE_PASSWORD = 'PreModuleChangePassword';
        case PRE_MODULE_CREATE = 'PreModuleCreate';
        case PRE_MODULE_CUSTOM = 'PreModuleCustom';
        case PRE_MODULE_DEPROVISION_ADD_ON_FEATURE = 'PreModuleDeprovisionAddOnFeature';
        case PRE_MODULE_PROVISION_ADD_ON_FEATURE = 'PreModuleProvisionAddOnFeature';
        case PRE_MODULE_RENEW = 'PreModuleRenew';
        case PRE_MODULE_SUSPEND = 'PreModuleSuspend';
        case PRE_MODULE_SUSPEND_ADD_ON_FEATURE = 'PreModuleSuspendAddOnFeature';
        case PRE_MODULE_TERMINATE = 'PreModuleTerminate';
        case PRE_MODULE_UNSUSPEND = 'PreModuleUnsuspend';
        case PRE_MODULE_UNSUSPEND_ADD_ON_FEATURE = 'PreModuleUnsuspendAddOnFeature';
        case DOMAIN_DELETE = 'DomainDelete';
        case DOMAIN_EDIT = 'DomainEdit';
        case DOMAIN_TRANSFER_COMPLETED = 'DomainTransferCompleted';
        case DOMAIN_TRANSFER_FAILED = 'DomainTransferFailed';
        case DOMAIN_VALIDATION = 'DomainValidation';
        case PRE_DOMAIN_REGISTER = 'PreDomainRegister';
        case PRE_DOMAIN_TRANSFER = 'PreDomainTransfer';
        case PRE_REGISTRAR_REGISTER_DOMAIN = 'PreRegistrarRegisterDomain';
        case PRE_REGISTRAR_RENEW_DOMAIN = 'PreRegistrarRenewDomain';
        case PRE_REGISTRAR_TRANSFER_DOMAIN = 'PreRegistrarTransferDomain';
        case TOP_LEVEL_DOMAIN_ADD = 'TopLevelDomainAdd';
        case TOP_LEVEL_DOMAIN_DELETE = 'TopLevelDomainDelete';
        case TOP_LEVEL_DOMAIN_PRICING_UPDATE = 'TopLevelDomainPricingUpdate';
        case TOP_LEVEL_DOMAIN_UPDATE = 'TopLevelDomainUpdate';
        case AFTER_REGISTRAR_GET_CONTACT_DETAILS = 'AfterRegistrarGetContactDetails';
        case AFTER_REGISTRAR_GET_DNS = 'AfterRegistrarGetDNS';
        case AFTER_REGISTRAR_GET_EPP_CODE = 'AfterRegistrarGetEPPCode';
        case AFTER_REGISTRAR_GET_NAMESERVERS = 'AfterRegistrarGetNameservers';
        case AFTER_REGISTRAR_REGISTER = 'AfterRegistrarRegister';
        case AFTER_REGISTRAR_REGISTRATION = 'AfterRegistrarRegistration';
        case AFTER_REGISTRAR_REGISTRATION_FAILED = 'AfterRegistrarRegistrationFailed';
        case AFTER_REGISTRAR_RENEW = 'AfterRegistrarRenew';
        case AFTER_REGISTRAR_RENEWAL = 'AfterRegistrarRenewal';
        case AFTER_REGISTRAR_RENEWAL_FAILED = 'AfterRegistrarRenewalFailed';
        case AFTER_REGISTRAR_REQUEST_DELETE = 'AfterRegistrarRequestDelete';
        case AFTER_REGISTRAR_SAVE_CONTACT_DETAILS = 'AfterRegistrarSaveContactDetails';
        case AFTER_REGISTRAR_SAVE_DNS = 'AfterRegistrarSaveDNS';
        case AFTER_REGISTRAR_SAVE_NAMESERVERS = 'AfterRegistrarSaveNameservers';
        case AFTER_REGISTRAR_TRANSFER = 'AfterRegistrarTransfer';
        case AFTER_REGISTRAR_TRANSFER_FAILED = 'AfterRegistrarTransferFailed';
        case PRE_REGISTRAR_GET_CONTACT_DETAILS = 'PreRegistrarGetContactDetails';
        case PRE_REGISTRAR_GET_DNS = 'PreRegistrarGetDNS';
        case PRE_REGISTRAR_GET_EPP_CODE = 'PreRegistrarGetEPPCode';
        case PRE_REGISTRAR_GET_NAMESERVERS = 'PreRegistrarGetNameservers';
        case PRE_REGISTRAR_REQUEST_DELETE = 'PreRegistrarRequestDelete';
        case PRE_REGISTRAR_SAVE_CONTACT_DETAILS = 'PreRegistrarSaveContactDetails';
        case PRE_REGISTRAR_SAVE_DNS = 'PreRegistrarSaveDNS';
        case PRE_REGISTRAR_SAVE_NAMESERVERS = 'PreRegistrarSaveNameservers';
        case ADDON = 'Addon';
        case ADDON_ACTIVATED = 'AddonActivated';
        case ADDON_ACTIVATION = 'AddonActivation';
        case ADDON_ADD = 'AddonAdd';
        case ADDON_CANCELLED = 'AddonCancelled';
        case ADDON_CONFIG = 'AddonConfig';
        case ADDON_CONFIG_SAVE = 'AddonConfigSave';
        case ADDON_DELETED = 'AddonDeleted';
        case ADDON_EDIT = 'AddonEdit';
        case ADDON_RENEWAL = 'AddonRenewal';
        case ADDON_SUSPENDED = 'AddonSuspended';
        case ADDON_TERMINATED = 'AddonTerminated';
        case ADDON_UNSUSPENDED = 'AddonUnsuspended';
        case AFTER_ADDON_UPGRADE = 'AfterAddonUpgrade';
        case LICENSING_ADDON_REISSUE = 'LicensingAddonReissue';
        case LICENSING_ADDON_VERIFY = 'LicensingAddonVerify';
        case PRODUCT_ADDON_DELETE = 'ProductAddonDelete';
        case AFTER_CLIENT_MERGE = 'AfterClientMerge';
        case CLIENT_ADD = 'ClientAdd';
        case CLIENT_ALERT = 'ClientAlert';
        case CLIENT_CHANGE_PASSWORD = 'ClientChangePassword';
        case CLIENT_CLOSE = 'ClientClose';
        case CLIENT_DELETE = 'ClientDelete';
        case CLIENT_DETAILS_VALIDATION = 'ClientDetailsValidation';
        case CLIENT_EDIT = 'ClientEdit';
        case PRE_DELETE_CLIENT = 'PreDeleteClient';
        case USER_ADD = 'UserAdd';
        case USER_CHANGE_PASSWORD = 'UserChangePassword';
        case USER_EDIT = 'UserEdit';
        case USER_EMAIL_VERIFICATION_COMPLETE = 'UserEmailVerificationComplete';
        case CONTACT_ADD = 'ContactAdd';
        case CONTACT_DELETE = 'ContactDelete';
        case CONTACT_DETAILS_VALIDATION = 'ContactDetailsValidation';
        case CONTACT_EDIT = 'ContactEdit';
        case AFTER_PRODUCT_UPGRADE = 'AfterProductUpgrade';
        case PRODUCT_DELETE = 'ProductDelete';
        case PRODUCT_EDIT = 'ProductEdit';
        case SERVER_ADD = 'ServerAdd';
        case SERVER_DELETE = 'ServerDelete';
        case SERVER_EDIT = 'ServerEdit';
        case ADMIN_AREA_VIEW_TICKET_PAGE = 'AdminAreaViewTicketPage';
        case ADMIN_AREA_VIEW_TICKET_PAGE_SIDEBAR = 'AdminAreaViewTicketPageSidebar';
        case ADMIN_SUPPORT_TICKET_PAGE_PRE_TICKETS = 'AdminSupportTicketPagePreTickets';
        case CLIENT_AREA_PAGE_SUBMIT_TICKET = 'ClientAreaPageSubmitTicket';
        case CLIENT_AREA_PAGE_SUPPORT_TICKETS = 'ClientAreaPageSupportTickets';
        case CLIENT_AREA_PAGE_VIEW_TICKET = 'ClientAreaPageViewTicket';
        case SUBMIT_TICKET_ANSWER_SUGGESTIONS = 'SubmitTicketAnswerSuggestions';
        case TICKET_ADD_NOTE = 'TicketAddNote';
        case TICKET_ADMIN_REPLY = 'TicketAdminReply';
        case TICKET_CLOSE = 'TicketClose';
        case TICKET_DELETE = 'TicketDelete';
        case TICKET_DELETE_REPLY = 'TicketDeleteReply';
        case TICKET_DEPARTMENT_CHANGE = 'TicketDepartmentChange';
        case TICKET_FLAGGED = 'TicketFlagged';
        case TICKET_MERGE = 'TicketMerge';
        case TICKET_OPEN = 'TicketOpen';
        case TICKET_OPEN_ADMIN = 'TicketOpenAdmin';
        case TICKET_OPEN_VALIDATION = 'TicketOpenValidation';
        case TICKET_PIPING = 'TicketPiping';
        case TICKET_PRIORITY_CHANGE = 'TicketPriorityChange';
        case TICKET_SPLIT = 'TicketSplit';
        case TICKET_STATUS_CHANGE = 'TicketStatusChange';
        case TICKET_SUBJECT_CHANGE = 'TicketSubjectChange';
        case TICKET_USER_REPLY = 'TicketUserReply';
        case TRANSLITERATE_TICKET_TEXT = 'TransliterateTicketText';
        case ANNOUNCEMENT_ADD = 'AnnouncementAdd';
        case ANNOUNCEMENT_EDIT = 'AnnouncementEdit';
        case FILE_DOWNLOAD = 'FileDownload';
        case NETWORK_ISSUE_ADD = 'NetworkIssueAdd';
        case NETWORK_ISSUE_CLOSE = 'NetworkIssueClose';
        case NETWORK_ISSUE_DELETE = 'NetworkIssueDelete';
        case NETWORK_ISSUE_EDIT = 'NetworkIssueEdit';
        case NETWORK_ISSUE_REOPEN = 'NetworkIssueReopen';
        case CLIENT_LOGIN_SHARE = 'ClientLoginShare';
        case USER_LOGIN = 'UserLogin';
        case USER_LOGOUT = 'UserLogout';
        case CLIENT_AREA_DOMAIN_DETAILS = 'ClientAreaDomainDetails';
        case CLIENT_AREA_HOMEPAGE = 'ClientAreaHomepage';
        case CLIENT_AREA_HOMEPAGE_PANELS = 'ClientAreaHomepagePanels';
        case CLIENT_AREA_NAVBARS = 'ClientAreaNavbars';
        case CLIENT_AREA_PAGE = 'ClientAreaPage';
        case CLIENT_AREA_PAGE_ADD_CONTACT = 'ClientAreaPageAddContact';
        case CLIENT_AREA_PAGE_ADD_FUNDS = 'ClientAreaPageAddFunds';
        case CLIENT_AREA_PAGE_ADDON_MODULE = 'ClientAreaPageAddonModule';
        case CLIENT_AREA_PAGE_AFFILIATES = 'ClientAreaPageAffiliates';
        case CLIENT_AREA_PAGE_ANNOUNCEMENTS = 'ClientAreaPageAnnouncements';
        case CLIENT_AREA_PAGE_BANNED = 'ClientAreaPageBanned';
        case CLIENT_AREA_PAGE_BULK_DOMAIN_MANAGEMENT = 'ClientAreaPageBulkDomainManagement';
        case CLIENT_AREA_PAGE_CANCELLATION = 'ClientAreaPageCancellation';
        case CLIENT_AREA_PAGE_CART = 'ClientAreaPageCart';
        case CLIENT_AREA_PAGE_CHANGE_PASSWORD = 'ClientAreaPageChangePassword';
        case CLIENT_AREA_PAGE_CONFIGURE_SSL = 'ClientAreaPageConfigureSSL';
        case CLIENT_AREA_PAGE_CONTACT = 'ClientAreaPageContact';
        case CLIENT_AREA_PAGE_CONTACTS = 'ClientAreaPageContacts';
        case CLIENT_AREA_PAGE_CREDIT_CARD = 'ClientAreaPageCreditCard';
        case CLIENT_AREA_PAGE_CREDIT_CARD_CHECKOUT = 'ClientAreaPageCreditCardCheckout';
        case CLIENT_AREA_PAGE_DOMAIN_ADDONS = 'ClientAreaPageDomainAddons';
        case CLIENT_AREA_PAGE_DOMAIN_CONTACTS = 'ClientAreaPageDomainContacts';
        case CLIENT_AREA_PAGE_DOMAIN_DNS_MANAGEMENT = 'ClientAreaPageDomainDNSManagement';
        case CLIENT_AREA_PAGE_DOMAIN_DETAILS = 'ClientAreaPageDomainDetails';
        case CLIENT_AREA_PAGE_DOMAIN_EPP_CODE = 'ClientAreaPageDomainEPPCode';
        case CLIENT_AREA_PAGE_DOMAIN_EMAIL_FORWARDING = 'ClientAreaPageDomainEmailForwarding';
        case CLIENT_AREA_PAGE_DOMAIN_REGISTER_NAMESERVERS = 'ClientAreaPageDomainRegisterNameservers';
        case CLIENT_AREA_PAGE_DOMAINS = 'ClientAreaPageDomains';
        case CLIENT_AREA_PAGE_DOWNLOADS = 'ClientAreaPageDownloads';
        case CLIENT_AREA_PAGE_EMAILS = 'ClientAreaPageEmails';
        case CLIENT_AREA_PAGE_HOME = 'ClientAreaPageHome';
        case CLIENT_AREA_PAGE_INVOICES = 'ClientAreaPageInvoices';
        case CLIENT_AREA_PAGE_KNOWLEDGEBASE = 'ClientAreaPageKnowledgebase';
        case CLIENT_AREA_PAGE_LOGIN = 'ClientAreaPageLogin';
        case CLIENT_AREA_PAGE_LOGOUT = 'ClientAreaPageLogout';
        case CLIENT_AREA_PAGE_MASS_PAY = 'ClientAreaPageMassPay';
        case CLIENT_AREA_PAGE_NETWORK_ISSUES = 'ClientAreaPageNetworkIssues';
        case CLIENT_AREA_PAGE_PASSWORD_RESET = 'ClientAreaPagePasswordReset';
        case CLIENT_AREA_PAGE_PRODUCT_DETAILS = 'ClientAreaPageProductDetails';
        case CLIENT_AREA_PAGE_PRODUCTS_SERVICES = 'ClientAreaPageProductsServices';
        case CLIENT_AREA_PAGE_PROFILE = 'ClientAreaPageProfile';
        case CLIENT_AREA_PAGE_QUOTES = 'ClientAreaPageQuotes';
        case CLIENT_AREA_PAGE_REGISTER = 'ClientAreaPageRegister';
        case CLIENT_AREA_PAGE_SECURITY = 'ClientAreaPageSecurity';
        case CLIENT_AREA_PAGE_SERVER_STATUS = 'ClientAreaPageServerStatus';
        case CLIENT_AREA_PAGE_UNSUBSCRIBE = 'ClientAreaPageUnsubscribe';
        case CLIENT_AREA_PAGE_UPGRADE = 'ClientAreaPageUpgrade';
        case CLIENT_AREA_PAGE_VIEW_EMAIL = 'ClientAreaPageViewEmail';
        case CLIENT_AREA_PAGE_VIEW_INVOICE = 'ClientAreaPageViewInvoice';
        case CLIENT_AREA_PAGE_VIEW_QUOTE = 'ClientAreaPageViewQuote';
        case CLIENT_AREA_PAYMENT_METHODS = 'ClientAreaPaymentMethods';
        case CLIENT_AREA_PRIMARY_NAVBAR = 'ClientAreaPrimaryNavbar';
        case CLIENT_AREA_PRIMARY_SIDEBAR = 'ClientAreaPrimarySidebar';
        case CLIENT_AREA_PRODUCT_DETAILS = 'ClientAreaProductDetails';
        case CLIENT_AREA_PRODUCT_DETAILS_PRE_MODULE_TEMPLATE = 'ClientAreaProductDetailsPreModuleTemplate';
        case CLIENT_AREA_REGISTER = 'ClientAreaRegister';
        case CLIENT_AREA_SECONDARY_NAVBAR = 'ClientAreaSecondaryNavbar';
        case CLIENT_AREA_SECONDARY_SIDEBAR = 'ClientAreaSecondarySidebar';
        case CLIENT_AREA_SIDEBARS = 'ClientAreaSidebars';
        case ADMIN_AREA_CLIENT_SUMMARY_ACTION_LINKS = 'AdminAreaClientSummaryActionLinks';
        case ADMIN_AREA_CLIENT_SUMMARY_PAGE = 'AdminAreaClientSummaryPage';
        case ADMIN_AREA_PAGE = 'AdminAreaPage';
        case ADMIN_AREA_VIEW_QUOTE_PAGE = 'AdminAreaViewQuotePage';
        case ADMIN_CLIENT_DOMAINS_TAB_FIELDS = 'AdminClientDomainsTabFields';
        case ADMIN_CLIENT_DOMAINS_TAB_FIELDS_SAVE = 'AdminClientDomainsTabFieldsSave';
        case ADMIN_CLIENT_FILE_UPLOAD = 'AdminClientFileUpload';
        case ADMIN_CLIENT_PROFILE_TAB_FIELDS = 'AdminClientProfileTabFields';
        case ADMIN_CLIENT_PROFILE_TAB_FIELDS_SAVE = 'AdminClientProfileTabFieldsSave';
        case ADMIN_CLIENT_SERVICES_TAB_FIELDS = 'AdminClientServicesTabFields';
        case ADMIN_CLIENT_SERVICES_TAB_FIELDS_SAVE = 'AdminClientServicesTabFieldsSave';
        case ADMIN_HOMEPAGE = 'AdminHomepage';
        case ADMIN_LOGIN = 'AdminLogin';
        case ADMIN_LOGOUT = 'AdminLogout';
        case ADMIN_PREDEFINED_ADDONS = 'AdminPredefinedAddons';
        case ADMIN_PRODUCT_CONFIG_FIELDS = 'AdminProductConfigFields';
        case ADMIN_PRODUCT_CONFIG_FIELDS_SAVE = 'AdminProductConfigFieldsSave';
        case ADMIN_SERVICE_EDIT = 'AdminServiceEdit';
        case AUTH_ADMIN = 'AuthAdmin';
        case AUTH_ADMIN_API = 'AuthAdminApi';
        case INVOICE_CREATION_ADMIN_AREA = 'InvoiceCreationAdminArea';
        case PRE_ADMIN_SERVICE_EDIT = 'PreAdminServiceEdit';
        case VIEW_ORDER_DETAILS_PAGE = 'ViewOrderDetailsPage';
        case ADMIN_AREA_FOOTER_OUTPUT = 'AdminAreaFooterOutput';
        case ADMIN_AREA_HEAD_OUTPUT = 'AdminAreaHeadOutput';
        case ADMIN_AREA_HEADER_OUTPUT = 'AdminAreaHeaderOutput';
        case ADMIN_INVOICES_CONTROLS_OUTPUT = 'AdminInvoicesControlsOutput';
        case CLIENT_AREA_DOMAIN_DETAILS_OUTPUT = 'ClientAreaDomainDetailsOutput';
        case CLIENT_AREA_FOOTER_OUTPUT = 'ClientAreaFooterOutput';
        case CLIENT_AREA_HEAD_OUTPUT = 'ClientAreaHeadOutput';
        case CLIENT_AREA_HEADER_OUTPUT = 'ClientAreaHeaderOutput';
        case CLIENT_AREA_PRODUCT_DETAILS_OUTPUT = 'ClientAreaProductDetailsOutput';
        case FORMAT_DATE_FOR_CLIENT_AREA_OUTPUT = 'FormatDateForClientAreaOutput';
        case FORMAT_DATE_TIME_FOR_CLIENT_AREA_OUTPUT = 'FormatDateTimeForClientAreaOutput';
        case REPORT_VIEW_POST_OUTPUT = 'ReportViewPostOutput';
        case REPORT_VIEW_PRE_OUTPUT = 'ReportViewPreOutput';
        case SHOPPING_CART_CHECKOUT_OUTPUT = 'ShoppingCartCheckoutOutput';
        case SHOPPING_CART_CONFIGURE_PRODUCT_ADDONS_OUTPUT = 'ShoppingCartConfigureProductAddonsOutput';
        case SHOPPING_CART_VIEW_CART_OUTPUT = 'ShoppingCartViewCartOutput';
        case AFTER_CRON_JOB = 'AfterCronJob';
        case DAILY_CRON_JOB = 'DailyCronJob';
        case DAILY_CRON_JOB_PRE_EMAIL = 'DailyCronJobPreEmail';
        case POP_EMAIL_COLLECTION_CRON_COMPLETED = 'PopEmailCollectionCronCompleted';
        case POST_AUTOMATION_TASK = 'PostAutomationTask';
        case PRE_AUTOMATION_TASK = 'PreAutomationTask';
        case PRE_CRON_JOB = 'PreCronJob';
        case AFFILIATE_ACTIVATION = 'AffiliateActivation';
        case AFFILIATE_CLICKTHRU = 'AffiliateClickthru';
        case AFFILIATE_COMMISSION = 'AffiliateCommission';
        case AFFILIATE_WITHDRAWAL_REQUEST = 'AffiliateWithdrawalRequest';
        case AFTER_CONFIG_OPTIONS_UPGRADE = 'AfterConfigOptionsUpgrade';
        case CC_UPDATE = 'CCUpdate';
        case CALC_AFFILIATE_COMMISSION = 'CalcAffiliateCommission';
        case CUSTOM_FIELD_LOAD = 'CustomFieldLoad';
        case CUSTOM_FIELD_SAVE = 'CustomFieldSave';
        case EMAIL_PRE_LOG = 'EmailPreLog';
        case EMAIL_PRE_SEND = 'EmailPreSend';
        case EMAIL_TPL_MERGE_FIELDS = 'EmailTplMergeFields';
        case FETCH_CURRENCY_EXCHANGE_RATES = 'FetchCurrencyExchangeRates';
        case INTELLIGENT_SEARCH = 'IntelligentSearch';
        case LINK_TRACKER = 'LinkTracker';
        case LOG_ACTIVITY = 'LogActivity';
        case NOTIFICATION_PRE_SEND = 'NotificationPreSend';
        case PAY_METHOD_MIGRATION = 'PayMethodMigration';
        case PRE_EMAIL_SEND_REDUCE_RECIPIENTS = 'PreEmailSendReduceRecipients';
        case PRE_UPGRADE_CHECKOUT = 'PreUpgradeCheckout';
        case PREMIUM_PRICE_OVERRIDE = 'PremiumPriceOverride';
        case PREMIUM_PRICE_RECALCULATION_OVERRIDE = 'PremiumPriceRecalculationOverride';
    }
}
