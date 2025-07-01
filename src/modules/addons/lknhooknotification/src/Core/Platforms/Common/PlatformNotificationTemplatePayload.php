<?php

namespace Lkn\HookNotification\Core\Platforms\Common;

interface PlatformNotificationTemplatePayload
{
    public function fromArray(array $data): static;

    public function toArray(): array;
}
