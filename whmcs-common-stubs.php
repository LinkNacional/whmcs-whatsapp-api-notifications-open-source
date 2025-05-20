<?php

namespace {
    define('ROOTDIR', '');
    function pdfInvoice(int $invoiceid): string
    {

    }

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

        public function getTemplateVars(string $var): mixed {
            //
        }
    }
}

namespace WHMCS\Module {

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
}



namespace Illuminate\Database\Eloquent {

    use ArrayAccess;
    use JsonSerializable;
    use Illuminate\Contracts\Support\Jsonable;
    use Illuminate\Contracts\Support\Arrayable;
    use Illuminate\Contracts\Routing\UrlRoutable;
    use Illuminate\Contracts\Queue\QueueableEntity;

    abstract class Model implements ArrayAccess, Arrayable, Jsonable, JsonSerializable, QueueableEntity, UrlRoutable {}
}

namespace Illuminate\Database\Query {

    use Closure;

    final class Builder
    {
        public function where(
            string|array|Closure $column,
            int|string|null $operator = null,
            mixed $value = null,
            string $boolean = 'and'
        ): self {}

        public function whereIn(string $column, mixed $values, string $boolean = 'and', bool $not = false): self {}

        public function exists(): bool {}

        public function join(
            string $table,
            string $one,
            ?string $operator = null,
            ?string $two = null,
            string $type = 'inner',
            $where = false
        ): self {}

        public function orWhere(string $column, string $operator = null, mixed $value = null): self {}

        public function whereNotIn(string $column, mixed $values): static {}

        public function update(array $values): int {}

        public function limit($value): self {}

        public function insert(array $values): bool {}

        public function first($columns = ['*']): \Illuminate\Database\Eloquent\Model|static|null {}

        public function leftJoin(
            string $table,
            string $first,
            ?string $operator = null,
            ?string $second = null
        ): \Illuminate\Database\Query\Builder|static {}


        public function leftJoin(
            string $table,
            \Closure|\Illuminate\Database\Query\Expression|string $first,
            ?string $operator = null,
            \Illuminate\Database\Query\Expression|string|null $second = null
        ): self {}

        public function selectRaw(string $expression, array $bindings = []): \Illuminate\Database\Query\Builder|static {}

        public function leftJoinSub(
            $query,
            string $as,
            \Closure|\Illuminate\Database\Query\Expression|string $first,
            ?string $operator = null,
            \Illuminate\Database\Query\Expression|string|null $second = null
        ): static {}

        public function groupBy(array|string $column): self {}

        public function count(string $columns = '*'): int {}

        public function get(array $columns = ['*']): \Illuminate\Support\Collection {}

        public function select(array|string ...$columns): self {}

        public function raw(string $value): string {}

        public function orderBy(string $column, string $direction = 'asc'): self {}
    }
}

namespace WHMCS\Payment\PayMethod\Adapter {

    use WHMCS\Payment\PayMethod\Model;

    final class RemoteCreditCard
    {
        public static function factoryPayMethod(object $client, object $billingContact = null, $description = ''): object
        {
            return new Model();
        }
    }
}

namespace WHMCS\Payment\PayMethod {

    use WHMCS\Module\Gateway;

    final class Model
    {
        public $payment;

        public function setGateway(Gateway $gateway): void
        {
            //
        }

        public function save(): void
        {
            //
        }
    }
}

namespace WHMCS {

    use DateTimeZone;

    final class Carbon
    {
        public static function createFromCcInput(string|array $montYear): DateTimeZone|string|null
        {
            return '';
        }
    }
}

namespace WHMCS\Billing {

    final class Invoice
    {
        public static function find(int $id): void
        {
            //
        }
    }
}

namespace WHMCS\Authentication {

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
}

namespace WHMCS\Language {

    abstract class AbstractLanguage extends \Symfony\Component\Translation\Translator
    {
        public static function getLocales(): array
        {
            return [];
        }
    }
}

namespace WHMCS\User {

    final class Client
    {
        public readonly int $id;

        public function contacts(): array
        {
            return [];
        }

        public function invoices(): array
        {
            return [];
        }

        public function payMethods(): array
        {
            return [];
        }
    }
}

namespace Symfony\Component\Translation {

    abstract class Translator
    {
        protected string $locale;

        public function __construct(string $locale = 'en')
        {
            $this->locale = $locale;
        }

        public function getLocale(): string
        {
            return $this->locale;
        }

        public function setLocale(string $locale): void
        {
            $this->locale = $locale;
        }

        abstract public function trans(
            string $id,
            array $parameters = [],
            string $domain = null,
            string $locale = null
        ): string;
    }
}

namespace WHMCS\Database {

    use Illuminate\Database\Query\Builder;

    final class Capsule extends \Illuminate\Database\Capsule\Manager
    {
        public static function table($table, string|null $connection = null): Builder
        {
            return new Builder();
        }

        public static function connection($name = null): \Illuminate\Database\Connection {}
    }
}

namespace Illuminate\Database {
    class Connection implements ConnectionInterface
    {
        public function getPdo(): \PDO {}

        public function statement(string $query, array $bindings = []): bool {}
    }
}

namespace Illuminate\Database\Capsule {
    class Manager
    {
        public static function schema(?string $connection = null): \Illuminate\Database\Schema\Builder
        {
            return static::$instance->connection($connection)->getSchemaBuilder();
        }
    }
}

namespace Illuminate\Database\Schema {
    class Builder
    {
        public function rename(string $from, string $to): \Illuminate\Database\Schema\Blueprint {}
    }
}


namespace Illuminate\Database\Query {

    use Closure;
    use Illuminate\Database\Query\Expression;

    class JoinClause
    {
        public string $type;
        public string $table;
        public array $clauses = [];
        public array $bindings = [];

        public function __construct(string $type, string $table) {}

        public function on(Closure|string $first, ?string $operator = null, ?string $second = null, string $boolean = 'and', bool $where = false): static {}

        public function orOn(Closure|string $first, ?string $operator = null, ?string $second = null): static {}

        public function where(Closure|string $first, ?string $operator = null, ?string $second = null, string $boolean = 'and'): static {}

        public function orWhere(Closure|string $first, ?string $operator = null, ?string $second = null): static {}

        public function whereNull(string $column, string $boolean = 'and'): static {}

        public function orWhereNull(string $column): static {}

        public function whereNotNull(string $column, string $boolean = 'and'): static {}

        public function orWhereNotNull(string $column): static {}

        public function whereIn(string $column, array $values): static {}

        public function whereNotIn(string $column, array $values): static {}

        public function orWhereIn(string $column, array $values): static {}

        public function orWhereNotIn(string $column, array $values): static {}

        public function nest(Closure $callback, string $boolean = 'and'): static {}
    }
}

namespace Illuminate\Support {

    use Countable;
    use ArrayAccess;
    use ArrayIterator;
    use CachingIterator;
    use JsonSerializable;
    use IteratorAggregate;
    use InvalidArgumentException;
    use Illuminate\Support\Traits\Macroable;
    use Illuminate\Contracts\Support\Jsonable;
    use Illuminate\Contracts\Support\Arrayable;

    class Collection implements ArrayAccess, Arrayable, Countable, IteratorAggregate, Jsonable, JsonSerializable
    {
        use Macroable;

        /**
         * @var array
         */
        protected $items = [];

        /**
         * @param mixed $items
         * @return void
         */
        public function __construct($items = []) {}

        /**
         * @param mixed $items
         * @return static
         */
        public static function make($items = []) {}

        /**
         * @return array
         */
        public function all() {}

        /**
         * @param string|null $key
         * @return mixed
         */
        public function avg($key = null) {}

        /**
         * @param string|null $key
         * @return mixed
         */
        public function average($key = null) {}

        /**
         * @return static
         */
        public function collapse() {}

        /**
         * @param mixed $key
         * @param mixed $value
         * @return bool
         */
        public function contains($key, $value = null) {}

        /**
         * @param mixed $items
         * @return static
         */
        public function diff($items) {}

        /**
         * @param mixed $items
         * @return static
         */
        public function diffKeys($items) {}

        /**
         * @param callable $callback
         * @return $this
         */
        public function each(callable $callback) {}

        /**
         * @param int $step
         * @param int $offset
         * @return static
         */
        public function every($step, $offset = 0) {}

        /**
         * @param mixed $keys
         * @return static
         */
        public function except($keys) {}

        /**
         * @param callable|null $callback
         * @return static
         */
        public function filter(callable $callback = null) {}


        public function toArray(): array {}
    }
}
