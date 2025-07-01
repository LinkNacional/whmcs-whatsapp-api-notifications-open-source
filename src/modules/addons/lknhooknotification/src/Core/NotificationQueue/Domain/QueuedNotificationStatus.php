<?php

namespace Lkn\HookNotification\Core\NotificationQueue\Domain;

enum QueuedNotificationStatus: string
{
    case SENT = 'sent';

    /**
     * Waiting to be sent.
     */
    case WAITING = 'waiting';
    case ERROR   = 'error';
    case RESENT  = 'resent';
    case ABORTED = 'aborted';

    public function label(): string
    {
        return match ($this) {
            self::SENT => lkn_hn_lang('Sent'),
            self::WAITING => lkn_hn_lang('Waiting'),
            self::ERROR => lkn_hn_lang('Error'),
            self::RESENT => lkn_hn_lang('Resent'),
            self::ABORTED => lkn_hn_lang('Aborted'),
        };
    }
}
