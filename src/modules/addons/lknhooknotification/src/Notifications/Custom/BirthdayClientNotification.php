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
                    fn(): string => $this->whmcsHookParams['client_first_name']
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
    
    /**
     * Converts a date format string from a custom format (e."g., "YYYY/MM/DD)
     * to a PHP-compatible DateTime format (e.g., "Y/m/d").
     *
     * This function replaces common uppercase date placeholders ("YYYY", "MM", "DD")
     * with the corresponding PHP DateTime format characters ("Y", "m", "d").
     * All spaces are removed from the input string.
     *
     * The function also validates that the resulting format contains only valid 
     * PHP DateTime format characters (Y, m, d) and allowed separators (/ - .).
     * If the resulting string includes any other characters, the function returns null.
     *
     * Example:
     *   normalizeFormat("YYYY-MM-DD") returns "Y-m-d"
     *   normalizeFormat("random string") returns null
     *
     * @param  string $formatFromDB  The original date format string (e.g., "YYYY/MM/DD")
     * @return string|null           The normalized format compatible with PHP DateTime (e.g., "Y/m/d"), or null if invalid
     */

    public function normalizeFormat(string $formatFromDB): ?string
    {
        $normalized =  str_replace(
        ['DD', 'MM', 'YYYY', ' '],
        ['d',  'm',  'Y', ''],
        strtoupper($formatFromDB));

        if(!preg_match('/^[Ymd\/\.\-]+$/', $normalized)) return null;
        
        return $normalized;
    }

    /**
     * Retrieves the current date format configuration from the database,
     * normalizes it, and returns the matching format settings (day and month positions).
     *
     * The method compares the normalized format against a predefined list
     * of supported formats. If a match is found, it returns the corresponding
     * day and month position identifiers. Otherwise, it returns null.
     *
     * How to add a new date format:
     * - Add a new array to the $formats list.
     * - Set the 'formatType' in PHP DateTime format style (e.g., "Y.m.d").
     * - Specify the corresponding 'dayId' and 'monthId' values.
     * 
     * Example:
     * ['formatType' => 'm.Y.d', 'dayId' => 9, 'monthId' => 6]
     * 
     * @return array|null  The matched date format configuration or null if not found
    */
    public function getDateFormat(): ?array
    {    
        $formats = [
        ['formatType' => 'd/m/Y', 'dayId' => 1, 'monthId' =>4 ],
        ['formatType' => 'd-m-Y', 'dayId' => 1, 'monthId' =>4 ],
        ['formatType' => 'Y-m-d', 'dayId' => 9, 'monthId' =>6 ],
        ['formatType' => 'm/d/Y', 'dayId' => 4, 'monthId' =>1 ],
        ['formatType' => 'd.m.Y', 'dayId' => 1, 'monthId' =>4 ],
        ['formatType' => 'Y/m/d', 'dayId' => 9, 'monthId' =>6 ],
        ['formatType' => 'Y.m.d', 'dayId' => 9, 'monthId' =>6 ],
        ];

        $dateFormatDB = Capsule::table('tblconfiguration')
        ->where('setting', 'DateFormat')
        ->value('value');

        if (Empty($dateFormatDB)) return null;

        $normalizeFormat = $this->normalizeFormat($dateFormatDB);

        if($normalizeFormat === null) return null;

        foreach($formats as $format){
            if($format['formatType'] === $normalizeFormat){
                return $format;
            }
        }

        return null;
    }

    /**
     * Get the custom field ID used to store client birthdates.
     *
     * This function first attempts to match known field names exactly using a predefined list.
     * If no match is found, it will fallback to using a regex pattern to try and identify
     * the field by keywords commonly used for birthdates.
     *
     * To add support for new field names:
     * - Add the new label to the `$expectedBirthdateFieldLabels` array for exact match.
     * - Add new keywords to the `$regexBirthdateFieldLabel` pattern for partial match support.
     *
     * @return int|null Returns the field ID if found, or null if not found.
     */
    public function getBirthdateFieldId(): ?int
    {
        $expectedBirthdateFieldLabels = [
            'Birthdate',
            'Date of Birth',
            'DOB',
            'Birthday',
            'Data de Nascimento',
            'Fecha de Nacimiento',
            'Date de Naissance',
            'Geburtsdatum',
            'Data di Nascita'
        ];

        $regexBirthdateFieldLabel ='/\b(birth(date)?|dob|nascimento|naissance|nascita|nacimiento|geburt|birthday)\b/i';
        
        $birthdateFieldId = Capsule::table('tblcustomfields')
        ->where('type', 'client')
        ->whereIn('fieldname', $expectedBirthdateFieldLabels)
        ->value('id');
        if(empty($birthdateFieldId) || !is_numeric($birthdateFieldId)){
            $customFields= Capsule::table('tblcustomfields')
            ->where('type','client')
            ->get();

            foreach($customFields as $field){
                if(preg_match($regexBirthdateFieldLabel, $field->fieldname)){
                    $birthdateFieldId = $field->id;
                    return (int) $birthdateFieldId;
                }   
            }
            
            return null;
        }

        return (int) $birthdateFieldId;
    }

    public function getPayload(): array
    {   
        $currentDate = new dateTime();
        $currentDayString = $currentDate->format("d");
        $currentMonthString = $currentDate->format("m");

        $formatDateDB = $this->getDateFormat();
        
        if(empty($formatDateDB)) return [];
        
        $birthdateFieldId = $this->getBirthdateFieldId();

        if(empty($birthdateFieldId)) return [];

        $birthdayClients = Capsule::table('tblclients')
        ->join('tblcustomfieldsvalues', 'tblclients.id', '=', 'tblcustomfieldsvalues.relid')
        ->join('tblcustomfields', 'tblcustomfields.id', '=', 'tblcustomfieldsvalues.fieldid')
        ->where('tblcustomfields.type', 'client')
        ->where('tblcustomfields.id', $birthdateFieldId)
        ->whereRaw('SUBSTRING(tblcustomfieldsvalues.value,'.$formatDateDB['monthId'].', 2) = ?',  $currentMonthString)
        ->whereRaw('SUBSTRING(tblcustomfieldsvalues.value,'.$formatDateDB['dayId'].', 2) = ?', $currentDayString)
        ->get(['tblclients.id as clientId']);

        if (Empty($birthdayClients)) return [];

        foreach($birthdayClients as $client){
            if(!is_numeric($client->clientId)) return [];
        }
        
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