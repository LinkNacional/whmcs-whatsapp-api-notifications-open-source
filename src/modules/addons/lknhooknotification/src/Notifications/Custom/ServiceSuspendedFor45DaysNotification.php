<?php

/**
 * Code: ServiceSuspendedFor45Days
 */

namespace Lkn\HookNotification\Notifications\Custom;

use DateInterval;
use DateTime;
use Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory;
use Lkn\HookNotification\Core\Notification\Domain\AbstractCronNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;
use WHMCS\Database\Capsule;

final class ServiceSuspendedFor45DaysNotification extends AbstractCronNotification
{
    public function __construct()
    {
        parent::__construct(
            'ServiceSuspendedFor45Days',
            NotificationReportCategory::ORDER,
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
            fn() => $this->whmcsHookParams['client_id'],
            fn() => $this->whmcsHookParams['report_category_id'],
        );
    }

    public function getPayload(): array
    {
        $currentDate = new DateTime();
        $currentDate->sub(new DateInterval('P45D'));
        $formattedDate = $currentDate->format('Y-m-d');

        /** @var \stdClass[] $suspendedServices */
        $suspendedServices = Capsule::table('tblhosting')
            ->leftJoin('tblproducts', 'tblproducts.id', '=', 'tblhosting.packageid')
            ->where('tblhosting.nextduedate', $formattedDate)
            ->whereIn('tblproducts.type', ['hostingaccount', 'other'])
            ->get(['tblhosting.id as serviceId', 'tblhosting.userid as clientId']);

        $payloads = [];

        foreach ($suspendedServices as $service) {
            $serviceId = $service->serviceId;
            $clientId  = $service->clientId;

            $payloads[] = [
                'client_id' => $clientId,
                'service_id' => $serviceId,
                'report_category_id' => $serviceId,
            ];
        }

        return $payloads;
    }
}
