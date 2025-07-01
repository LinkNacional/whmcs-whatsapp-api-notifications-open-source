<?php

namespace Lkn\HookNotification\Core\Notification\Infrastructure;

use Lkn\HookNotification\Core\Notification\Application\NotificationFactory;
use Lkn\HookNotification\Core\Notification\Application\Services\NotificationSender;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;
use Throwable;

/**
 * This class is responsible for receiving an array of AbstractNotifications and
 * listen to their $hook with add_hook.
 *
 * It should pass a closure for add_hook that should be responsible for:
 * 1. Using $notificationPlatformResolver for:
 *  - Identify the correct template for the client.
 *  - Identify the correct platform the the template.
 */
final class NotificationHookListener
{
    private readonly NotificationSender $notificationSender;

    public function __construct()
    {
        $this->notificationSender = NotificationSender::getInstance();
    }

    /**
     * @return void
     */
    public function listen(): void
    {
        $notifications = NotificationFactory::getInstance()->makeEnabledNotifs();

        foreach ($notifications as $notification) {
            /** @var \Lkn\HookNotification\Core\Notification\Domain\AbstractNotification $notification */

            $isManualNotification = in_array(
                $notification->hook,
                [
                    Hooks::ADMIN_INVOICES_CONTROLS_OUTPUT,
                ]
            );

            if ($isManualNotification) {
                continue;
            }

            add_hook(
                $notification->hook->value,
                $notification->priority,
                function (?array $whmcsHookParams = []) use ($notification) {
                    try {
                        $this->notificationSender->dispatchNotification($notification, $whmcsHookParams);
                    } catch (Throwable $th) {
                        lkn_hn_log(
                            'listener error',
                            [
                                'notification' => $notification,
                            ],
                            [
                                'exception' => $th->__toString(),
                            ]
                        );
                    }
                }
            );
        }
    }
}
