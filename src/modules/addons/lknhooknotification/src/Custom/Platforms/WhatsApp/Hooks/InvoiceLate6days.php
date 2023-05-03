<?php

namespace Lkn\HookNotification\Custom\Platforms\WhatsApp\Hooks;

use Lkn\HookNotification\Domains\Platforms\WhatsApp\Abstracts\WhatsappHookFile;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\Events\ChatwootSendMessageAsPrivate;

final class InvoiceLate6days extends WhatsappHookFile
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
        $response = $this->sendMessageTemplate('InvoiceLate6days', $hookData);

        if ($response['success']) {
            (new ChatwootSendMessageAsPrivate())->run(
                $hookData->clientId,
                'Mensagem sobre fatura #' . $hookData->invoiceId . ' atrasada há 6 dias foi enviada para este cliente.'
            );
        }

        return $response['success'];
    }
}
