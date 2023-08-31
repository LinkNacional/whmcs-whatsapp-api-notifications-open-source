<?php

use Lkn\HookNotification\Domains\Notifications\Messenger;
use Lkn\HookNotification\Notifications\WhatsApp\DomainRenewal5DaysBefore\DomainRenewal5DaysBeforeNotification;

Messenger::run(DomainRenewal5DaysBeforeNotification::class);
