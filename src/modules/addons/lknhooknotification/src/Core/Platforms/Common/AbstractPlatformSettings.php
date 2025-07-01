<?php

namespace Lkn\HookNotification\Core\Platforms\Common;

abstract class AbstractPlatformSettings
{
    public function __construct(
        public ?int $wpCustomFieldId = null
    ) {
    }
}
