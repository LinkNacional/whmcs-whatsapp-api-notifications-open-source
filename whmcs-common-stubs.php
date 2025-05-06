<?php

define('ROOTDIR', '');

function localAPI(string $cmd, array $apivalues1 = [], string $adminuser = ''): array
{
    return [];
}

function getGatewayVariables(string $gateway, string $invoiceId = ''): array
{
    return [];
}

function logTransaction(
    string $gateway,
    string|array $data,
    string $result,
    array $passedParams = [],
    \WHMCS\Module\Gateway $gatewayModule = null
): void {
    //
}

function add_hook(string $hook_name, int $priority, callable $hook_function, string|callable $rollback_function = ''): void
{
    //
}

function lknc_check_license(): string|bool
{
    return true;
}

final class Smarty
{
    public function setTemplateDir(string|array $template_dir, bool $isConfig = false): void
    {
        //
    }

    public function assign(string|array $tpl_var, $value = null, bool $nocache = false): self
    {
        return $this;
    }

    public function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null): string
    {
        return '';
    }
}

namespace WHMCS\Module;

final class Gateway
{
    public function __construct(string $gateway_name)
    {
        //
    }

    public function load(string $module, $globalVariable = null): bool
    {
        return true;
    }
}

namespace WHMCS\Database;

use Illuminate\Database\Query\Builder;

final class Capsule
{
    public function __construct()
    {
        //
    }

    public static function table($table, string|null $connection = null): Builder
    {
        return new Builder();
    }
}

namespace Illuminate\Database\Query;

use Closure;

final class Builder
{
    public function where(string|array|Closure $column, string|null $operator = null, mixed $value = null, string $boolean = 'and'): self
    {
        return $this;
    }

    public function whereIn(string $column, mixed $values, string $boolean = 'and', bool $not = false): self
    {
        return $this;
    }

    public function exists(): self
    {
        return $this;
    }

    public function join(
        string $table,
        string $one,
        ?string $operator = null,
        ?string $two = null,
        string $type = 'inner',
        $where = false
    ): self {
        return $this;
    }

    public function orWhere(string $column, string $operator = null, mixed $value = null): self
    {
        return $this;
    }

    public function update(array $values): int
    {
        return 0;
    }
}

namespace WHMCS\Payment\PayMethod\Adapter;

use WHMCS\Payment\PayMethod\Model;

final class RemoteCreditCard
{
    public static function factoryPayMethod(object $client, object $billingContact = null, $description = ''): object
    {
        return new Model();
    }
}

namespace WHMCS\Payment\PayMethod;

final class Model
{
    public $payment;

    public function setGateway(\WHMCS\Module\Gateway $gateway): void
    {
        //
    }

    public function save(): void
    {
    }
    //
}

namespace  WHMCS;

use DateTimeZone;

final class Carbon
{
    public static function createFromCcInput(string|array $montYear): DateTimeZone|string|null
    {
        return '';
    }
}

namespace WHMCS\Billing;

final class Invoice
{
    public static function find(int $id): void
    {
        //
    }
}

namespace WHMCS\Authentication;

final class CurrentUser
{
    public function client(): ?\WHMCS\User\Client
    {
        return null;
    }

    public function isAuthenticatedAdmin(): bool
    {
        return true;
    }
}
