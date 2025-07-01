<?php

namespace Lkn\HookNotification\Core\Platforms\Chatwoot\Domain;

use Lkn\HookNotification\Core\Platforms\Common\AbstractPlatformSettings;
use Lkn\HookNotification\Core\Platforms\Module\Domain\ModuleSettings;

class ChatwootSettings extends AbstractPlatformSettings
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
        public ?LiveChatSettings $liveChatSettings,
        // Module Settings
        public ?ModuleSettings $moduleSettings,
        public ?int $accountId = null,
    ) {
    }
}
