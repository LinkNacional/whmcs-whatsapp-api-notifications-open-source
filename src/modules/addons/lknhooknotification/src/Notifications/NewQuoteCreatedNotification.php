<?php

/**
 * Code: NewQuoteCreated
 */

use Lkn\HookNotification\Core\Notification\Domain\AbstractNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;
use WHMCS\Config\Setting;

final class NewQuoteCreatedNotification extends AbstractNotification
{
    public function __construct()
    {
        parent::__construct(
            'NewQuoteCreated',
            null,
            Hooks::QUOTE_CREATED,
            new NotificationParameterCollection([
                new NotificationParameter(
                    'quote_id',
                    lkn_hn_lang('quote id'),
                    fn(): int => $this->whmcsHookParams['quoteid']
                ),
                new NotificationParameter(
                    'link_pdf',
                    lkn_hn_lang('link pdf'),
                    fn(): string => Setting::getValue('SystemURl').'dl.php?type=q&id='.$this->whmcsHookParams['quoteid']
                ),
                new NotificationParameter(
                    'link_quote',
                    lkn_hn_lang('link quote'),
                    fn(): string => Setting::getValue('SystemURl').'viewquote.php?id='.$this->whmcsHookParams['quoteid']
                )
                ]),
            fn(): int => $this->whmcsHookParams['quoteid']
        );
    }
}