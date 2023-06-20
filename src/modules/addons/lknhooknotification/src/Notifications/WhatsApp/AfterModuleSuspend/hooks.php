<?php

use Lkn\HookNotification\Domains\Notifications\Messenger;
use Lkn\HookNotification\Notifications\WhatsApp\AfterModuleSuspend\AfterModuleSuspendNotification;

Messenger::run(AfterModuleSuspendNotification::class);
