<?php

namespace Lkn\HookNotification\Core\Platforms\Chatwoot\Application;

use Lkn\HookNotification\Core\Platforms\Chatwoot\Domain\LiveChatSettings;
use Lkn\HookNotification\Core\Platforms\Common\Infrastructure\PlatformSettingsFactory;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings;
use Lkn\HookNotification\Core\Shared\Infrastructure\View\View;
use WHMCS\User\Client;

final class LiveChatService
{
    private readonly Client $signedInClient;
    private readonly View $view;
    private readonly LiveChatSettings $liveChatSettings;

    /**
     * @var array<mixed>
     */
    private readonly array $whmcsHookParams;

    /**
     * @param  array<string, mixed> $whmcsHookParams
     */
    public function __construct(array $whmcsHookParams)
    {
        /** @var Client $clientId */
        $clientId = $whmcsHookParams['client'];

        $this->signedInClient = $clientId;
        $this->view           =  new View();
        $this->view->setTemplateDir(__DIR__ . '/../Http/Views');
        $this->liveChatSettings = PlatformSettingsFactory::makeLiveChatSettings();
        $this->whmcsHookParams  = $whmcsHookParams;
    }

    public function handle(): string
    {
        if (
            !$this->liveChatSettings->enableLiveChat ||
            !$this->liveChatSettings->userIdentityValidation
        ) {
            return '';
        }

        [$clientIdentifierKey, $identifierHash] = self::makeIdentifierHash(
            $this->signedInClient->id,
            $this->liveChatSettings->userIdentityValidation
        );

        return $this->view->view(
            'live_chat',
            [
                'messenger_script' => $this->liveChatSettings->liveChatScript,
                'client_identifier_key' => $clientIdentifierKey,
                'identifier_hash' => $identifierHash,
                'client_details' => $this->getClientDetailsForLiveChat(),
                'custom_attrs_script' => json_encode(
                    $this->generateCustomAttrsSetterScript(),
                    JSON_UNESCAPED_SLASHES
                    | JSON_UNESCAPED_UNICODE
                    | JSON_PRETTY_PRINT
                ),
            ]
        )->render();
    }

    /**
     * Undocumented function
     *
     * @return array{
     *  locale: string,
     *  name: string,
     *  email: string,
     *  phone_number: string,
     *  country_code: string,
     *  city: string,
     *  company_name: string,
     * }
     */
    private function getClientDetailsForLiveChat(): array
    {
        $clientLocale = current(
            array_filter(
                $this->whmcsHookParams['locales'],
                fn (array $locale) =>
                $locale['language'] === $this->signedInClient->language
            )
        )['languageCode'];

        return [
            'locale' => $clientLocale,
            'name' =>   lkn_hn_normalize_person_name(
                $this->whmcsHookParams['client']['firstname'] . ' ' . $this->whmcsHookParams['client']['lastname']
            ),
            'email' => $this->whmcsHookParams['client']['email'],
            'phone_number' => '+' . lkn_hn_remove_phone_number($this->whmcsHookParams['client']['phonenumber']),
            'country_code' => $this->whmcsHookParams['client']['country'],
            'city' => $this->whmcsHookParams['client']['city'],
            'company_name' => lkn_hn_normalize_person_name($this->whmcsHookParams['client']['companyname']),
        ];
    }

    private function generateCustomAttrsSetterScript(): array
    {
        /** @var array<string> $clientStatsToSend */
        $clientStatsToSend = lkn_hn_config(Settings::CW_CLIENT_STATS_TO_SEND);
        /** @var array<int> $customFieldsToSend */
        $customFieldsToSend = lkn_hn_config(Settings::CW_CUSTOM_FIELDS_TO_SEND);
        /** @var array<string> $selectedAdditionalCustomFields */
        $selectedAdditionalCustomFields = lkn_hn_config(Settings::CW_LIVE_CHAT_MODULE_ATTRS_TO_SEND);

        $clientStatsFields = (require __DIR__ . '/../Infrastructure/constants.php')['chat_widget_attrs_options'];

        /** @var array<string, string> $customAttrs */
        $customAttrs = [];

        if (count($customFieldsToSend) > 0) {
            $customFields = lkn_hn_get_client_custom_fields_for_view();
            /** @var array<array{id: int, value: string}> */
            $clientCustomFields = $this->whmcsHookParams['clientsdetails']['customfields'];

            foreach ($customFields as $customField) {
                $customFieldId = $customField['value'];

                if (!in_array($customFieldId, $customFieldsToSend, false)) {
                    continue;
                }

                $customFieldKey = strtolower(str_replace(' ', '_', $customField['label'])) . '_'. $customFieldId;


                /** @var string $customFieldValue */
                $customFieldValue = current(
                    array_filter(
                        $clientCustomFields,
                        fn(array $item) => $item['id'] == $customFieldId,
                    ),
                )['value'];

                $customAttrs[$customFieldKey] = $customFieldValue;
            }
        }

        if (count($clientStatsToSend)) {
            $clientStats = localAPI('GetClientsDetails', ['clientid' => $this->signedInClient->id, 'stats' => true])['stats'];

            foreach ($clientStatsToSend as $statsKey) {
                $statsValue = current(array_filter($clientStats, fn ($key) => $key === $statsKey, ARRAY_FILTER_USE_KEY));
                $statsValue = $statsValue instanceof \WHMCS\View\Formatter\Price ? $statsValue->toPrefixed() : $statsValue;

                if (!empty($statsValue)) {
                    $customAttrs[$statsKey] = $statsValue;
                }
            }
        }

        if (count($selectedAdditionalCustomFields) > 0) {
            /** @var array<string> $additionalAttrsFields */
            $additionalAttrsFields = (require __DIR__ . '/../Infrastructure/constants.php')['module_attrs_options'];

            foreach ($selectedAdditionalCustomFields as $attr) {
                $clientId = $this->signedInClient->id;

                $attrsValue = match ($attr) {
                    'client_initial_acessed_page' => (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
                    'client_profile_url' => lkn_hn_get_admin_root_url("clientssummary.php?userid=$clientId"),
                    'client_tickets_url' => lkn_hn_get_admin_root_url("client/$clientId/tickets"),
                    'client_invoices_url' => lkn_hn_get_admin_root_url("clientsinvoices.php?userid=$clientId"),
                };

                $customAttrs[$attr] = $attrsValue;
            }
        }

        return $customAttrs;
    }

    /**
     * @see https://www.chatwoot.com/hc/user-guide/articles/1677587234-how-to-send-additional-user-information-to-chatwoot-using-sdk
     *
     * @param  integer $clientId
     * @param  string  $userIdentifyValidation
     *
     * @return array [$clientIdentifierKey, $identifierHash]
     */
    private static function makeIdentifierHash(
        int $clientId,
        string $userIdentifyValidation
    ) {
        $clientIdentifierKey = hash_hmac(
            'sha256',
            $clientId,
            $userIdentifyValidation
        );

        $identifierHash = hash_hmac(
            'sha256',
            $clientIdentifierKey,
            $userIdentifyValidation
        );

        return [$clientIdentifierKey, $identifierHash];
    }
}
