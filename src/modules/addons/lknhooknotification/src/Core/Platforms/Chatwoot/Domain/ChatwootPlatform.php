<?php

namespace Lkn\HookNotification\Core\Platforms\Chatwoot\Domain;

use Lkn\HookNotification\Core\NotificationReport\Domain\NotificationReportStatus;
use Lkn\HookNotification\Core\Notification\Domain\AbstractNotification;
use Lkn\HookNotification\Core\Notification\Domain\AbstractNotificationParser;
use Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate;
use Lkn\HookNotification\Core\Platforms\Common\AbstractPlatform;
use Lkn\HookNotification\Core\Platforms\Common\AbstractPlatformSettings;
use Lkn\HookNotification\Core\Platforms\Common\PlatformNotificationSendResult;
use Lkn\HookNotification\Core\Shared\Infrastructure\BaseApiClient;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;
use Lkn\HookNotification\Core\Shared\Infrastructure\Result;

final class ChatwootPlatform extends AbstractPlatform
{
    /**
     * @var ChatwootSettings
     */
    public readonly AbstractPlatformSettings $platformSettings;
    public readonly AbstractNotificationParser $notificationParser;

    /**
     * @var \Lkn\HookNotification\Core\Platforms\Chatwoot\Infrastructure\ChatwootApiClient
     */
    protected readonly BaseApiClient $apiClient;

    public function sendNotification(
        AbstractNotification $notification,
        NotificationTemplate $template,
    ): PlatformNotificationSendResult {
        if (!$this->platformSettings->enabled) {
            return new PlatformNotificationSendResult(
                NotificationReportStatus::NOT_SENT,
                'The platform is disabled.'
            );
        }

        $whatsappPhoneNumber = $this->getPhoneNumber($notification);

        if (!$whatsappPhoneNumber) {
            lkn_hn_log(
                Platforms::CHATWOOT->value . ': client has no valid phone number',
                [
                    'notification' => $notification,
                    'template' => $template,
                    'whatsappPhoneNumber' => $whatsappPhoneNumber,
                ]
            );

            return new PlatformNotificationSendResult(
                NotificationReportStatus::NOT_SENT,
                'Client has no valid phone number.'
            );
        }

        $contactIdsAndInboxesSourcesIds = $this->apiClient->searchContactAndGetItsIdAndItsInboxesSourceId(strval($whatsappPhoneNumber));

        $inboxId = $this->platformSettings->wpInboxId;

        if ($contactIdsAndInboxesSourcesIds->operationResult === false) {
            $clientName = getClientFullNameByClientId($notification->client->id);
            $clientName = $clientName !== '' ? $clientName : lkn_hn_lang($this->platformSettings->moduleSettings->defaultClientName ?: 'Unregistered Client');

            $clientEmail = getClientEmailByClientId($notification->client->id);

            $createdContact = $this->apiClient->createContact(
                $inboxId,
                $clientName,
                $clientEmail,
                '+' . strval($whatsappPhoneNumber)
            );

            if ($createdContact->operationResult === false) {
                lkn_hn_log(
                    Platforms::CHATWOOT->value . ': unable to create contact',
                    [
                        'notification' => $notification,
                        'template' => $template,
                    ],
                    [
                        'api_response' => $createdContact,
                    ]
                );

                return new PlatformNotificationSendResult(
                    NotificationReportStatus::ERROR,
                    'Unable to create contact in Chatwoot.'
                );
            }

            $contactInboxesSourcesIds = $this->apiClient->getSourceIdsByInboxIds(
                $createdContact->body['payload']['contact']['contact_inboxes']
            );

            $contactIdsAndInboxesSourcesIds = new Result(
                data: [
                    'contact' => [
                        'id' => $createdContact->body['payload']['contact']['id'],
                        'inboxesSourcesIds' => $contactInboxesSourcesIds,
                    ],
                ]
            );
        }

        $contactId               = $contactIdsAndInboxesSourcesIds->data['contact']['id'];
        $contactSourceIdForInbox = $contactIdsAndInboxesSourcesIds->data['contact']['inboxesSourcesIds'][$inboxId];

        $conversation = null;

        if ($this->platformSettings->listenToWhatsAppPlatformMode === 'open_new_conversation') {
            $conversation = $this->apiClient->createConversation(
                $contactId,
                $contactSourceIdForInbox,
                $inboxId
            );

            if ($conversation->operationResult === false) {
                lkn_hn_log(
                    Platforms::CHATWOOT->value . ': unable to create conversation',
                    [
                        'notification' => $notification,
                        'template' => $template,
                    ],
                    [
                        'api_response' => $conversation,
                    ]
                );

                return new PlatformNotificationSendResult(
                    NotificationReportStatus::ERROR,
                    'Unable to create conversation in Chatwoot.'
                );
            }

            $conversation = $conversation->body;
        } else {
            $conversation = $this->apiClient->getContactLastConversation(
                $contactId,
                $inboxId
            );

            if (
                $conversation instanceof Result &&
                $conversation->operationResult === false
            ) {
                $conversation = $this->apiClient->createConversation(
                    $contactId,
                    $contactSourceIdForInbox,
                    $inboxId,
                    'open'
                );

                if ($conversation->operationResult === false) {
                    lkn_hn_log(
                        Platforms::CHATWOOT->value . ': unable to create conversation',
                        [
                            'notification' => $notification,
                            'template' => $template,
                        ],
                        [
                            'api_response' => $conversation,
                        ]
                    );

                    return new PlatformNotificationSendResult(
                        NotificationReportStatus::ERROR,
                        'Unable to create conversation in Chatwoot.'
                    );
                }

                $conversation = $conversation->body;
            } else {
                $conversation = $conversation->data['lastConversation'];
            }
        }

        if (!$conversation) {
            lkn_hn_log(
                Platforms::CHATWOOT->value . ': unable to create conversation',
                [
                    'notification' => $notification,
                    'template' => $template,
                ],
                [
                    'api_response' => $conversation,
                ]
            );

            return new PlatformNotificationSendResult(
                NotificationReportStatus::ERROR,
                'Unable to create conversation in Chatwoot.'
            );
        }

        $message = $this->apiClient->sendMessageToConversation(
            $conversation['id'],
            $template->template,
            'text',
            'outgoing',
            true
        );

        return new PlatformNotificationSendResult(
            NotificationReportStatus::SENT,
            'The notification was sent.'
        );
    }
}
