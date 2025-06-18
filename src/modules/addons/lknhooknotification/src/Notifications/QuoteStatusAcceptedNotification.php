<?php

/**
 * Code: QuoteStatusAccepted
 */

use Lkn\HookNotification\Core\Notification\Domain\AbstractNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;

final class QuoteStatusAcceptedNotification extends AbstractNotification
{
    public function __construct()
    {
        parent::__construct(
            'QuoteStatusAccepted',
            null,
            Hooks::QUOTE_STATUS_CHANGE,
            new NotificationParameterCollection([
                new NotificationParameter(
                    'quote_id',
                    lkn_hn_lang('quote id'),
                    fn(): int => $this->whmcsHookParams['quoteid']
                ),
                new NotificationParameter(
                    'status',
                    lkn_hn_lang('status'),
                    fn(): string => $this->whmcsHookParams['status']
                )
            ]),
            fn(): int => $this->whmcsHookParams['quoteid']
        );
    }

    public function shouldRun(): bool
    {
        if($this->whmcsHookParams['status'] == 'Accepted'){
            return true;
        }
        return false;
    }
}