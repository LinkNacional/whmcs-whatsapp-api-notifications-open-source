<?php

namespace Lkn\HookNotification\Core\BulkMessaging\Http;

use DateTime;
use Lkn\HookNotification\Core\BulkMessaging\Domain\BulkStatus;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;

final class NewBulkRequest
{
    /**
     * @param  BulkStatus|null                   $status
     * @param  string|null                       $title
     * @param  string|null                       $descrip
     * @param  Platforms|null                    $platform
     * @param  DateTime|null                     $startAt
     * @param  integer|null                      $maxConcurrency
     * @param  array<string, array<string>>|null $filters
     * @param  string|null                       $template
     */
    public function __construct(
        public readonly ?BulkStatus $status,
        public readonly ?string $title,
        public readonly ?string $descrip,
        public readonly ?Platforms $platform,
        public readonly ?DateTime $startAt,
        public readonly ?int $maxConcurrency,
        public readonly ?array $filters,
        public readonly ?string $template,
    ) {
    }

    /**
     * @param  array<string, array<string>> $request
     *
     * @return array<string, array<string>>
     */
    public static function parseFiltersFromRequest(array $request): array
    {
        $filters = [
            'client_status' =>  $request['client-status'],
            'client_locale' => $request['client-locale'],
            'client_country' => $request['client-country'],
            'services' => $request['services'],
            'service_status' => $request['service-status'],
            'client_ids' => $request['client-ids'],
            'not_sending_clients' => $request['not-sending-clients'] ?? [],
        ];

        return $filters;
    }
}
