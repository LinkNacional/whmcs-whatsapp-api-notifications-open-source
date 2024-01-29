<?php

/**
 * Code: ServiceSuspendedFor45Days
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\ServiceSuspendedFor45Days;

use DateInterval;
use DateTime;
use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Config\ReportCategory;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;
use WHMCS\Database\Capsule;

final class ServiceSuspendedFor45DaysNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'ServiceSuspendedFor45Days';
    public Hooks|array|null $hook = Hooks::DAILY_CRON_JOB;

    public function run(): bool
    {
        $this->setReportCategory(ReportCategory::SERVICE);

        $currentDate = new DateTime();
        $currentDate->sub(new DateInterval('P45D'));
        $formattedDate = $currentDate->format('Y-m-d');

        $suspendedServices = Capsule::table('tblhosting')
            ->leftJoin('tblproducts', 'tblproducts.id', '=', 'tblhosting.packageid')
            ->where('tblhosting.nextduedate', $formattedDate)
            ->whereIn('tblproducts.type', ['hostingaccount', 'other'])
            ->get(['tblhosting.id as serviceId', 'tblhosting.userid as clientId']);

        foreach ($suspendedServices as $service) {
            $serviceId = $service->serviceId;
            $clientId = $service->clientId;

            // Setup properties for reporting purposes (not required).
            $this->setReportCategoryId($serviceId);
            $this->setHookParams(['serviceId' => $serviceId, 'clientId' => $clientId]);

            // Setup client ID for getting its WhatsApp number (required).
            $this->setClientId($clientId);

            // Send the message and get the raw response (converted to array) from WhatsApp API.
            $response = $this->sendMessage();

            // Defines if response tells if the message was sent successfully.
            $success = isset($response['messages'][0]['id']);

            return $success;
        }
    }

    public function defineParameters(): void
    {
        $this->parameters = [
            'service_id' => [
                'label' => $this->lang['service_id'],
                'parser' => fn () => $this->hookParams['serviceId']
            ],
            'client_first_name' => [
                'label' => $this->lang['client_first_name'],
                'parser' => fn () => $this->getClientFirstNameByClientId($this->clientId)
            ],
            'client_full_name' => [
                'label' => $this->lang['client_full_name'],
                'parser' => fn () => $this->getClientFullNameByClientId($this->clientId)
            ]
        ];
    }
}
