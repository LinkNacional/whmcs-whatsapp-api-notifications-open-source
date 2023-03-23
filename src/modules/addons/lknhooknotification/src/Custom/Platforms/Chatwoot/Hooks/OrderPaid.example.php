<?php

namespace Lkn\HookNotification\Custom\Platforms\Chatwoot\Hooks;

use Lkn\HookNotification\Config\Platforms;
use Lkn\HookNotification\Config\Settings;
use Lkn\HookNotification\Domains\Platform\Traits\HookDataGetter;
use Lkn\HookNotification\Domains\Platforms\Chatwoot\Abstracts\ChatwootHookFile;
use Lkn\HookNotification\Helpers\Config;

final class OrderPaid extends ChatwootHookFile
{
    use HookDataGetter;

    /**
     * @since 1.0.0
     *
     * @param \Lkn\HookNotification\Domains\Platform\Abstracts\HookDataParser $hookData
     *
     * @return bool
     */
    public function run(object $hookData): bool
    {
        // Note: on free plan you can have only 3 total hook files under /Custom/Platforms/*/Hooks.
        /**
         * In short, you can call this method for sending a message template.
         */
        // $response = $this->sendMessageToClient($hookData->clientId, 'Message');

        /**
         * For more custom things, you should read and use this.
         *
         * For sending a message to Chatwoot API, it requires a contact and a conversation
         * So the code below searchs a contact, creates a conversation it there is not one
         * and send a message to the conversation.
         */

        $whatsappPhoneNumber = $this->getWhatsAppNumberForClient($hookData->clientId);

        $contact = $this->searchContact($whatsappPhoneNumber);

        $contactWhatsAppSourceId = $contact['sourceIds']['whatsapp'] ?? '';

        if ($contact['success'] === false) {
            return $contact;
        }

        /**
         * Tries to find a open conversation, avoids to create one new conversation
         * for each message sent.
         */
        $conversation = $this->searchForOpenWhatsAppConversation($contact['id']);

        /**
         * Tries to create a conversation for the contact if there is none.
         */
        if ($conversation['success'] === false) {
            $whatsappInboxId = Config::get(Platforms::CHATWOOT, Settings::CW_WHATSAPP_INBOX_ID);

            $conversation = $this->createConversation($contact['id'], $contactWhatsAppSourceId, $whatsappInboxId);

            if ($conversation['success'] === false) {
                return $conversation;
            }
        }

        $message = 'Hello, World!';

        /**
         * The last parameter tells if the message must be sent as private or not.
         * Default is false.
         */
        $message = $this->sendMessage($conversation['id'], $message, true);

        /**
        * When you finish your implementation, you should see /Custom/hooks.php
        * and call this hook file there, using the Dispatcher.
        */

        return true;
    }
}
