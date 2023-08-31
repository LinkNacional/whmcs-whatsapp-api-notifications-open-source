<?php

/**
 * Access this file in your browser to test the notification you are working on.
 *
 * Use Messenger::runNow to simulate the hook params passed to an notification when WHMCS call it.
 *
 * You can access this file by adding /modules/addons/lknhooknotification/tests.php to your URL of your WHMCS
 *
 * @since 3.2.0.
 */

require_once __DIR__ . '/../../../init.php';

$currentUser = new \WHMCS\Authentication\CurrentUser();

if (!$currentUser->admin()) {
    exit;
}
