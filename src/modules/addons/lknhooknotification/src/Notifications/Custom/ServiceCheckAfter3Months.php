<?php

/**
 * Code: ServiceCheckAfter3Months
 */

use Lkn\HookNotification\Core\Notification\Domain\AbstractCronNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;

final class ServiceCheckAfter3Months extends AbstractCronNotification
{
    public function __construct()
    {
        parent::__construct(
            'ServiceCheckAfter3Months',
            null,
            Hooks::DAILY_CRON_JOB,
            new NotificationParameterCollection([
                new NotificationParameter(
                    'service_id',
                    lkn_hn_lang('service_id'),
                    fn (): int => $this->whmcsHookParams['service_id']
                ),
                new NotificationParameter(
                    'client_id',
                    lkn_hn_lang('Client ID'),
                    fn (): int => $this->client->id
                ),
                new NotificationParameter(
                    'client_email',
                    lkn_hn_lang('Client email'),
                    fn (): string => getClientEmailByClientId($this->client->id)
                ),
                new NotificationParameter(
                    'client_first_name',
                    lkn_hn_lang('Client first name'),
                    fn (): string => getClientFirstNameByClientId($this->client->id)
                ),
                new NotificationParameter(
                    'client_full_name',
                    lkn_hn_lang('Client full name'),
                    fn (): string => getClientFullNameByClientId($this->client->id)
                ),
            ]),
            fn() => $this->whmcsHookParams['client_id']
        );
    } 

    public function getPayload(): array
    {
        return[];
    }
}