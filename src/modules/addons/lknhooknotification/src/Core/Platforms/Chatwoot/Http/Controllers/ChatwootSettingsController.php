<?php

namespace Lkn\HookNotification\Core\Platforms\Chatwoot\Http\Controllers;

use Lkn\HookNotification\Core\Platforms\Chatwoot\Infrastructure\ChatwootApiClient;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings;
use Lkn\HookNotification\Core\Shared\Infrastructure\Interfaces\BaseController;
use Lkn\HookNotification\Core\Shared\Infrastructure\View\View;

final class ChatwootSettingsController extends BaseController {
    public readonly ChatwootApiClient $chatwootApiClient;

    public function __construct()
    {
        /** @var string $accountId */
        $accountId = lkn_hn_config(Settings::CW_ACCOUNT_ID);
        /** @var string $chatwootUrl */
        $chatwootUrl = lkn_hn_config(Settings::CW_URL);
        /** @var string $apiAccessToken */
        $apiAccessToken = lkn_hn_config(Settings::CW_API_ACCESS_TOKEN);

        $this->chatwootApiClient = new ChatwootApiClient(
            $accountId,
            $chatwootUrl,
            $apiAccessToken,
        );

        parent::__construct(new View());
    }

    public function handle(array $request): void
    {
        /** @var array{
         *  attribute_display_name: string,
         *  attribute_display_type: int,
         *  attribute_key: string,
         *  attribute_model: int,
         * }[] $attrs
         */
        $attrs = [];

        /** @var array<string> $selectedClientStatsIds */
        $selectedClientStatsIds = $request[Settings::CW_CLIENT_STATS_TO_SEND->value] ?? [];
        /** @var array<int> $selectedCustomFieldsIds */
        $selectedCustomFieldsIds = $request[Settings::CW_CUSTOM_FIELDS_TO_SEND->value] ?? [];
        /** @var array<string> $selectedChatAttrsIds */
        $selectedChatAttrsIds = $request[Settings::CW_LIVE_CHAT_MODULE_ATTRS_TO_SEND->value] ?? [];

        $customFields = lkn_hn_get_client_custom_fields_for_view();

        foreach ($customFields as $customField) {
            $customFieldId = $customField['value'];

            if (!in_array($customFieldId, $selectedCustomFieldsIds)) {
                continue;
            }

            $attrs[] = [
                'attribute_display_name' => $customField['label'],
                'attribute_display_type' => 0,
                'attribute_key' => strtolower(str_replace(' ', '_', $customField['label'])) . '_' . $customFieldId,
                'attribute_model' => 1,
            ];
        }

        foreach ($selectedClientStatsIds as $statId) {
            $attrs[] = [
                'attribute_display_name' => lkn_hn_lang($statId),
                'attribute_display_type' => 0,
                'attribute_key' => $statId,
                'attribute_model' => 1,
            ];
        }

        foreach ($selectedChatAttrsIds as $attrId) {
            $attrs[] = [
                'attribute_display_name' => lkn_hn_lang($attrId),
                'attribute_display_type' => 'link',
                'attribute_key' => $attrId,
                'attribute_model' => 1,
            ];
        }

        $this->chatwootApiClient->createCustomAttribute($attrs);
    }
}
