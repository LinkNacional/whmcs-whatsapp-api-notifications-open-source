<?php

/**
 * Code: UserPasswordChangeConfirmed
 */

use Lkn\HookNotification\Core\Notification\Domain\AbstractNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;
use WHMCS\Database\Capsule;

final class UserPasswordChangeConfirmedNotication extends AbstractNotification
{
    public function __construct()
    {
        $parameters =[
            new NotificationParameter(
                    'client_id',
                    lkn_hn_lang('client id'),
                    fn(): int =>(int) $this->whmcsHookParams['userid']
            ),
            new NotificationParameter(
                    'client_first_name',
                    lkn_hn_lang('client first name'),
                    fn(): string => getClientFirstNameByClientId($this->getClientId($this->whmcsHookParams['userid']))
            ),
            new NotificationParameter(
                    'client_full_name',
                    lkn_hn_lang('client full name'),
                    fn(): string => getClientFullNameByClientId($this->getClientId($this->whmcsHookParams['userid']))
            )
        ];
        parent::__construct(
            'UserPasswordChangeConfirmed',
            null,
            Hooks::USER_CHANGE_PASSWORD,
            New NotificationParameterCollection($parameters),
            fn():int =>(int) $this->getClientId($this->whmcsHookParams['userid'])
        );
    }

    private function getClientId(int $userId){
        return Capsule::table('tblusers_clients')->where('auth_user_id', $userId)->value('clien_id');
    }
}