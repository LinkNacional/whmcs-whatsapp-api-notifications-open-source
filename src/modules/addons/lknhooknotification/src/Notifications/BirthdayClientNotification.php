<?php

/**
 * Code: BirthdayClientNotification
 */

namespace Lkn\HookNotification\Notifications\Custom;

use DateTime;
use Lkn\HookNotification\Core\Notification\Domain\AbstractCronNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;
use WHMCS\Database\Capsule;

final class BirthdayClientNotification extends AbstractCronNotification{

    public function __construct()
    {
        parent::__construct(
            'BirthdayClientNotification',
            null,
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
                ),
            ]),
            fn(): int => $this->whmcsHookParams['client_id']
        );
    }

    public function getPayload(): array
    {
        echo '<hr>: ' . 'ping' . '<hr>';
        $formats = [
            'DDMMYYYY' => ['dayId' => 1, 'monthId' =>4 ],
            'MMDDYYYY' => ['dayId' => 4, 'monthId' =>1 ],
            'YYYYMMDD' => ['dayId' => 9, 'monthId' =>6 ],
            'DDYYYYMM' => ['dayId' => 1, 'monthId' =>9 ],
            'MMYYYYDD' => ['dayId' => 9, 'monthId' =>1 ],
            'YYYYDDMM' => ['dayId' => 6, 'monthId' =>9 ],
        ];

        $dateFormatFromDB = Capsule::table('tblconfiguration')
            ->where('setting', 'DateFormat')
            ->value('value');

        if (empty($dateFormatFromDB)) {
            return [];
        }

        $dateFormatKey = str_replace(
            ['/', '-', '.', ' '],
            ['',  '',  '', ''],
            strtoupper($dateFormatFromDB)
        );

        if (!preg_match('/^[YMD]+$/', $dateFormatKey)) {
            return [];
        }

        $format = $formats[$dateFormatKey];

        $currentDate        = new DateTime();
        $currentDayString   = $currentDate->format('d');
        $currentMonthString = $currentDate->format('m');

        /** @var null|int $birthdateFieldId */
        $birthdateFieldId = lkn_hn_config(Settings::BD_CUSTOM_FIELD_ID);

        if (empty($birthdateFieldId)) {
            return [];
        }

        $birthdayClients = Capsule::table('tblclients')
            ->join('tblcustomfieldsvalues', 'tblclients.id', '=', 'tblcustomfieldsvalues.relid')
            ->join('tblcustomfields', 'tblcustomfields.id', '=', 'tblcustomfieldsvalues.fieldid')
            ->where('tblcustomfields.type', 'client')
            ->where('tblcustomfields.id', $birthdateFieldId)
            ->whereRaw('SUBSTRING(tblcustomfieldsvalues.value,'.$format['monthId'].', 2) = ?', $currentMonthString)
            ->whereRaw('SUBSTRING(tblcustomfieldsvalues.value,'.$format['dayId'].', 2) = ?', $currentDayString)
            ->get(['tblclients.id as clientId', 'tblclients.firstname as clientFirstName','tblclients.lastname as clientLastName']);

        if (empty($birthdayClients)) {
            return [];
        }

        foreach ($birthdayClients as $client) {
            if (!is_numeric($client->clientId)) {
                return [];
            }
        }

        $payloads = [];

        foreach ($birthdayClients as $bdClient) {
            $clientId        = $bdClient->clientId;
            $clientFirstName = $bdClient->clientFirstName;
            $clientFullName  = "$bdClient->clientFirstName $bdClient->clientLastName";

            $payloads[] = [
                'client_id' => $clientId,
                'client_first_name' => $clientFirstName,
                'client_full_name' =>$clientFullName,
            ];
        }

        return $payloads;
    }
}
