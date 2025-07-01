<?php

namespace Lkn\HookNotification\Core\Platforms\Module\Domain;

use Lkn\HookNotification\Core\Platforms\Common\AbstractPlatformSettings;

class ModuleSettings extends AbstractPlatformSettings
{
    public function __construct(
        public readonly ?string $language,
        public readonly ?string $lknLicense,
        public readonly ?string $defaultClientName,
        public readonly ?string $objectPagesToShowReports,
        public readonly ?string $enableLog,
    ) {
    }
}
