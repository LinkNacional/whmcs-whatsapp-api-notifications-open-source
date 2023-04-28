<?php

namespace Lkn\HookNotification\Custom\Platforms\WhatsApp\Hooks;

use Lkn\HookNotification\Domains\Platforms\WhatsApp\Abstracts\WhatsappHookFile;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\Events\ChatwootSendMessageAsPrivate;

/**
 * In order to have access to the message template parser, you must inherit the class
 * Lkn\HookNotification\Domains\Platforms\WhatsApp\Abstracts\WhatsappHookFile
 */
final class TicketAnsweredNotification extends WhatsappHookFile
{
    /**
     * @since 2.0.0
     *
     * @param \Lkn\HookNotification\Domains\Platform\Abstracts\HookDataParser $hookData
     *
     * @return bool
     */
    public function run($hookData): bool
    {
        $response = $this->sendMessageTemplate('TicketAnsweredNotification', $hookData);

        $this->setCustomParser(function ($paramLabel) use ($hookData): mixed {
            return match ($paramLabel) {
                'client_first_name' => $this->getClientFirstName($hookData->clientId ?? $hookData->id),
                'ticket_id' => $hookData->ticketId,
            };
        });

        if ($response['success']) {
            (new ChatwootSendMessageAsPrivate())->run(
                $hookData->clientId,
                'Mensagem sobre ticket #' . $hookData->ticketId . ' respondido, foi enviada para este cliente.'
            );
        }

        return $response['success'];
    }
}
