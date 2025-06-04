<?php

/**
 * Code: BirthdayClientNotification
 */

namespace Lkn\HookNotification\Notifications\Custom;

use DateTime;
use Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory;
use Lkn\HookNotification\Core\Notification\Domain\AbstractCronNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;
use WHMCS\Database\Capsule;

final class BirthdayClientNotification extends AbstractCronNotification{
    public function __construct()
    {
        parent::__construct(
            'BirthdayClientNotification',
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
                    lkn_hn_lang('client_id'),
                    fn(): int => $this->whmcsHookParams['client_id']
                ),
                new NotificationParameter(
                    'client_first_name',
                    lkn_hn_lang('client_first_name'),
                    fn(): string => $this->whmcsHookParams['client_fisrt_name']
                ),
                new NotificationParameter(
                    'client_full_name',
                    lkn_hn_lang('client_full_name'),
                    fn(): string => $this->whmcsHookParams['client_full_name']
                )
                ]),
                fn(): int => $this->whmcsHookParams['client_id']
        ); 
        
    }

    public function getPayload(): array
    {   
        $currentDate = new dateTime();
        $currentDayString = $currentDate->format("d");
        $currentMonthString = $currentDate->format("m");


        $birthdayClients = Capsule::table('tblclients')
        ->join('tblcustomfieldsvalues', 'tblclients.id', '=', 'tblcustomfieldsvalues.relid')
        ->join('tblcustomfields', 'tblcustomfields.id', '=', 'tblcustomfieldsvalues.fieldid')
        ->where('tblcustomfields.type', 'client')
        ->where('tblcustomfields.fieldname', 'Birthdate')
        ->whereRaw('SUBSTRING(tblcustomfieldsvalues.value, 4, 2) = ?',  $currentMonthString)
        ->whereRaw('SUBSTRING(tblcustomfieldsvalues.value, 1, 2) = ?', $currentDayString)
        ->get(['tblclients.id as clientId']);

        
        $payloads = [];
        
        foreach($birthdayClients as $bdClient){
            $clientId = $bdClient->clientId;
            $payloads[] = [
                'clientId' => $clientId
            ];
        }

        return $payloads;
    }
}
