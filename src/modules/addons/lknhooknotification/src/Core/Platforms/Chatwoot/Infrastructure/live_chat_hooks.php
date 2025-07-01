<?php

/**
 * This is the entrypoing for the Live Chat feature of Chatwoot platform.
 *
 * This is file should be require_once at entrypoint.php.
 */

use Lkn\HookNotification\Core\Platforms\Chatwoot\Application\LiveChatService;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings;

add_hook(
    'ClientAreaFooterOutput',
    999,
    function (array $whmcsHookParams) {
        try {
            if (empty($whmcsHookParams['client'])) {
                if (
                    isset($_SESSION['lknhooknotification']['cw_live_chat_logout']) &&
                    $_SESSION['lknhooknotification']['cw_live_chat_logout'] === true
                ) {
                    unset($_SESSION['lknhooknotification']['cw_live_chat_logout']);

                    return '<script>window.addEventListener("chatwoot:ready", function () { window.$chatwoot.reset(); });</script>';
                }

                return;
            }

            if (lkn_hn_config(Settings::CW_ENABLE_LIVE_CHAT)) {
                $output = (new LiveChatService($whmcsHookParams))->handle();

                return $output;
            }
        } catch (Throwable $th) {
            lkn_hn_log(
                'Live chat error',
                [
                    'whmcs_hook_params' => $whmcsHookParams,
                ],
                [
                    'error' => $th->__toString(),
                ]
            );
        }
    }
);

add_hook(
    'UserLogout',
    999,
    function ($vars) {
        try {
            if (lkn_hn_config(Settings::CW_ENABLE_LIVE_CHAT)) {
                $_SESSION['lknhooknotification']['cw_live_chat_logout'] = true;
            }
        } catch (Throwable $th) {
            lkn_hn_log(
                'Live chat error - unable to set logout variable',
                [
                    'vars' => $vars,
                ],
                [
                    'error' => $th->__toString(),
                ]
            );
        }
    }
);
