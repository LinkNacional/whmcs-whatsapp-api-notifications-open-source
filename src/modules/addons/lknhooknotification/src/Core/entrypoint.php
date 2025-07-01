<?php

/**
 * This is the entrypoing for automatic notification triggering based on WHMCS
 * add_hook.
 *
 * This file has the same function as hooks.php. But:
 *
 * since hooks.php cannot be encoded, the module uses this file to put its
 * code because this one can be encoded.
 */

use Lkn\HookNotification\Core\AdminUI\Http\Controllers\HomepageController;
use Lkn\HookNotification\Core\BulkMessaging\Infrastructure\BulkDispatcher;
use Lkn\HookNotification\Core\Notification\Infrastructure\ManualNotificationHookListener;
use Lkn\HookNotification\Core\Notification\Infrastructure\NotificationHookListener;
use Lkn\HookNotification\Core\Shared\Infrastructure\Hooks;
use Lkn\HookNotification\Core\Shared\Infrastructure\View\View;

require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Wrap inside a function to avoid naming conflicts.
 *
 * @return void
 */
function lkn_hn_entrypoint()
{
    try {
        require_once __DIR__ . '/Shared/Infrastructure/helpers.php';
        require_once __DIR__ . '/Platforms/Chatwoot/Infrastructure/live_chat_hooks.php';

        BulkDispatcher::getInstance()->run();

        (new NotificationHookListener())->listen();

        /**
         * Currently, only the hook AdminInvoicesControlsOutput has manual notification.
         */
        (new ManualNotificationHookListener())->listenFor(
            Hooks::ADMIN_INVOICES_CONTROLS_OUTPUT
        );

        add_hook(
            'AdminAreaHeadOutput',
            999,
            function (): ?string {
                if ($_GET['module'] !== 'lknhooknotification' || $_GET['page'] !== 'bulk/new') {
                    return null;
                }

                return '<link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.css"/>';
            }
        );

        add_hook('AdminHomepage', 999, function (): ?string {
            try {
                return (new HomepageController(new View()))->newVersion();
            } catch (Throwable $th) {
                lkn_hn_log(
                    'Version check error',
                    [],
                    [
                        'error' => $th->__toString(),
                    ]
                );

                return null;
            }
        });

        add_hook(
            'DailyCronJob',
            999,
            function (): void {
                try {
                    (new HomepageController(new View()))->handleNewVersionCheck();
                } catch (Throwable $th) {
                    lkn_hn_log(
                        'Version check error',
                        [],
                        [
                            'error' => $th->__toString(),
                        ]
                    );
                }
            }
        );
    } catch (Throwable $th) {
        lkn_hn_log(
            'general error',
            [],
            [
                'msg' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString(),
                'to_string' => $th->__toString(),
            ]
        );
    }
}

lkn_hn_entrypoint();

add_hook('AdminAreaHeadOutput', 1, function ($vars) {
    return <<<HTML
<link
    rel="stylesheet"
    href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.css"
/>
HTML;
});
