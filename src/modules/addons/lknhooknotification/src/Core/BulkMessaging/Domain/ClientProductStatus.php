<?php

namespace Lkn\HookNotification\Core\BulkMessaging\Domain;

enum ClientProductStatus: string
{
    case Pending    = 'Pending';
    case Active     = 'Active';
    case Completed  = 'Completed';
    case Suspended  = 'Suspended';
    case Terminated = 'Terminated';
    case Cancelled  = 'Cancelled';
    case Fraud      = 'Fraud';

    public static function forView(): array
    {
        return array_map(
            function (ClientProductStatus $item) {
                return [
                    'value' => $item->value,
                    'label' => lkn_hn_lang($item->value),
                ];
            },
            self::cases()
        );
    }
}
