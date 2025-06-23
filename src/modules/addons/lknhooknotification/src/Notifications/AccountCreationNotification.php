<?php

/**
 * Code: NotificationForAccountCreation
 */

namespace Lkn\HookNotification\Notifications\Custom;

use Lkn\HookNotification\Core\Notification\Domain\AbstractNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;

/*
* @see https://developers.whmcs.com/hooks-reference/client-area-interface/#clientarearegister
    
*/

final class AccountCreationNotification extends AbstractNotification 
{
    public function __construct()
    {
        parent::__construct(
            'AccountCreated',
            null,
            Hooks::CLIENT_ADD,
            new NotificationParameterCollection([
                new NotificationParameter(
                    'client_id',
                    lkn_hn_lang('Client Id'),
                    fn () : int => $this->whmcsHookParams['client_id']
                ),
                new NotificationParameter(
                    'client_name',
                    lkn_hn_lang('Client First Name'),
                    fn () : string => getClientFirstNameByClientId($this->whmcsHookParams['client_id'])
                ),
                new NotificationParameter(
                    'client_full_name',
                    lkn_hn_lang('Client Full Name'),
                    fn () : string => getClientFullNameByClientId($this->whmcsHookParams['client_id'])
                ),
                new NotificationParameter(
                    'client_email',
                    lkn_hn_lang('Client Email'),
                    fn () : string => getClientEmailByClientId($this->whmcsHookParams['client_id'])
                )
                ]),
            fn () : int => $this->whmcsHookParams['client_id']
        );
    }

}