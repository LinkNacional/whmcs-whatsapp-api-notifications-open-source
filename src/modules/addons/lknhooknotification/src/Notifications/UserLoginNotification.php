<?php
/**
 * code: UserLoginNotification
 */

use Lkn\HookNotification\Core\Notification\Domain\AbstractNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;


final class UserLoginNotification extends AbstractNotification
{  
    public function __construct(){
        parent::__construct(
            'UserLoginNotification',
            null,
            Hooks::USER_LOGIN,
            new NotificationParameterCollection([
                new NotificationParameter(
                    'client_id',
                    lkn_hn_lang('client id'),
                    fn(): int => $this->whmcsHookParams['user']->id
                ),
                new NotificationParameter(
                    'client_first_name',
                    lkn_hn_lang('client first name'),
                    fn(): string => $this->whmcsHookParams['user']->fisrt_name
                ),
                new NotificationParameter(
                    'client_full_name',
                    lkn_hn_lang('client full name'),
                    fn(): string => $this->whmcsHookParams['user']->last_name
                )
                ]),
                fn():int =>(int) $this->whmcsHookParams['user']->id
        );
    }
}
