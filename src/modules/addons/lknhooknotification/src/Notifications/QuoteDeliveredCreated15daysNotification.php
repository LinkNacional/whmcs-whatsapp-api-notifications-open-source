<?php

/**
 * Code: QuoteDeliveredCreated15days
 */

use Illuminate\Support\Carbon;
use Lkn\HookNotification\Core\Notification\Domain\AbstractCronNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;
use WHMCS\Config\Setting;
use WHMCS\Database\Capsule;

final class QuoteDeliveredCreated15daysNotification extends AbstractCronNotification
{
    public function __construct()
    {
        parent::__construct(
            'QuoteDeliveredCreated15days',
            null,
            Hooks::DAILY_CRON_JOB,
            New NotificationParameterCollection([
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
                    'quote_id',
                    lkn_hn_lang('quote id'),
                    fn(): int => $this->whmcsHookParams['quote_id']
                ),
                new NotificationParameter(
                    'link_pdf',
                    lkn_hn_lang('link pdf'),
                    fn(): string => $this->whmcsHookParams['link_pdf']
                ),
                new NotificationParameter(
                    'link_quote',
                    lkn_hn_lang('link quote'),
                    fn(): string => $this->whmcsHookParams['link_quote']
                )
            ]),
            fn(): int => $this->whmcsHookParams['quote_id']
        );
    }

    public function getPayload(): array
    {
        $fifteenDaysAgo = Carbon::now()->subDays(15)->toDateString();

        $quoteDelivered = Capsule::table('tblquotes')
        ->join('tblclients', 'tblquotes.userid', '=', 'tblclients.id')
        ->whereDate('tblquotes.datecreated', $fifteenDaysAgo)
        ->where('tblquotes.stage', 'Delivered')
        ->get(['tblquotes.id as quote_id', 'tblclients.id as client_id', 'tblclients.firstname as clientFirstName']);

        if(empty($quoteDelivered)){
            return [];
        }

        $payloads =[];

        foreach($quoteDelivered as $quote){
            $clientId = $quote->client_id;
            $quoteId = $quote->quote_id;
            $clientFirstName = $quote->clientFirstName;
            $linkPDF = Setting::getValue('SystemURl').'dl.php?type=q&id='.$quoteId;
            $linkQuote = Setting::getValue('SystemURl').'viewquote.php?id='.$quoteId;

            $payloads[] = [
                'client_id' => $clientId,
                'quote_id' => $quoteId,
                'client_first_name' => $clientFirstName,
                'link_pdf' => $linkPDF,
                'linkQuote' => $linkQuote
            ];
        }
        return $payloads;
    }
}