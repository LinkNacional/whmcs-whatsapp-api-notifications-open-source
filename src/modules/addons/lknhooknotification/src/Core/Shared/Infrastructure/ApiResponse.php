<?php

namespace Lkn\HookNotification\Core\Shared\Infrastructure;

final class ApiResponse
{
    public readonly null|bool $operationResult;

    /**
     * @param  integer                                      $httpStatusCode
     * @param  array<int|string, mixed>|null|boolean|string $body
     */
    public function __construct(
        public readonly int $httpStatusCode,
        public readonly array|null|bool|string $body,
    ) {
    }

    public function setOperationResult(bool $result)
    {
        $this->operationResult = $result;
    }

    /**
     * @return array<int|string, array<string, array<mixed>|bool|int|string|null>|bool>
     */
    public function toArray(): array
    {
        $arr = [];

        if (isset($this->operationResult)) {
            $arr = ['operationResult' => $this->operationResult];
        }

        $arr = [
            ...$arr,
            [
                'httpStatusCode' => $this->httpStatusCode,
                'body' => $this->body,
            ],
        ];

        return $arr;
    }
}
