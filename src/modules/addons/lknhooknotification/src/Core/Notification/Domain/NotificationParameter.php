<?php

namespace Lkn\HookNotification\Core\Notification\Domain;

use Closure;

class NotificationParameter
{
    /**
     * @param string  $code
     * @param string  $label
     * @param Closure $valueGetter
     */
    public function __construct(
        public string $code,
        public string $label,
        public Closure $valueGetter
    ) {
        //
    }
}
