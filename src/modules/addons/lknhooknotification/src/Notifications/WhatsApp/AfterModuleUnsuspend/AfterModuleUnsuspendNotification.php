<?php

/**
 * Code: AfterModuleUnsuspend
 */

namespace Lkn\HookNotification\Notifications\WhatsApp\AfterModuleUnsuspend;

use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Config\ReportCategory;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation;

final class AfterModuleUnsuspendNotification extends AbstractWhatsAppNotifcation
{
    public string $notificationCode = 'AfterModuleUnsuspend';
    public Hooks|array|null $hook = Hooks::AFTER_MODULE_UNSUSPEND;

    public function run(): bool
    {
        $this->events = [];
        $this->enableAutoReport = false;

        // Setup properties for reporting purposes (not required).
        $this->setReportCategory(ReportCategory::SERVICE);
        $this->setReportCategoryId($this->hookParams['params']['serviceid']);

        // Setup client ID for getting its WhatsApp number (required).
        $this->setClientId($this->hookParams['params']['userid']);

        // Send the message and get the raw response (converted to array) from WhatsApp API.
        $response = $this->sendMessage();

        // Defines if response tells if the message was sent successfully.
        $success = isset($response['messages'][0]['id']);

        return $success;
    }

    public function defineParameters(): void
    {
        $this->parameters = [
            'service_id' => [
                'label' => $this->lang['service_id'],
                'parser' => fn () => $this->hookParams['params']['serviceid']
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
