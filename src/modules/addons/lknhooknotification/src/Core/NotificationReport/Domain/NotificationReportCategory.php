<?php

namespace Lkn\HookNotification\Core\NotificationReport\Domain;

/**
 * Used for specifying the domain of the notification for reporting puporses.
 *
 * @since  3.2.0
 */
enum NotificationReportCategory: string
{
    case INVOICE = 'invoice';
    case TICKET  = 'ticket';
    case SERVICE = 'service';
    case ORDER   = 'order';
    case DOMAIN  = 'domain';

    public function label(): string
    {
        return match ($this) {
            self::INVOICE => lkn_hn_lang('Invoice'),
            self::TICKET => lkn_hn_lang('Ticket'),
            self::SERVICE => lkn_hn_lang('Service'),
            self::ORDER => lkn_hn_lang('Order'),
            self::DOMAIN => lkn_hn_lang('Domain'),
        };
    }
}
