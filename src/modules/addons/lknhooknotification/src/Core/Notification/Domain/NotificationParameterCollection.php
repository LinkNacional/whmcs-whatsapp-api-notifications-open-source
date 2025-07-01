<?php

namespace Lkn\HookNotification\Core\Notification\Domain;

use Closure;

class NotificationParameterCollection
{
    /**
     * @param NotificationParameter[] $params
     */
    public function __construct(public array $params)
    {
    }

    /**
     * @param  array $paramCodes
     *
     * @return NotificationParameter[]
     */
    public function getParametersByCode(array $paramCodes): array
    {
        $found = array_filter(
            $this->params,
            fn(NotificationParameter $param): bool =>
            in_array($param->code, $paramCodes)
        );

        return $found;
    }

    public function getValueGetterForParameter(string $paramCode): false|Closure
    {
        $found = current(
            array_filter(
                $this->params,
                fn(NotificationParameter $param): bool =>
                $param->code === $paramCode
            )
        );

        return $found ? $found->valueGetter : false;
    }

    public function fixThisBindOnValueGetters(AbstractNotification $object): void
    {
        foreach ($this->params as $key => $param) {
            $this->params[$key]->valueGetter = $param->valueGetter->bindTo($object, $object::class);
        }
    }
}
