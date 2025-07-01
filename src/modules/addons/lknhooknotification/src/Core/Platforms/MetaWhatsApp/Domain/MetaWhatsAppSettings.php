<?php

namespace Lkn\HookNotification\Core\Platforms\MetaWhatsApp\Domain;

use Lkn\HookNotification\Core\Platforms\Common\AbstractPlatformSettings;

class MetaWhatsAppSettings extends AbstractPlatformSettings
{
    public function __construct(
        public readonly ?bool $enabled,
        public readonly ?string $userAccessToken,
        public readonly ?string $businessAccountId,
        public readonly ?string $phoneNumberId,
        public readonly ?string $apiVersion,
        public ?int $wpCustomFieldId,
        public ?bool $showInvoiceReminderBtn,
        public ?int $wpCustomFieldIdForTicket,
        public ?string $defaultMsgTemplateLang,
    ) {
    }
}
