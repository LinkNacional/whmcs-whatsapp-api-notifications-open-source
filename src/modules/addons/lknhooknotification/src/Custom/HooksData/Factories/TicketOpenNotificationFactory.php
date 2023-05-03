<?php

namespace Lkn\HookNotification\Custom\HooksData\Factories;

use Lkn\HookNotification\Custom\HooksData\Ticket;

final class TicketOpenNotificationFactory
{
    /**
     * You should call this method in the add_hook callback.
     *
     * @since 1.0.0
     *
     * @param array $raw raw array coming from the callback provided to add_hook().
     *
     * @return \Lkn\HookNotification\Custom\HooksData\Ticket
     */
    public static function fromHook(array $vars): Ticket
    {
        $ticketId = (string) $vars['ticketid'];

        $ticketInfo = localAPI('GetTicket', ['ticketid' => $ticketId]);

        $tid = $ticketInfo['tid'];
        $clientAccess = $ticketInfo['c'];
        $clientId = $ticketInfo['userid'];

        return new Ticket($vars, $tid, $clientAccess, $clientId);
    }
}
