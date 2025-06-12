<?php

/**
 * Code: ServiceCheckAfter3Months
 */

use Carbon\Carbon;
use Lkn\HookNotification\Core\Notification\Domain\AbstractCronNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;
use WHMCS\Database\Capsule;

final class ServiceCheckAfter3MonthsNotification extends AbstractCronNotification
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
                    fn (): int => $this->whmcsHookParams['client_id']
                ),
                new NotificationParameter(
                    'client_first_name',
                    lkn_hn_lang('Client first name'),
                    fn (): int => $this->whmcsHookParams['client_first_name']
                ),
                new NotificationParameter(
                    'client_full_name',
                    lkn_hn_lang('Client full name'),
                    fn (): int => $this->whmcsHookParams['client_full_name']
                ),
            ]),
            fn () => $this->whmcsHookParams['client_id'],
        );
    }

    public function getPayload(): array
    {
        $threeMonthsAgo = Carbon::now()->subMonths(3)->toDateString();

        $services = Capsule::table('tblhosting')
            ->join('tblclients', 'tblhosting.userid', '=', 'tblclients.id')
            ->where('tblhosting.regdate', '=', $threeMonthsAgo)
            ->where('tblhosting.domainstatus', 'Active')
            ->get([
                'tblhosting.id as service_id',
                'tblclients.id as client_id',
                'tblclients.firstname as client_first_name',
                'tblclients.lastname as client_last_name'
            ]);

        if (empty($services)) {
            return [];
        }

        foreach ($services as $service) {
            if (!is_numeric($service->service_id) || !is_numeric($service->client_id)) {
                return [];
            }
        }

        $payload = [];

        foreach ($services as $service) {
            $client_id = $service->client_id;
            $service_id = $service->service_id;
            $client_first_name = $service->client_first_name;
            $client_full_name = "$service->client_first_name $service->client_last_name";

            $payload[] = [
                'service_id' => $service_id,
                'client_id' => $client_id,
                'client_first_name' => $client_first_name,
                'client_full_name' => $client_full_name
            ];
        }

        return $payload;
    }
}
