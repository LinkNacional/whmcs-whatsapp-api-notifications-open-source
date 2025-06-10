<?php

/**
 * ServiceCheckAfter3Months 
 */

namespace Lkn\HookNotification\Notifications\Custom;

use DateTime;
use Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory;
use Lkn\HookNotification\Core\Notification\Domain\AbstractCronNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;
use WHMCS\Database\Capsule;

final class ServiceCheckAfter3Months extends AbstractCronNotification
{
    public function __construct()
    {
        parent::__construct(
            'ServiceCheckAfter3Months',
            NotificationReportCategory::SERVICE,
            Hooks::DAILY_CRON_JOB,
            new NotificationParameterCollection([
                new NotificationParameter(
                    'client_id',
                    lkn_hn_lang('client id'),
                    fn(): int => $this->whmcsHookParams['client_id']
                ),
                new NotificationParameter(
                    'client_first_name',
                    lkn_hn_lang('client first name'),
                    fn(): string => $this->whmcsHookParams['client_first_name']
                ),
                new NotificationParameter(
                    'client_full_name',
                    lkn_hn_lang('client full name'),
                    fn(): string => $this->whmcsHookParams['client_full_name']
                )
                ]),
            fn() => $this->whmcsHookParams['client_id']
        );
    }
    public function getPayload(): array
    {
        $currentDate = new DateTime;
        $currentDate = $currentDate->format(Capsule::table('tblconfiguration')
            ->where('setting', 'DateFormat')->value());
 
        return [];
    } 
}