<?php

use Lkn\HookNotification\Core\WHMCS\SafePasswordReset\SafePasswordResetController;

add_hook(
    'ClientAreaPagePasswordReset',
    1,
    fn (array $whmcsHookParams) =>  (new SafePasswordResetController())->handleClientAreaPassowordReset($whmcsHookParams)
);
