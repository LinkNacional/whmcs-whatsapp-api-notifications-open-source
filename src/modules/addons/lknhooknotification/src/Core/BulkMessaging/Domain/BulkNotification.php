<?php

namespace Lkn\HookNotification\Core\BulkMessaging\Domain;

use Lkn\HookNotification\Core\Notification\Domain\AbstractNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;
use WHMCS\Database\Capsule;

final class BulkNotification extends AbstractNotification
{
    public function __construct()
    {
        parent::__construct(
            'BulkNotification',
            null,
            Hooks::BULK,
            new NotificationParameterCollection([
                new NotificationParameter(
                    'service_id_name_status',
                    lkn_hn_lang('Service ID + Name + Status'),
                    fn (): string => $this->paramServiceIdNameStatus()
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
                    fn (): string=> getClientFullNameByClientId($this->client->id)
                ),
            ]),
            fn() => $this->whmcsHookParams['client_id'],
            null
        );
    }

    private function paramServiceIdNameStatus(): string
    {
        $servicesStr = '';

        $services = Capsule::table('tblhosting')
            ->join('tblproducts', 'tblhosting.packageid', '=', 'tblproducts.id')
            ->whereIn('tblhosting.id', $this->whmcsHookParams['services'])
            ->get([
                'tblhosting.id',
                'tblproducts.name',
                'tblhosting.domainstatus',
            ]);

        foreach ($services as $service) {
            $servicesStr .= '#' . $service->id . " ($service->domainstatus) " . $service->name . ', ';
        }

        $servicesStr = rtrim($servicesStr, ', ');

        return $servicesStr;
    }
}
