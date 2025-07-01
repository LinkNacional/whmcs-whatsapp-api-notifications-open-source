<?php

use Lkn\HookNotification\Core\WHMCS\ApiHandler;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../../../../init.php';
require_once __DIR__ . '/../Core/Shared/param_funcs.php';

try {
    /** @var string $endpoint */
    $endpoint = $_GET['endpoint'] ?? '';

    if (empty($endpoint)) {
        echo 'empty endpoint';
    } else {
        ApiHandler::getInstance()->routeEndpoint($endpoint);
    }
} catch (Throwable $th) {
    lkn_hn_log('API error', ['exception' => $th->__toString()]);
}
