<?php

declare(strict_types=1);

namespace FaunaDB\Expr;

use FaunaDB\Interfaces\Arrayable;
use FaunaDB\Result\Collection;
use FaunaDB\FQL;
use function sprintf;
use function array_keys;
use function in_array;
use function is_array;
use function is_callable;
use function is_string;
use function json_encode;

final class Expr
{
    private const VAR_ARGS_FUNCTIONS = [
        'Do',
        'Call',
        'Union',
        'Intersection',
        'Difference',
        'Equals',
        'Add',
        'BitAnd',
        'BitOr',
        'BitXor',
        'Divide',
        'Max',
        'Min',
        'Modulo',
        'Multiply',
        'Subtract',
        'LT',
        'LTE',
        'GT',
        'GTE',
        'And',
        'Or',
    ];
    private const SPECIAL_CASES = [
        'containsstrregex' => 'ContainsStrRegex',
        'endswith' => 'EndsWith',
        'findstr' => 'FindStr',
        'findstrregex' => 'FindStrRegex',
        'gt' => 'GT',
        'gte' => 'GTE',
        'is_nonempty' => 'is_non_empty',
        'lowercase' => 'LowerCase',
        'lt' => 'LT',
        'lte' => 'LTE',
        'ltrim' => 'LTrim',
        'ngram' => 'NGram',
        'rtrim' => 'RTrim',
        'regexescape' => 'RegexEscape',
        'replacestr' => 'ReplaceStr',
        'replacestrregex' => 'ReplaceStrRegex',
        'startswith' => 'StartsWith',
        'substring' => 'SubString',
        'titlecase' => 'TitleCase',
        'uppercase' => 'UpperCase',
    ];

    /**
     * @param array<string,mixed> $raw
     */
    public function __construct(private array $raw)
    {
    }

    public function toJson(): string
    {
        return json_encode($this->raw);
    }

    public function toFQL(): string
    {
        return (string) $this;
    }

    public function __toString(): string
    {
        if (Collection::from($this->raw)->hasOnlyNumericKeys()) {
            return static::toString($this->raw);
        }
        $keys = array_keys($this->raw);
        if (in_array('match', $keys, true)) {
            $matchStr = static::toString($this->raw['match']);
            /** @var array<string,mixed>|Expr $terms */
            $terms = $this->raw['terms'] ?? [];
            if ($terms instanceof Expr) {
                $terms = $terms->raw;
            }
            $terms = Collection::from($terms);
            if ($terms->count() === 0) {
                return sprintf('Match(%s)', $matchStr);
            }
            if ($terms->hasOnlyNumericKeys()) {
                return sprintf(
                    'Match(%s, [%s])',
                    $matchStr,
                    static::printArray($terms, fn (mixed $v) => static::toString($v))
                );
            }

            return sprintf('Match(%s, %s)', $matchStr, static::toString($terms));
        } elseif (in_array('paginate', $keys, true)) {
            if (\count($keys) === 1) {
                return sprintf('Paginate(%s)', static::toString($this->raw['paginate']));
            }
            $params = Collection::from($this->raw)
                ->filter(fn (mixed $_, string $k) => $k !== 'paginate')
                ->toArray();

            return sprintf('Paginate(%s, %s)', static::toString($this->raw['paginate']), static::printObject($params));
        } elseif (in_array('let', $keys, true) && in_array('in', $keys, true)) {
            /** @var Arrayable<string,mixed>|array<string,mixed> $let */
            $let = $this->raw['let'];
            $letExpr = static::printObject($let);

            return sprintf('Let(%s, %s)', $letExpr, static::toString($this->raw['in']));
        } elseif (in_array('object', $keys, true)) {
            /** @var Arrayable<string,mixed>|array<string,mixed> $obj */
            $obj = $this->raw['object'];

            return static::printObject($obj);
        } elseif (in_array('merge', $keys, true)) {
            $values = [
                static::toString($this->raw['merge']),
                static::toString($this->raw['with']),
            ];
            if (in_array('lambda', $keys, true)) {
                $values[] = static::toString($this->raw['lambda']);
            }

            return sprintf('Lambda(%s)', \implode(', ', $values));
        } elseif (in_array('lambda', $keys, true)) {
            /** @var string $expr */
            $expr = $this->raw['expr'];

            return sprintf(
                'Lambda(%s, %s)',
                static::toString($this->raw['lambda']),
                $expr,
            );
        } elseif (in_array('filter', $keys, true)) {
            return sprintf(
                'Filter(%s, %s)',
                static::toString($this->raw['collection']),
                static::toString($this->raw['filter']),
            );
        } elseif (in_array('call', $keys, true)) {
            return sprintf(
                'Call(%s, %s)',
                static::toString($this->raw['call']),
                static::toString($this->raw['arguments']),
            );
        } elseif (in_array('map', $keys, true)) {
            return sprintf(
                'Map(%s, %s)',
                static::toString($this->raw['collection']),
                static::toString($this->raw['map']),
            );
        } elseif (in_array('foreach', $keys, true)) {
            return sprintf(
                'Foreach(%s, %s)',
                static::toString($this->raw['collection']),
                static::toString($this->raw['foreach']),
            );
        }

        $fn = static::convertToCamelCase($keys[0]);
        $args = Collection::from($this->raw)
            ->filter(fn (mixed $v) => $v !== null || count($keys) > 1)
            ->map(fn (mixed $v) => static::toString($v, $fn))
            ->implode(', ');

        return "{$fn}({$args})";
    }

    public static function toString(mixed $expr, ?string $caller = null): string
    {
        if ($expr instanceof Expr) {
            return $expr->toFQL();
        }

        if ($expr === null) {
            return 'null';
        } elseif ($expr instanceof Collection) {
            return static::toString($expr->toArray());
        } elseif (is_array($expr) && Collection::from($expr)->hasOnlyNumericKeys()) {
            $arrAsString = static::printArray($expr, fn (mixed $v) => static::toString($v));

            return in_array($caller, static::VAR_ARGS_FUNCTIONS, true)
                ? $arrAsString
                : "[{$arrAsString}]";
        } elseif (is_callable($expr) && ! is_string($expr) && ! is_array($expr)) {
            return FQL\Lambda($expr)->toFQL();
        } elseif ($expr instanceof Arrayable || \is_array($expr)) {
            /** @var array<string,mixed> $expr */
            return static::printObject($expr);
        }

        return json_encode($expr);
    }

    /**
     * @template TValue
     * @param array|Arrayable $obj
     * @psalm-param array<string,TValue>|Arrayable<string,TValue> $obj
     * @return string
     */
    private static function printObject(array|Arrayable $obj): string
    {
        $obj = Collection::from($obj);

        return sprintf('{%s}', $obj
            ->map(fn (mixed $v, string $k): string => sprintf('"%s": %s', $k, static::toString($v)))
            ->implode(', '));
    }

    /**
     * @template TKey as array-key
     * @template TValue
     * @param array|Arrayable $obj
     * @psalm-param array<TKey,TValue>|Arrayable<TKey,TValue> $obj
     * @param callable $toStr
     * @psalm-param callable(TValue,TKey=,int=):string $toStr
     * @return string
     */
    private static function printArray(array|Arrayable $obj, callable $toStr): string
    {
        return Collection::from($obj)->map($toStr)->implode(', ');
    }

    /** @psalm-pure */
    private static function convertToCamelCase(string $value): string
    {
        if (isset(static::SPECIAL_CASES[$value])) {
            return static::SPECIAL_CASES[$value];
        }

        return Collection::from(\explode('_', $value))
            ->map(fn (string $v) => \ucfirst($v))
            ->implode();
    }
}
