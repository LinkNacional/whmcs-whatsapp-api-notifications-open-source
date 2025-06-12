<?php

/**
 * Code: ClientPasswordChangeConfirmed
 */

use Lkn\HookNotification\Core\Notification\Domain\AbstractNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;

final class ClientPasswordChangeConfirmedNotification extends AbstractNotification
{
    public function __construct()
    {
        
        parent::__construct(
            'ClientPasswordChangeConfirmed',
            null,
            Hooks::CLIENT_CHANGE_PASSWORD,
            New NotificationParameterCollection([
                new NotificationParameter(
                    'client_id',
                    lkn_hn_lang('client id'),
                    fn(): int =>(int) $this->whmcsHookParams['userid']
                ),
                new NotificationParameter(
                    'client_first_name',
                    lkn_hn_lang('client first name'),
                    fn(): string => getClientFirstNameByClientId($this->whmcsHookParams['userid'])
                ),
                new NotificationParameter(
                    'client_full_name',
                    lkn_hn_lang('client full name'),
                    fn(): string => getClientFullNameByClientId ($this->whmcsHookParams['userid'])
                    )
                ]),
                fn():int =>(int) $this->whmcsHookParams['userid']
            );
    }
}