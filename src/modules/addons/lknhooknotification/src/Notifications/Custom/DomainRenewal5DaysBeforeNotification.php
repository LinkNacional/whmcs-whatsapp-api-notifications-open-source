<?php
/**
 * Code: DomainRenewal5DaysBefore
 */

namespace Lkn\HookNotification\Notifications\Custom;

use DateTime;
use Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportCategory;
use Lkn\HookNotification\Core\Notification\Domain\AbstractCronNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameter;
use Lkn\HookNotification\Core\Notification\Domain\NotificationParameterCollection;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;
use WHMCS\Database\Capsule;

final class DomainRenewal5DaysBeforeNotification extends AbstractCronNotification
{
    public function __construct()
    {
        parent::__construct(
            'DomainRenewal5DaysBefore',
            NotificationReportCategory::DOMAIN,
            Hooks::DAILY_CRON_JOB,
            new NotificationParameterCollection([
                new NotificationParameter(
                    'domain_id',
                    lkn_hn_lang('Domain ID'),
                    fn (): int => $this->whmcsHookParams['domain_id']
                ),
                new NotificationParameter(
                    'domain_url',
                    lkn_hn_lang('Domain URL'),
                    fn (): string => $this->whmcsHookParams['domain_url']
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
        $threeDaysLater = (new DateTime())->modify('+3 days');

        $domainsDueInThreeDays = Capsule::table('tbldomains')
            ->where('status', 'Active')
            ->where('nextduedate', $threeDaysLater->format('Y-m-d'))
            ->get(['id', 'userid', 'domain'])
            ->toArray();

        $payload = [];

        foreach ($domainsDueInThreeDays as $domain) {
            $domainId  = $domain->id;
            $clientId  = $domain->userid;
            $domainUrl = $domain->domain;

            $payload[] = [
                'report_category_id' => $domainId,
                'client_id' => $clientId,
                'domain_id' => $domainId,
                'domain_url' => $domainUrl,
            ];
        }

        return $payload;
    }
}
