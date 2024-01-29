<?php

use Lkn\HookNotification\Domains\Notifications\Messenger;
use Lkn\HookNotification\Notifications\WhatsApp\AfterModuleUnsuspend\AfterModuleUnsuspendNotification;

Messenger::run(AfterModuleUnsuspendNotification::class);
