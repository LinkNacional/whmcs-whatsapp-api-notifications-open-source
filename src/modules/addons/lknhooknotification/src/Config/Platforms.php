<?php

namespace Lkn\HookNotification\Config;

enum Platforms: string
{
    case WHATSAPP = 'WhatsApp';
    case CHATWOOT = 'Chatwoot';
    case MODULE = 'module';
    case ALL = 'all';

    public function value(): string
    {
        return strtolower($this->value);
    }
}
