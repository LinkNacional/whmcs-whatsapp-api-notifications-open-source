<?php
/**
 * Code: WhatsAppPrivateNote
 */

namespace Lkn\HookNotification\Notifications\Chatwoot\WhatsAppPrivateNote;

use Exception;
use Lkn\HookNotification\Config\Platforms;
use Lkn\HookNotification\Config\ReportCategory;
use Lkn\HookNotification\Config\Settings;
use Lkn\HookNotification\Domains\Platforms\Chatwoot\AbstractChatwootNotification;
use Lkn\HookNotification\Helpers\Config;
use Lkn\HookNotification\Helpers\Lang;
use Lkn\HookNotification\Helpers\Utils;
use Lkn\HookNotification\Helpers\WhmcsApi;

final class WhatsAppPrivateNoteNotification extends AbstractChatwootNotification
{
    public string $notificationCode = 'WhatsAppPrivateNote';
    public ?string $channel = 'wp';

    /**
     * For this notification to work properly, the hook params passed must be:
     *
     * [ instance => AbstractWhatsAppNotifcation|AbstractChatwootNotification ]
     *
     * @since 1.0.0
     * @var array
     */
    public array $hookParams;

    public function run(): bool
    {
        if (!Utils::isChatwootNotifEnabled($this->notificationCode)) {
            $this->enableAutoReport = false;

            return false;
        }

        return $this->sendMessage();
    }

    public function sendMessage(): array|bool
    {
        /**
         * @var \Lkn\HookNotification\Domains\Platforms\WhatsApp\AbstractWhatsAppNotifcation|\Lkn\HookNotification\Domains\Platforms\Chatwoot\AbstractChatwootNotification
         */
        $notificationInstance = $this->hookParams['instance'];

        /**
         * @var \Lkn\HookNotification\Config\ReportCategory
         */
        $reportCategory = $notificationInstance->reportCategory;
        $reportCategoryId = $notificationInstance->reportCategoryId;

        $this->setReportCategory($reportCategory);
        $this->setReportCategoryId($reportCategoryId);

        $clientId = $notificationInstance->clientId;
        $searchBy = $this->getClientWhatsAppNumber($clientId);

        $this->setClientId($clientId);

        $whatsAppInboxId = Config::get(Platforms::CHATWOOT, Settings::CW_WHATSAPP_INBOX_ID);

        if ($whatsAppInboxId === null) {
            throw new Exception('WhatsApp inbox ID setting is empty.');
        }

        $categoryUrl = $this->getUrlPathForObject(
            $reportCategory,
            $reportCategoryId,
            $clientId
        );

        $msg = Lang::text('notification') . ": {$notificationInstance->lang['notification_title']} [#{$reportCategoryId}]({$categoryUrl})";

        $response = $this->api->sendMessageToClient(
            $clientId,
            $whatsAppInboxId,
            $searchBy,
            $msg,
            true
        );

        $success = $response['success'];

        $this->report($success);

        return $success;
    }

    private function getUrlPathForObject(
        ReportCategory $object,
        string $objectId,
        int $clientId
    ) {
        $objectUrlPath = match ($object) {
            ReportCategory::TICKET => 'supporttickets.php?action=view&id=:objectId',
            ReportCategory::SERVICE => 'clientsservices.php?userid=:clientId&id=:objectId',
            ReportCategory::INVOICE => 'invoices.php?action=edit&id=:objectId',
            ReportCategory::ORDER => 'orders.php?action=view&id=:objectId',
            ReportCategory::DOMAIN => 'clientsdomains.php?userid=:clientId&id=:objectId'
        };

        $objectUrlPath = str_replace(':objectId', $objectId, $objectUrlPath);
        $objectUrlPath = str_replace(':clientId', $clientId, $objectUrlPath);

        return WhmcsApi::getAdminRootUrl($objectUrlPath);
    }

    public function settings(): array
    {
        if (empty($_POST)) {
            $savedSettings = $this->getSettings();

            $settings = [
                'private_note_mode' => $savedSettings['private_note_mode'] ?? 'open_new_conversation'
            ];
        } else {
            $settings = [
                'private_note_mode' => strip_tags($_POST['private_note_mode'])
            ];

            $this->saveSettings($settings);
        }

        return [
            [
                'id' => 'private_note_mode',
                'label' => $this->lang['settings']['private_note_mode']['label'],
                'descrip' => $this->lang['settings']['private_note_mode']['descrip'],
                'type' => 'select',
                'value' => $settings['private_note_mode'],
                'options' => [
                    [
                        'label' => $this->lang['settings']['private_note_mode']['options'][0],
                        'value' => 'open_new_conversation'
                    ],
                    [
                        'label' => $this->lang['settings']['private_note_mode']['options'][1],
                        'value' => 'send_to_latest_conversation'
                    ]
                ]
            ]
        ];
    }
}
