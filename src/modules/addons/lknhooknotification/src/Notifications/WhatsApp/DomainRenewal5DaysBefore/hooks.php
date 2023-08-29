<?php

use Lkn\HookNotification\Domains\Notifications\Messenger;
use Lkn\HookNotification\Notifications\WhatsApp\DomainRenewal3DaysBefore\DomainRenewal3DaysBeforeNotification;

Messenger::run(DomainRenewal3DaysBeforeNotification::class);
