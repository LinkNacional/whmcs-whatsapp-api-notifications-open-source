<?php
/**
 * Code: DomainRenewal5DaysBefore
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\DomainRenewal5DaysBefore;

use DateTime;
use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Config\Platforms;
use Lkn\HookNotification\Config\ReportCategory;
use Lkn\HookNotification\Config\Settings;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;
use Lkn\HookNotification\Helpers\Config;
use Lkn\HookNotification\Helpers\Logger;
use Lkn\HookNotification\Notifications\Chatwoot\WhatsAppPrivateNote\WhatsAppPrivateNoteNotification;
use Throwable;
use WHMCS\Database\Capsule;

final class DomainRenewal5DaysBeforeNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'DomainRenewal5DaysBefore';
    public Hooks|array|null $hook = Hooks::DAILY_CRON_JOB;

    public function run(): bool
    {
        // Disable the event of sending a private note to Chatwoot, which is by default for registered clients.
        $this->events = [];
        $this->enableAutoReport = false;

        // Setup properties for reporting purposes (not required).
        $this->setReportCategory(ReportCategory::DOMAIN);

        $threeDaysLater = (new DateTime())->modify('+3 days');

        $domainsDueInThreeDays = Capsule::table('tbldomains')
            ->where('nextduedate', $threeDaysLater->format('Y-m-d'))
            ->get(['id', 'userid', 'domain'])
            ->toArray();

        foreach ($domainsDueInThreeDays as $domain) {
            $domainId = $domain->id;
            $clientId = $domain->userid;
            $domainUrl = $domain->domain;

            $this->setReportCategoryId($domainId);

            // Setup client ID for getting its WhatsApp number (required).
            $this->setClientId($clientId);

            $this->setHookParams([
                'domain_id' => $domainId,
                'domain_url' => $domainUrl,
                'client_id' => $clientId
            ]);

            try {
                // Send the message and get the raw response (converted to array) from WhatsApp API.
                $response = $this->sendMessage();

                // Defines if response tells if the message was sent successfully.
                $success = isset($response['messages'][0]['id']);

                $this->report($success);

                if (
                    $success
                    && class_exists('Lkn\HookNotification\Notifications\Chatwoot\WhatsAppPrivateNote\WhatsAppPrivateNoteNotification')
                    && Config::get(Platforms::CHATWOOT, Settings::CW_LISTEN_WHATSAPP)
                ) {
                    (new WhatsAppPrivateNoteNotification(['instance' => $this]))->run();
                }
            } catch (Throwable $th) {
                $this->report(false);

                Logger::log(
                    "{$this->getNotificationLogName()} error for domain {$domainId}",
                    [
                        'msg' => 'Unable to send notification for this domain.',
                        'context' => ['order' => $domain]
                    ],
                    [
                        'response' => $response,
                        'error' => $th->__toString()
                    ]
                );
            }
        }

        return true;
    }

    public function defineParameters(): void
    {
        $this->parameters = [
            'domain_id' => [
                'label' => $this->lang['domain_id'],
                'parser' => fn () => $this->hookParams['domain_id']
            ],
            'domain_url' => [
                'label' => $this->lang['domain_url'],
                'parser' => fn () => $this->hookParams['domain_url']
            ],
            'client_first_name' => [
                'label' => $this->lang['client_first_name'],
                'parser' => fn () => $this->getClientFirstNameByClientId($this->clientId),
            ],
            'client_full_name' => [
                'label' => $this->lang['client_full_name'],
                'parser' => fn () => $this->getClientFullNameByClientId($this->clientId),
            ]
        ];
    }
}
