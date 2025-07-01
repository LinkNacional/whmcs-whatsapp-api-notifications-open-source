<?php

namespace Lkn\HookNotification\Core\BulkMessaging\Domain;

enum BulkStatus: string
{
    case IN_PROGRESS = 'in_progress';
    case ABORTED     = 'aborted';
    case COMPLETED   = 'completed';

    public static function forView(): array
    {
        return array_map(
            function (BulkStatus $item) {
                return [
                    'value' => $item->value,
                    'label' => $item->label(),
                ];
            },
            self::cases()
        );
    }

    public function label(): string
    {
        return match ($this) {
            self::IN_PROGRESS => lkn_hn_lang('In progress'),
            self::ABORTED => lkn_hn_lang('Aborted'),
            self::COMPLETED => lkn_hn_lang('Completed'),
        };
    }

    public function labelClass(): string
    {
        return match ($this) {
            self::IN_PROGRESS => 'label-info',
            self::ABORTED => 'label-warning',
            self::COMPLETED => 'label-sucess',
        };
    }
}
