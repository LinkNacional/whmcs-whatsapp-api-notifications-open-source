<?php

namespace Lkn\HookNotification\Core\Shared\Infrastructure;

/**
 * @template T
 */
class Result
{
    /**
     * @var T|null
     */
    public ?array $data;

    /**
     * @param string|null  $code
     * @param T|null       $data
     * @param string|null  $msg
     * @param array|null   $errors
     * @param boolean|null $operationResult
     */
    public function __construct(
        public ?string $code = null,
        ?array $data = null,
        public ?string $msg = null,
        public ?array $errors = [],
        public readonly null|bool $operationResult = null
    ) {
        $this->data = $data;
    }

    public function toArray(): array
    {
        return [
            'code'            => $this->code,
            'data'            => $this->data,
            'msg'             => $this->msg,
            'errors'          => $this->errors,
            'operationResult' => $this->operationResult,
        ];
    }
}
