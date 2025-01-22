<?php

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
        protected function httpRequest(string $method, string $baseUrl, string $endpoint, array $body = [], array $headers = []) : false|null|array
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
        protected function response(bool $success, array $data = []) : array
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
        public final function request(string $method, string $endpoint, array $body = [], array $headers = []) : false|null|array
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
        public final function sendMessageToConversation(int $conversationId, string $content, string $contentType = 'text', string $msgType = 'outgoing', bool $private = false, array $contentAttrs = []) : array
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
        public final function searchContact(string $searchQuery) : array
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
        public final function getContactConversations(int $contactId) : array
        {
        }
        /**
         * @since 3.0.0
         *
         * @param int      $contactId
         * @param int|null $contactSourceId
         * @param int      $inboxId
         * @param string   $status
         *
         * @see https://www.chatwoot.com/developers/api/#tag/Conversations/operation/newConversation
         *
         * @return void
         */
        public final function createConversation(int $contactId, int|null $contactSourceId, int $inboxId, string $status = 'open')
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
        public final function createContact(int $inboxId, string $name = '', string $email = '', string $phone = '') : array
        {
        }
        public final function closeConversation()
        {
        }
    }
}
namespace Lkn\HookNotification\Domains\Notifications {
    interface NotificationInterface
    {
        public function run() : bool;
        public function sendMessage() : array|bool;
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
        public function setReportCategoryId(int $reportCategoryId) : void
        {
        }
        public function setReportCategory(\Lkn\HookNotification\Config\ReportCategory $reportCategory) : void
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
        public function report(bool $success) : void
        {
        }
        /**
         * For logs purposes.
         *
         * @since 3.0.0
         *
         * @return string "{$this->platform}:{$this->notificationCode}"
         */
        protected function getNotificationLogName() : string
        {
        }
        /**
         * @since 3.0.0
         *
         * @param array $hookParams the parameters provided by WHMCS. Each WHMCS hook
         *                          has its parameters: take a look at:
         *                          https://developers.whmcs.com/hooks/hook-index/
         */
        public final function setHookParams(array $hookParams) : void
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
        /**
         * @since 3.7.0
         *
         * @param  int   $clientId
         * @return array 0 => language locale, 1 => language code.
         */
        public static function getIsoLanguageForClient(int $clientId) : array
        {
        }
        public static function getClientWhatsAppNumber(int $clientId) : ?string
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
        public static function getInvoiceBalance(int $invoiceId, bool $formatFriendly = true) : null|string|float
        {
        }
        /**
         * @param int  $invoiceId
         * @param bool $formatFriendly
         *
         * @return null|string|float
         */
        public static function getInvoiceTotal(int $invoiceId, bool $formatFriendly = true) : null|string|float
        {
        }
        /**
         * @param int  $invoiceId
         * @param bool $formatFriendly
         *
         * @return null|string|float
         */
        public static function getInvoiceSubtotal(int $invoiceId, bool $formatFriendly = true) : null|string|float
        {
        }
        /**
         * @param int   $invoiceId
         * @param float $value
         *
         * @return string
         */
        public static function formatInvoiceValue(int $invoiceId, float $value) : string
        {
        }
        /**
         * Gets the info from the tblcurrencies database table.
         *
         * @param int $invoiceId
         */
        public static function getInvoiceCurrency(int $invoiceId) : \stdClass
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
        public static function getCurrencySeparatorsFromFormatId(int $formatId) : array
        {
        }
        public static function getClientFullNameByClientId(int $id) : string
        {
        }
        public static function getClientFirstTwoNamesByClientId(int $id) : string
        {
        }
        public static function getClientFirstNameByClientId(int $id) : string
        {
        }
        public static function getClientEmailByClientId(int $clientId) : string
        {
        }
        public static function getInvoicePdfUrlByInvocieId(int $id, bool $returnNullOtherwise = false) : ?string
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
        public static function getOrderItemsDescripByOrderId(int $orderId) : string
        {
        }
        public static function getInvoiceItemsByInvoiceId(int $invoiceId) : string
        {
        }
        /**
         * Tries to get the items of the invoice first using getOrderItemsDescripByOrderId.
         * If an empty string is returned, the uses getInvoiceItemsByInvoiceId.
         *
         * @since 3.4.0
         *
         * @param int $invoiceId
         *
         * @return string
         */
        public static function getItemsRelatedToInvoice(int $invoiceId) : string
        {
        }
        /**
         * @since 3.0.1
         *
         * @param int $invoiceId
         *
         * @return array [0 => Description 1, 1 => Description 2]
         */
        public static function getInvoiceItemsDescriptionsByInvoiceId(int $invoiceId) : array
        {
        }
        public static function getInvoiceDueDateByInvoiceId(int $invoiceId) : string
        {
        }
        public static function getServiceProductNameByProductId(int $productId) : string
        {
        }
        public function getHostDomainByHostId(int $hostId) : string
        {
        }
        public function getClientIdByInvoiceId(int $invoiceId) : int
        {
        }
        public function getClientIdByOrderId(int $orderId) : int
        {
        }
        public static function getOrderIdByInvoiceId(int $invoiceId) : ?int
        {
        }
        public function getClientIdByTicketId(int $ticketId) : ?int
        {
        }
        public function getTicket(int $ticketId, string $column) : string
        {
        }
        public function getTicketMask(int $ticketId) : string
        {
        }
        public function getTicketSubject(int $ticketId) : string
        {
        }
        public function getTicketStatus(int $ticketId) : string
        {
        }
        public function getTicketEmail(int $ticketId) : string
        {
        }
        public function getTicketNameColumn(int $ticketId) : string
        {
        }
        public function getTicketWhatsAppCfValue(int $ticketId) : ?int
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
        public final function setClientId(int $clientId) : void
        {
        }
        public function settings() : array
        {
        }
        public function saveSettings(array $newValuesPerSetting) : void
        {
        }
        public function getSettings() : array
        {
        }
        public function getSetting(string $name) : string
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
        public function sendMsgToChatwootAsPrivateNote(int $clientId, string $msg) : void
        {
        }
        public function sendMsgToChatwootAsPrivateNoteForUnregisteredClient(int $phoneNumber, string $msg, string $clientName, string $clientEmail, int $inboxId) : void
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
         * ISO language for the client.
         *
         * @since 3.7.0
         * @var string
         */
        protected string $clientLangLocale;
        /**
         * Language Code for the client.
         *
         * @since 3.7.0
         * @var string
         */
        protected string $clientLangCode;
        /**
         * Message templates can either be lang_COUNTRY (es_ES) or just lang (es).
         * That is why we need this flag to know which to send when
         * firing the message template.
         *
         * @since 3.7.0
         *
         * @see https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates/supported-languages
         *
         * @var string 'locale'|'lang-code'|'wp-lang-config'
         */
        protected string $didAssocMatchLangLocale;
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
        protected function defineParameters() : void
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
        public final function init() : void
        {
        }
        /**
         * @since 3.0.0
         *
         * @param int $clientId
         *
         * @return void
         */
        protected function setClientId(int $clientId) : void
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
        public final function sendMessage(?int $whatsappNumber = null) : array|false
        {
        }
        public function saveSettings(array $newValuesPerSetting) : void
        {
        }
        public function getSettings() : array
        {
        }
        public function getSetting(string $name) : string
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
        public final function parse(array $components, array $notifParams) : array
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
        public final function request(string $method, string $endpoint, array $body = [], array $headers = []) : false|null|array
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
        public final function apiCloud(string $method, string $endpoint, array $body = [], array $headers = []) : false|null|array
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
        public final function apiBusiness(string $method, string $endpoint, array $body = [], array $headers = [], string $queryParams = '') : false|null|array
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
        public static final function run(string $notificationNamespace, ?callable $condition = null) : void
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
        public static function runNow(string $notificationNamespace, array $hookParams = [], ?callable $condition = null, \Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation|\Lkn\HookNotification\Domains\Platforms\Chatwoot\AbstractChatwootNotification|null $instance = null) : bool
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
        public static function runManually(string $notifCode, \Lkn\HookNotification\Config\Platforms $platform, array $hookParams, ?callable $condition = null) : array
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
        public static function getModuleLangAsObject() : object
        {
        }
        public static function text(string $key)
        {
        }
        public static function getCurrentLanguageName() : string
        {
        }
        public static function getNotificationLang(string $notifFolderPath, bool $asObject = false) : array|\stdClass
        {
        }
    }
    abstract class Logger
    {
        public static final function log(string $action, array|object|string|null $request, array|object|string|null $response = '') : void
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
        public static final function report(string $status, \Lkn\HookNotification\Config\Platforms $platform, string $notification, ?int $clientId, ?\Lkn\HookNotification\Config\ReportCategory $category, ?int $categoryId, \Lkn\HookNotification\Config\Hooks|array|null $hook = null, ?string $channel = null) : void
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
        public static function getCustomFieldsLabels(bool $onlyForClients = true) : array
        {
        }
        /**
         * @since 3.2.0
         *
         * @param string $resource the piece of url to append to the end of the URL. No need to add initial /.
         *
         * @return string
         */
        public static function getAdminRootUrl(string $resource = '') : string
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
        public static final function systemUrl()
        {
        }
        /**
         * @since 2.0.0
         *
         * @return string
         */
        public static final function moduleUrl()
        {
        }
        public static final function make(string $controller, string $resource, array $params = []) : string
        {
        }
    }
    abstract class Response
    {
        public static final function api(bool $success, array $data = []) : void
        {
        }
        public static final function redirect(string $controller, string $resource, array $params = []) : void
        {
        }
    }
    abstract class VersionUpgrade
    {
        public static final function setLatestVersion(string $version) : void
        {
        }
        public static final function setDismissOnAdminHome(bool $dismiss) : void
        {
        }
        public static final function getNewVersion() : ?string
        {
        }
        public static final function getDismissNewVersionAlert() : ?bool
        {
        }
        public static final function requestLatestVersion() : ?string
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
        public static final function stripTagsArray(array $array) : array
        {
        }
        public static final function removeNonNumber(string $value) : string
        {
        }
        public static final function normalizePersonName(string $name) : string
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
        public static final function get(\Lkn\HookNotification\Config\Platforms $platform, \Lkn\HookNotification\Config\Settings $setting) : mixed
        {
        }
        public static final function set(\Lkn\HookNotification\Config\Platforms $platform, \Lkn\HookNotification\Config\Settings $setting, mixed $value) : void
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
        public static final function isReportPageActive(string $object) : bool
        {
        }
        public static final function getConstant(string $constant) : mixed
        {
        }
    }
    final class View
    {
        public static function baseRender(string $viewPath, array $vars = []) : string
        {
        }
        public static function render(string $view, array $vars = [], string $viewRootPath = '') : string
        {
        }
        public static function addNotificationOption(string $notificationNamespace) : string
        {
        }
        public static function renderWithFeedback(string $view, string $type, string $msg, bool $blockPage = false) : string
        {
        }
        public static function error500(string $view, string $msg)
        {
        }
        public static function error404(string $view, string $msg)
        {
        }
        public static function withValidationErrors(string $view, array $errors = [], string $msg, bool $blockPage = false) : string
        {
        }
    }
    final class Utils
    {
        public static function isChatwootNotifEnabled(string $notifCode) : bool
        {
        }
    }
    final class ExtractHeaderDocBlockFromFile
    {
        public static function run(string $path) : ?array
        {
        }
    }
}
namespace Lkn\HookNotification\Domains\Shared\Abstracts {
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
        protected function apiResponse(bool $success, array $body = []) : void
        {
        }
        /**
         * Gets the request data for the endpoint.
         *
         * @since 3.0.0
         *
         * @return array
         */
        protected static function request() : array
        {
        }
    }
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
        protected function getRequestData() : array
        {
        }
        /**
         * @since 3.0.0
         *
         * @param array $array
         *
         * @return array
         */
        protected function flattenErrorsArray(array $array) : array
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
        protected function stripTagsArray(array $array) : array
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
        public final function sendMessageToClient(int $clientId, int $inboxId, int $searchBy, string $message, bool $private = false, string $settingPrivateNoteMode = 'open_new_conversation') : array
        {
        }
        /**
         * @since 3.0.0
         *
         * @param string $searchQuery
         *
         * @return array
         */
        public function searchContactAndGetItsIdAndItsInboxesSourceId(string $searchQuery) : array
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
        public function getSourceIdsByInboxIds(array $contactInboxes) : array
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
        public final function searchForContactOpenConversationByInboxId(int $contactId, int $inboxId) : array
        {
        }
        /**
         * @since 3.4.0
         *
         * @param int $contactId
         * @param int $inboxId
         *
         * @return array
         */
        public final function getContactLastConversation(int $contactId, int $inboxId) : array
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
        protected function query() : \Illuminate\Database\Query\Builder
        {
        }
        protected function success(?array $data = null, string $msg = '') : array
        {
        }
        protected function failure(string $msg = '', ?array $data = null) : array
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
        public function getMessageTemplates(string $params = '') : array
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
        public function getMessageTemplate(string $templateName, string $params = 'fields=name,components,status&status=APPROVED') : array
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
        public function encodeJson(array $value) : string
        {
        }
        /**
         * @since 3.0.0
         *
         * @param string $value
         *
         * @return array
         */
        public function decodeJson(string $value) : array|bool|null
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
        public function update(\Lkn\HookNotification\Config\Settings $setting, string $value) : bool
        {
        }
        /**
         * @since 3.0.0
         *
         * @param \Lkn\HookNotification\Config\Settings $setting
         *
         * @return string|null
         */
        public function getSetting(\Lkn\HookNotification\Config\Settings $setting) : ?string
        {
        }
        /**
         * @since 3.0.0
         *
         * @param \Lkn\HookNotification\Config\Settings[] $settings
         *
         * @return array
         */
        protected function getSettings(array $settings) : array
        {
        }
        /**
         * @since 3.0.0
         *
         * @param array $newValuesPerSetting [Settings => new_value, ...]
         *
         * @return bool
         */
        protected function updateSettings(array $newValuesPerSetting) : bool
        {
        }
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
        public function cleanUp($allReaders = false)
        {
        }
        /**
         * Set the minimal PDF version.
         *
         * @param string $pdfVersion
         */
        protected function setMinPdfVersion($pdfVersion)
        {
        }
        /** @noinspection PhpUndefinedClassInspection */
        /**
         * Get a new pdf parser instance.
         *
         * @param StreamReader $streamReader
         * @param array $parserParams Individual parameters passed to the parser instance.
         * @return PdfParser|FpdiPdfParser
         */
        protected function getPdfParserInstance(\setasign\Fpdi\PdfParser\StreamReader $streamReader, array $parserParams = [])
        {
        }
        /**
         * Get an unique reader id by the $file parameter.
         *
         * @param string|resource|PdfReader|StreamReader $file An open file descriptor, a path to a file, a PdfReader
         *                                                     instance or a StreamReader instance.
         * @param array $parserParams Individual parameters passed to the parser instance.
         * @return string
         */
        protected function getPdfReaderId($file, array $parserParams = [])
        {
        }
        /**
         * Get a pdf reader instance by its id.
         *
         * @param string $id
         * @return PdfReader
         */
        protected function getPdfReader($id)
        {
        }
        /**
         * Set the source PDF file.
         *
         * @param string|resource|StreamReader $file Path to the file or a stream resource or a StreamReader instance.
         * @return int The page count of the PDF document.
         * @throws PdfParserException
         */
        public function setSourceFile($file)
        {
        }
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
        public function setSourceFileWithParserParams($file, array $parserParams = [])
        {
        }
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
        public function importPage($pageNumber, $box = \setasign\Fpdi\PdfReader\PageBoundaries::CROP_BOX, $groupXObject = true, $importExternalLinks = false)
        {
        }
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
        public function useImportedPage($pageId, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false)
        {
        }
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
        protected function adjustLastLink($externalLink, $xPt, $scaleX, $yPt, $newHeightPt, $scaleY, $importedPage)
        {
        }
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
        public function getImportedPageSize($tpl, $width = null, $height = null)
        {
        }
        /**
         * Writes a PdfType object to the resulting buffer.
         *
         * @param PdfType $value
         * @throws PdfTypeException
         */
        protected function writePdfType(\setasign\Fpdi\PdfParser\Type\PdfType $value)
        {
        }
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
        protected function _enddoc()
        {
        }
        /**
         * Get the next template id.
         *
         * @return int
         */
        protected function getNextTemplateId()
        {
        }
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
        public function useTemplate($tpl, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false)
        {
        }
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
        public function useImportedPage($pageId, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false)
        {
        }
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
        public function getTemplateSize($tpl, $width = null, $height = null)
        {
        }
        /**
         * @inheritdoc
         * @return string
         */
        protected function _getxobjectdict()
        {
        }
        /**
         * @inheritdoc
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        protected function _putxobjects()
        {
        }
        /**
         * Append content to the buffer of TCPDF.
         *
         * @param string $s
         * @param bool $newLine
         */
        protected function _put($s, $newLine = true)
        {
        }
        /**
         * Begin a new object and return the object number.
         *
         * @param int|string $objid Object ID (leave empty to get a new ID).
         * @return int object number
         */
        protected function _newobj($objid = '')
        {
        }
        /**
         * Writes a PdfType object to the resulting buffer.
         *
         * @param PdfType $value
         * @throws PdfTypeException
         */
        protected function writePdfType(\setasign\Fpdi\PdfParser\Type\PdfType $value)
        {
        }
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
        protected function adjustLastLink($externalLink, $xPt, $scaleX, $yPt, $newHeightPt, $scaleY, $importedPage)
        {
        }
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
        public function setPageFormat($size, $orientation)
        {
        }
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
        public function useTemplate($tpl, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false)
        {
        }
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
        public function getTemplateSize($tpl, $width = null, $height = null)
        {
        }
        /**
         * Begins a new template.
         *
         * @param float|int|null $width The width of the template. If null, the current page width is used.
         * @param float|int|null $height The height of the template. If null, the current page height is used.
         * @param bool $groupXObject Define the form XObject as a group XObject to support transparency (if used).
         * @return int A template identifier.
         */
        public function beginTemplate($width = null, $height = null, $groupXObject = false)
        {
        }
        /**
         * Ends a template.
         *
         * @return bool|int|null A template identifier.
         */
        public function endTemplate()
        {
        }
        /**
         * Get the next template id.
         *
         * @return int
         */
        protected function getNextTemplateId()
        {
        }
        /* overwritten FPDF methods: */
        /**
         * @inheritdoc
         */
        public function AddPage($orientation = '', $size = '', $rotation = 0)
        {
        }
        /**
         * @inheritdoc
         */
        public function Link($x, $y, $w, $h, $link)
        {
        }
        /**
         * @inheritdoc
         */
        public function SetLink($link, $y = 0, $page = -1)
        {
        }
        /**
         * @inheritdoc
         */
        public function SetDrawColor($r, $g = null, $b = null)
        {
        }
        /**
         * @inheritdoc
         */
        public function SetFillColor($r, $g = null, $b = null)
        {
        }
        /**
         * @inheritdoc
         */
        public function SetLineWidth($width)
        {
        }
        /**
         * @inheritdoc
         */
        public function SetFont($family, $style = '', $size = 0)
        {
        }
        /**
         * @inheritdoc
         */
        public function SetFontSize($size)
        {
        }
        protected function _putimages()
        {
        }
        /**
         * @inheritdoc
         */
        protected function _putxobjectdict()
        {
        }
        /**
         * @inheritdoc
         */
        public function _out($s)
        {
        }
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
        protected function _enddoc()
        {
        }
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
        public function useTemplate($tpl, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false)
        {
        }
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
        public function getTemplateSize($tpl, $width = null, $height = null)
        {
        }
        /**
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        protected function _putimages()
        {
        }
        /**
         * @inheritdoc
         */
        protected function _putxobjectdict()
        {
        }
        /**
         * @param int $n
         * @return void
         * @throws PdfParser\Type\PdfTypeException
         */
        protected function _putlinks($n)
        {
        }
        protected function _put($s, $newLine = true)
        {
        }
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
        function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
        {
        }
        function SetMargins($left, $top, $right = \null)
        {
        }
        function SetLeftMargin($margin)
        {
        }
        function SetTopMargin($margin)
        {
        }
        function SetRightMargin($margin)
        {
        }
        function SetAutoPageBreak($auto, $margin = 0)
        {
        }
        function SetDisplayMode($zoom, $layout = 'default')
        {
        }
        function SetCompression($compress)
        {
        }
        function SetTitle($title, $isUTF8 = \false)
        {
        }
        function SetAuthor($author, $isUTF8 = \false)
        {
        }
        function SetSubject($subject, $isUTF8 = \false)
        {
        }
        function SetKeywords($keywords, $isUTF8 = \false)
        {
        }
        function SetCreator($creator, $isUTF8 = \false)
        {
        }
        function AliasNbPages($alias = '{nb}')
        {
        }
        function Error($msg)
        {
        }
        function Close()
        {
        }
        function AddPage($orientation = '', $size = '', $rotation = 0)
        {
        }
        function Header()
        {
        }
        function Footer()
        {
        }
        function PageNo()
        {
        }
        function SetDrawColor($r, $g = \null, $b = \null)
        {
        }
        function SetFillColor($r, $g = \null, $b = \null)
        {
        }
        function SetTextColor($r, $g = \null, $b = \null)
        {
        }
        function GetStringWidth($s)
        {
        }
        function SetLineWidth($width)
        {
        }
        function Line($x1, $y1, $x2, $y2)
        {
        }
        function Rect($x, $y, $w, $h, $style = '')
        {
        }
        function AddFont($family, $style = '', $file = '', $dir = '')
        {
        }
        function SetFont($family, $style = '', $size = 0)
        {
        }
        function SetFontSize($size)
        {
        }
        function AddLink()
        {
        }
        function SetLink($link, $y = 0, $page = -1)
        {
        }
        function Link($x, $y, $w, $h, $link)
        {
        }
        function Text($x, $y, $txt)
        {
        }
        function AcceptPageBreak()
        {
        }
        function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = \false, $link = '')
        {
        }
        function MultiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = \false)
        {
        }
        function Write($h, $txt, $link = '')
        {
        }
        function Ln($h = \null)
        {
        }
        function Image($file, $x = \null, $y = \null, $w = 0, $h = 0, $type = '', $link = '')
        {
        }
        function GetPageWidth()
        {
        }
        function GetPageHeight()
        {
        }
        function GetX()
        {
        }
        function SetX($x)
        {
        }
        function GetY()
        {
        }
        function SetY($y, $resetX = \true)
        {
        }
        function SetXY($x, $y)
        {
        }
        function Output($dest = '', $name = '', $isUTF8 = \false)
        {
        }
        /*******************************************************************************
        *                              Protected methods                               *
        *******************************************************************************/
        protected function _checkoutput()
        {
        }
        protected function _getpagesize($size)
        {
        }
        protected function _beginpage($orientation, $size, $rotation)
        {
        }
        protected function _endpage()
        {
        }
        protected function _loadfont($path)
        {
        }
        protected function _isascii($s)
        {
        }
        protected function _httpencode($param, $value, $isUTF8)
        {
        }
        protected function _UTF8encode($s)
        {
        }
        protected function _UTF8toUTF16($s)
        {
        }
        protected function _escape($s)
        {
        }
        protected function _textstring($s)
        {
        }
        protected function _dounderline($x, $y, $txt)
        {
        }
        protected function _parsejpg($file)
        {
        }
        protected function _parsepng($file)
        {
        }
        protected function _parsepngstream($f, $file)
        {
        }
        protected function _readstream($f, $n)
        {
        }
        protected function _readint($f)
        {
        }
        protected function _parsegif($file)
        {
        }
        protected function _out($s)
        {
        }
        protected function _put($s)
        {
        }
        protected function _getoffset()
        {
        }
        protected function _newobj($n = \null)
        {
        }
        protected function _putstream($data)
        {
        }
        protected function _putstreamobject($data)
        {
        }
        protected function _putlinks($n)
        {
        }
        protected function _putpage($n)
        {
        }
        protected function _putpages()
        {
        }
        protected function _putfonts()
        {
        }
        protected function _tounicodecmap($uv)
        {
        }
        protected function _putimages()
        {
        }
        protected function _putimage(&$info)
        {
        }
        protected function _putxobjectdict()
        {
        }
        protected function _putresourcedict()
        {
        }
        protected function _putresources()
        {
        }
        protected function _putinfo()
        {
        }
        protected function _putcatalog()
        {
        }
        protected function _putheader()
        {
        }
        protected function _puttrailer()
        {
        }
        protected function _enddoc()
        {
        }
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
        public function __construct($x = 0.0, $y = 0.0)
        {
        }
        /**
         * @return float
         */
        public function getX()
        {
        }
        /**
         * @return float
         */
        public function getY()
        {
        }
        /**
         * @param Matrix $matrix
         * @return Vector
         */
        public function multiplyWithMatrix(\setasign\Fpdi\Math\Matrix $matrix)
        {
        }
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
        public function __construct($a = 1, $b = 0, $c = 0, $d = 1, $e = 0, $f = 0)
        {
        }
        /**
         * @return float[]
         */
        public function getValues()
        {
        }
        /**
         * @param Matrix $by
         * @return Matrix
         */
        public function multiply(self $by)
        {
        }
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
        public static function createByString($content, $maxMemory = 2097152)
        {
        }
        /**
         * Creates a stream reader instance by a filename.
         *
         * @param string $filename
         * @return StreamReader
         */
        public static function createByFile($filename)
        {
        }
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
        public function __construct($stream, $closeStream = false)
        {
        }
        /**
         * The destructor.
         */
        public function __destruct()
        {
        }
        /**
         * Closes the file handle.
         */
        public function cleanUp()
        {
        }
        /**
         * Returns the byte length of the buffer.
         *
         * @param bool $atOffset
         * @return int
         */
        public function getBufferLength($atOffset = false)
        {
        }
        /**
         * Get the current position in the stream.
         *
         * @return int
         */
        public function getPosition()
        {
        }
        /**
         * Returns the current buffer.
         *
         * @param bool $atOffset
         * @return string
         */
        public function getBuffer($atOffset = true)
        {
        }
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
        public function getByte($position = null)
        {
        }
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
        public function readByte($position = null)
        {
        }
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
        public function readBytes($length, $position = null)
        {
        }
        /**
         * Read a line from the current position.
         *
         * @param int $length
         * @return string|bool
         */
        public function readLine($length = 1024)
        {
        }
        /**
         * Set the offset position in the current buffer.
         *
         * @param int $offset
         */
        public function setOffset($offset)
        {
        }
        /**
         * Returns the current offset in the current buffer.
         *
         * @return int
         */
        public function getOffset()
        {
        }
        /**
         * Add an offset to the current offset.
         *
         * @param int $offset
         */
        public function addOffset($offset)
        {
        }
        /**
         * Make sure that there is at least one character beyond the current offset in the buffer.
         *
         * @return bool
         */
        public function ensureContent()
        {
        }
        /**
         * Returns the stream.
         *
         * @return resource
         */
        public function getStream()
        {
        }
        /**
         * Gets the total available length.
         *
         * @return int
         */
        public function getTotalLength()
        {
        }
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
        public function reset($pos = 0, $length = 200)
        {
        }
        /**
         * Ensures bytes in the buffer with a specific length and location in the file.
         *
         * @param int $pos
         * @param int $length
         * @see reset()
         */
        public function ensure($pos, $length)
        {
        }
        /**
         * Forcefully read more data into the buffer.
         *
         * @param int $minLength
         * @return bool Returns false if the stream reaches the end
         */
        public function increaseLength($minLength = 100)
        {
        }
    }
}
namespace setasign\Fpdi {
    /**
     * Base exception class for the FPDI package.
     */
    class FpdiException extends \Exception
    {
    }
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
        public function __construct(\setasign\Fpdi\PdfParser\PdfParser $parser)
        {
        }
        /**
         * Get the trailer dictionary.
         *
         * @return PdfDictionary
         */
        public function getTrailer()
        {
        }
        /**
         * Read the trailer dictionary.
         *
         * @throws CrossReferenceException
         * @throws PdfTypeException
         */
        protected function readTrailer()
        {
        }
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
        public function __construct(\setasign\Fpdi\PdfParser\PdfParser $parser)
        {
        }
        /**
         * @inheritdoc
         * @return int|false
         */
        public function getOffsetFor($objectNumber)
        {
        }
        /**
         * Get all found offsets.
         *
         * @return array
         */
        public function getOffsets()
        {
        }
        /**
         * Extracts the cross reference data from the stream reader.
         *
         * @param StreamReader $reader
         * @return string
         * @throws CrossReferenceException
         */
        protected function extract(\setasign\Fpdi\PdfParser\StreamReader $reader)
        {
        }
        /**
         * Read the cross-reference entries.
         *
         * @param string $xrefContent
         * @throws CrossReferenceException
         */
        protected function read($xrefContent)
        {
        }
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
        public function __construct(\setasign\Fpdi\PdfParser\PdfParser $parser, $fileHeaderOffset = 0)
        {
        }
        /**
         * Get the size of the cross reference.
         *
         * @return integer
         */
        public function getSize()
        {
        }
        /**
         * Get the trailer dictionary.
         *
         * @return PdfDictionary
         */
        public function getTrailer()
        {
        }
        /**
         * Get the cross reference readser instances.
         *
         * @return ReaderInterface[]
         */
        public function getReaders()
        {
        }
        /**
         * Get the offset by an object number.
         *
         * @param int $objectNumber
         * @return integer|bool
         */
        public function getOffsetFor($objectNumber)
        {
        }
        /**
         * Get an indirect object by its object number.
         *
         * @param int $objectNumber
         * @return PdfIndirectObject
         * @throws CrossReferenceException
         */
        public function getIndirectObject($objectNumber)
        {
        }
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
        protected function readXref($offset)
        {
        }
        /**
         * Get a cross-reference reader instance.
         *
         * @param PdfToken|PdfIndirectObject $initValue
         * @return ReaderInterface|bool
         * @throws CrossReferenceException
         * @throws PdfTypeException
         */
        protected function initReaderInstance($initValue)
        {
        }
        /**
         * Check for encryption.
         *
         * @param PdfDictionary $dictionary
         * @throws CrossReferenceException
         */
        protected function checkForEncryption(\setasign\Fpdi\PdfParser\Type\PdfDictionary $dictionary)
        {
        }
        /**
         * Find the start position for the first cross-reference.
         *
         * @return int The byte-offset position of the first cross-reference.
         * @throws CrossReferenceException
         */
        protected function findStartXref()
        {
        }
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
        public function __construct(\setasign\Fpdi\PdfParser\PdfParser $parser)
        {
        }
        /**
         * Get all subsection data.
         *
         * @return array
         */
        public function getSubSections()
        {
        }
        /**
         * @inheritdoc
         * @return int|false
         */
        public function getOffsetFor($objectNumber)
        {
        }
        /**
         * Read the cross-reference.
         *
         * This reader will only read the subsections in this method. The offsets were resolved individually by this
         * information.
         *
         * @throws CrossReferenceException
         */
        protected function read()
        {
        }
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
        public function fixFaultySubSectionShift()
        {
        }
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
        public function __construct(\setasign\Fpdi\PdfParser\StreamReader $streamReader)
        {
        }
        /**
         * Removes cycled references.
         *
         * @internal
         */
        public function cleanUp()
        {
        }
        /**
         * Get the stream reader instance.
         *
         * @return StreamReader
         */
        public function getStreamReader()
        {
        }
        /**
         * Get the tokenizer instance.
         *
         * @return Tokenizer
         */
        public function getTokenizer()
        {
        }
        /**
         * Resolves the file header.
         *
         * @throws PdfParserException
         * @return int
         */
        protected function resolveFileHeader()
        {
        }
        /**
         * Get the cross-reference instance.
         *
         * @return CrossReference
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        public function getCrossReference()
        {
        }
        /**
         * Get the PDF version.
         *
         * @return int[] An array of major and minor version.
         * @throws PdfParserException
         */
        public function getPdfVersion()
        {
        }
        /**
         * Get the catalog dictionary.
         *
         * @return PdfDictionary
         * @throws Type\PdfTypeException
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        public function getCatalog()
        {
        }
        /**
         * Get an indirect object by its object number.
         *
         * @param int $objectNumber
         * @param bool $cache
         * @return PdfIndirectObject
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        public function getIndirectObject($objectNumber, $cache = false)
        {
        }
        /**
         * Read a PDF value.
         *
         * @param null|bool|string $token
         * @param null|string $expectedType
         * @return false|PdfArray|PdfBoolean|PdfDictionary|PdfHexString|PdfIndirectObject|PdfIndirectObjectReference|PdfName|PdfNull|PdfNumeric|PdfStream|PdfString|PdfToken
         * @throws Type\PdfTypeException
         */
        public function readValue($token = null, $expectedType = null)
        {
        }
        /**
         * @return PdfString
         */
        protected function parsePdfString()
        {
        }
        /**
         * @return false|PdfHexString
         */
        protected function parsePdfHexString()
        {
        }
        /**
         * @return bool|PdfDictionary
         * @throws PdfTypeException
         */
        protected function parsePdfDictionary()
        {
        }
        /**
         * @return PdfName
         */
        protected function parsePdfName()
        {
        }
        /**
         * @return false|PdfArray
         * @throws PdfTypeException
         */
        protected function parsePdfArray()
        {
        }
        /**
         * @param int $objectNumber
         * @param int $generationNumber
         * @return false|PdfIndirectObject
         * @throws Type\PdfTypeException
         */
        protected function parsePdfIndirectObject($objectNumber, $generationNumber)
        {
        }
        /**
         * Ensures that the token will evaluate to an expected object type (or not).
         *
         * @param string $token
         * @param string|null $expectedType
         * @return bool
         * @throws Type\PdfTypeException
         */
        protected function ensureExpectedType($token, $expectedType)
        {
        }
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
        public static function resolve(\setasign\Fpdi\PdfParser\Type\PdfType $value, \setasign\Fpdi\PdfParser\PdfParser $parser, $stopAtIndirectObject = false)
        {
        }
        /**
         * Ensure that a value is an instance of a specific PDF type.
         *
         * @param string $type
         * @param PdfType $value
         * @param string $errorMessage
         * @return mixed
         * @throws PdfTypeException
         */
        protected static function ensureType($type, $value, $errorMessage)
        {
        }
        /**
         * Flatten indirect object references to direct objects.
         *
         * @param PdfType $value
         * @param PdfParser $parser
         * @return PdfType
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        public static function flatten(\setasign\Fpdi\PdfParser\Type\PdfType $value, \setasign\Fpdi\PdfParser\PdfParser $parser)
        {
        }
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
        public static function parse(\setasign\Fpdi\PdfParser\Type\PdfDictionary $dictionary, \setasign\Fpdi\PdfParser\StreamReader $reader, $parser = null)
        {
        }
        /**
         * Helper method to create an instance.
         *
         * @param PdfDictionary $dictionary
         * @param string $stream
         * @return self
         */
        public static function create(\setasign\Fpdi\PdfParser\Type\PdfDictionary $dictionary, $stream)
        {
        }
        /**
         * Ensures that the passed value is a PdfStream instance.
         *
         * @param mixed $stream
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($stream)
        {
        }
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
        public function getStream($cache = false)
        {
        }
        /**
         * Extract the stream "manually".
         *
         * @return string
         * @throws PdfTypeException
         */
        protected function extractStream()
        {
        }
        /**
         * Get all filters defined for this stream.
         *
         * @return PdfType[]
         * @throws PdfTypeException
         */
        public function getFilters()
        {
        }
        /**
         * Get the unfiltered stream data.
         *
         * @return string
         * @throws FilterException
         * @throws PdfParserException
         */
        public function getUnfilteredStream()
        {
        }
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
        public static function create($objectNumber, $generationNumber)
        {
        }
        /**
         * Ensures that the passed value is a PdfIndirectObject instance.
         *
         * @param mixed $value
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($value)
        {
        }
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
        public static function parse(\setasign\Fpdi\PdfParser\StreamReader $streamReader)
        {
        }
        /**
         * Helper method to create an instance.
         *
         * @param string $string The hex encoded string.
         * @return self
         */
        public static function create($string)
        {
        }
        /**
         * Ensures that the passed value is a PdfHexString instance.
         *
         * @param mixed $hexString
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($hexString)
        {
        }
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
        public static function create($value)
        {
        }
        /**
         * Ensures that the passed value is a PdfBoolean instance.
         *
         * @param mixed $value
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($value)
        {
        }
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
        public static function create($value)
        {
        }
        /**
         * Ensures that the passed value is a PdfNumeric instance.
         *
         * @param mixed $value
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($value)
        {
        }
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
        public static function parse(\setasign\Fpdi\PdfParser\Tokenizer $tokenizer, \setasign\Fpdi\PdfParser\PdfParser $parser)
        {
        }
        /**
         * Helper method to create an instance.
         *
         * @param PdfType[] $values
         * @return self
         */
        public static function create(array $values = [])
        {
        }
        /**
         * Ensures that the passed array is a PdfArray instance with a (optional) specific size.
         *
         * @param mixed $array
         * @param null|int $size
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($array, $size = null)
        {
        }
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
        public static function parse(\setasign\Fpdi\PdfParser\Tokenizer $tokenizer, \setasign\Fpdi\PdfParser\StreamReader $streamReader)
        {
        }
        /**
         * Unescapes a name string.
         *
         * @param string $value
         * @return string
         */
        public static function unescape($value)
        {
        }
        /**
         * Helper method to create an instance.
         *
         * @param string $string
         * @return self
         */
        public static function create($string)
        {
        }
        /**
         * Ensures that the passed value is a PdfName instance.
         *
         * @param mixed $name
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($name)
        {
        }
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
        public static function parse(\setasign\Fpdi\PdfParser\StreamReader $streamReader)
        {
        }
        /**
         * Helper method to create an instance.
         *
         * @param string $value The string needs to be escaped accordingly.
         * @return self
         */
        public static function create($value)
        {
        }
        /**
         * Ensures that the passed value is a PdfString instance.
         *
         * @param mixed $string
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($string)
        {
        }
        /**
         * Escapes sequences in a string according to the PDF specification.
         *
         * @param string $s
         * @return string
         */
        public static function escape($s)
        {
        }
        /**
         * Unescapes escaped sequences in a PDF string according to the PDF specification.
         *
         * @param string $s
         * @return string
         */
        public static function unescape($s)
        {
        }
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
        public static function create($token)
        {
        }
        /**
         * Ensures that the passed value is a PdfToken instance.
         *
         * @param mixed $token
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($token)
        {
        }
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
        public static function parse(\setasign\Fpdi\PdfParser\Tokenizer $tokenizer, \setasign\Fpdi\PdfParser\StreamReader $streamReader, \setasign\Fpdi\PdfParser\PdfParser $parser)
        {
        }
        /**
         * Helper method to create an instance.
         *
         * @param PdfType[] $entries The keys are the name entries of the dictionary.
         * @return self
         */
        public static function create(array $entries = [])
        {
        }
        /**
         * Get a value by its key from a dictionary or a default value.
         *
         * @param mixed $dictionary
         * @param string $key
         * @param PdfType|null $default
         * @return PdfNull|PdfType
         * @throws PdfTypeException
         */
        public static function get($dictionary, $key, $default = null)
        {
        }
        /**
         * Ensures that the passed value is a PdfDictionary instance.
         *
         * @param mixed $dictionary
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($dictionary)
        {
        }
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
        public static function parse($objectNumber, $objectGenerationNumber, \setasign\Fpdi\PdfParser\PdfParser $parser, \setasign\Fpdi\PdfParser\Tokenizer $tokenizer, \setasign\Fpdi\PdfParser\StreamReader $reader)
        {
        }
        /**
         * Helper method to create an instance.
         *
         * @param int $objectNumber
         * @param int $generationNumber
         * @param PdfType $value
         * @return self
         */
        public static function create($objectNumber, $generationNumber, \setasign\Fpdi\PdfParser\Type\PdfType $value)
        {
        }
        /**
         * Ensures that the passed value is a PdfIndirectObject instance.
         *
         * @param mixed $indirectObject
         * @return self
         * @throws PdfTypeException
         */
        public static function ensure($indirectObject)
        {
        }
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
        public function __construct(\setasign\Fpdi\PdfParser\StreamReader $streamReader)
        {
        }
        /**
         * Get the stream reader instance.
         *
         * @return StreamReader
         */
        public function getStreamReader()
        {
        }
        /**
         * Clear the token stack.
         */
        public function clearStack()
        {
        }
        /**
         * Push a token onto the stack.
         *
         * @param string $token
         */
        public function pushStack($token)
        {
        }
        /**
         * Get next token.
         *
         * @return false|string
         */
        public function getNextToken()
        {
        }
        /**
         * Leap white spaces.
         *
         * @return boolean
         */
        public function leapWhiteSpaces()
        {
        }
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
        protected function extensionLoaded()
        {
        }
        /**
         * Decodes a flate compressed string.
         *
         * @param string|false $data The input string
         * @return string
         * @throws FlateException
         */
        public function decode($data)
        {
        }
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
        public function decode($data)
        {
        }
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
        public function decode($data)
        {
        }
        /**
         * Converts a string into ASCII hexadecimal representation.
         *
         * @param string $data The input string
         * @param boolean $leaveEOD
         * @return string
         */
        public function encode($data, $leaveEOD = false)
        {
        }
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
        public function decode($data)
        {
        }
        /**
         * Initialize the string table.
         */
        protected function initsTable()
        {
        }
        /**
         * Add a new string to the string table.
         *
         * @param string $oldString
         * @param string $newString
         */
        protected function addStringToTable($oldString, $newString = '')
        {
        }
        /**
         * Returns the next 9, 10, 11 or 12 bits.
         *
         * @return int
         */
        protected function getNextCode()
        {
        }
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
        public function __construct($ctm = null)
        {
        }
        /**
         * @param Matrix $matrix
         * @return $this
         */
        public function add(\setasign\Fpdi\Math\Matrix $matrix)
        {
        }
        /**
         * @param int|float $x
         * @param int|float $y
         * @param int|float $angle
         * @return $this
         */
        public function rotate($x, $y, $angle)
        {
        }
        /**
         * @param int|float $shiftX
         * @param int|float $shiftY
         * @return $this
         */
        public function translate($shiftX, $shiftY)
        {
        }
        /**
         * @param int|float $scaleX
         * @param int|float $scaleY
         * @return $this
         */
        public function scale($scaleX, $scaleY)
        {
        }
        /**
         * @param Vector $vector
         * @return Vector
         */
        public function toUserSpace(\setasign\Fpdi\Math\Vector $vector)
        {
        }
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
        public function __construct(\setasign\Fpdi\PdfParser\PdfParser $parser)
        {
        }
        /**
         * PdfReader destructor.
         */
        public function __destruct()
        {
        }
        /**
         * Get the pdf parser instance.
         *
         * @return PdfParser
         */
        public function getParser()
        {
        }
        /**
         * Get the PDF version.
         *
         * @return string
         * @throws PdfParserException
         */
        public function getPdfVersion()
        {
        }
        /**
         * Get the page count.
         *
         * @return int
         * @throws PdfTypeException
         * @throws CrossReferenceException
         * @throws PdfParserException
         */
        public function getPageCount()
        {
        }
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
        public function getPage($pageNumber)
        {
        }
        /**
         * Walk the page tree and resolve all indirect objects of all pages.
         *
         * @param bool $readAll
         * @throws CrossReferenceException
         * @throws PdfParserException
         * @throws PdfTypeException
         */
        protected function readPages($readAll = false)
        {
        }
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
        public static function isValidName($name)
        {
        }
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
        public static function byPdfArray($array, \setasign\Fpdi\PdfParser\PdfParser $parser)
        {
        }
        public static function byVectors(\setasign\Fpdi\Math\Vector $ll, \setasign\Fpdi\Math\Vector $ur)
        {
        }
        /**
         * Rectangle constructor.
         *
         * @param float|int $ax
         * @param float|int $ay
         * @param float|int $bx
         * @param float|int $by
         */
        public function __construct($ax, $ay, $bx, $by)
        {
        }
        /**
         * Get the width of the rectangle.
         *
         * @return float|int
         */
        public function getWidth()
        {
        }
        /**
         * Get the height of the rectangle.
         *
         * @return float|int
         */
        public function getHeight()
        {
        }
        /**
         * Get the lower left abscissa.
         *
         * @return float|int
         */
        public function getLlx()
        {
        }
        /**
         * Get the lower left ordinate.
         *
         * @return float|int
         */
        public function getLly()
        {
        }
        /**
         * Get the upper right abscissa.
         *
         * @return float|int
         */
        public function getUrx()
        {
        }
        /**
         * Get the upper right ordinate.
         *
         * @return float|int
         */
        public function getUry()
        {
        }
        /**
         * Get the rectangle as an array.
         *
         * @return array
         */
        public function toArray()
        {
        }
        /**
         * Get the rectangle as a PdfArray.
         *
         * @return PdfArray
         */
        public function toPdfArray()
        {
        }
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
        public function __construct(\setasign\Fpdi\PdfParser\Type\PdfIndirectObject $page, \setasign\Fpdi\PdfParser\PdfParser $parser)
        {
        }
        /**
         * Get the indirect object of this page.
         *
         * @return PdfIndirectObject
         */
        public function getPageObject()
        {
        }
        /**
         * Get the dictionary of this page.
         *
         * @return PdfDictionary
         * @throws PdfParserException
         * @throws PdfTypeException
         * @throws CrossReferenceException
         */
        public function getPageDictionary()
        {
        }
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
        public function getAttribute($name, $inherited = true)
        {
        }
        /**
         * Get the rotation value.
         *
         * @return int
         * @throws PdfParserException
         * @throws PdfTypeException
         * @throws CrossReferenceException
         */
        public function getRotation()
        {
        }
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
        public function getBoundary($box = \setasign\Fpdi\PdfReader\PageBoundaries::CROP_BOX, $fallback = true)
        {
        }
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
        public function getWidthAndHeight($box = \setasign\Fpdi\PdfReader\PageBoundaries::CROP_BOX, $fallback = true)
        {
        }
        /**
         * Get the raw content stream.
         *
         * @return string
         * @throws PdfReaderException
         * @throws PdfTypeException
         * @throws FilterException
         * @throws PdfParserException
         */
        public function getContentStream()
        {
        }
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
        public function getExternalLinks($box = \setasign\Fpdi\PdfReader\PageBoundaries::CROP_BOX)
        {
        }
    }
}
namespace {
    class PDF extends \FPDF
    {
        protected $B = 0;
        protected $I = 0;
        protected $U = 0;
        protected $HREF = '';
        function WriteHTML($html)
        {
        }
        function OpenTag($tag, $attr)
        {
        }
        function CloseTag($tag)
        {
        }
        function SetStyle($tag, $enable)
        {
        }
        function PutLink($URL, $txt)
        {
        }
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
        function __construct($file)
        {
        }
        function __destruct()
        {
        }
        function Parse()
        {
        }
        function ParseOffsetTable()
        {
        }
        function ParseHead()
        {
        }
        function ParseHhea()
        {
        }
        function ParseMaxp()
        {
        }
        function ParseHmtx()
        {
        }
        function ParseLoca()
        {
        }
        function ParseGlyf()
        {
        }
        function ParseCmap()
        {
        }
        function ParseName()
        {
        }
        function ParseOS2()
        {
        }
        function ParsePost()
        {
        }
        function Subset($chars)
        {
        }
        function AddGlyph($id)
        {
        }
        function Build()
        {
        }
        function BuildCmap()
        {
        }
        function BuildHhea()
        {
        }
        function BuildHmtx()
        {
        }
        function BuildLoca()
        {
        }
        function BuildGlyf()
        {
        }
        function BuildMaxp()
        {
        }
        function BuildPost()
        {
        }
        function BuildFont()
        {
        }
        function LoadTable($tag)
        {
        }
        function SetTable($tag, $data)
        {
        }
        function Seek($tag)
        {
        }
        function Skip($n)
        {
        }
        function Read($n)
        {
        }
        function ReadUShort()
        {
        }
        function ReadShort()
        {
        }
        function ReadULong()
        {
        }
        function CheckSum($s)
        {
        }
        function Error($msg)
        {
        }
    }
}
namespace {
    function lknhooknotification_run_wp_events(bool $wasNotificationSent, \Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation|\Lkn\HookNotification\Domains\Platforms\Chatwoot\AbstractChatwootNotification $instance)
    {
    }
    function Message($txt, $severity = '')
    {
    }
    function Notice($txt)
    {
    }
    function Warning($txt)
    {
    }
    function Error($txt)
    {
    }
    function LoadMap($enc)
    {
    }
    function GetInfoFromTrueType($file, $embed, $subset, $map)
    {
    }
    function GetInfoFromType1($file, $embed, $map)
    {
    }
    function MakeFontDescriptor($info)
    {
    }
    function MakeWidthArray($widths)
    {
    }
    function MakeFontEncoding($map)
    {
    }
    function MakeUnicodeArray($map)
    {
    }
    function SaveToFile($file, $s, $mode)
    {
    }
    function MakeDefinitionFile($file, $type, $enc, $embed, $subset, $map, $info)
    {
    }
    function MakeFont($fontfile, $enc = 'cp1252', $embed = \true, $subset = \true)
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
