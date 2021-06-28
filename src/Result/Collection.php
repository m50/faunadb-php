<?php

declare(strict_types=1);

namespace FaunaDB\Result;

use Iterator;
use ArrayAccess;
use Webmozart\Assert\Assert;
use FaunaDB\Interfaces\Arrayable;
use FaunaDB\Exceptions\ImmutableException;

/**
 * @template TKey as array-key
 * @template TValue
 * @template-implements ArrayAccess<TKey,TValue>
 * @template-implements Iterator<TKey,TValue>
 * @template-implements Arrayable<TKey,TValue>
 * @psalm-immutable
 */
final class Collection implements ArrayAccess, Iterator, Arrayable
{
    /**
     * @psalm-readonly-allow-private-mutation
     */
    private int $idx = 0;
    /**
     * @psalm-readonly-allow-private-mutation
     * @psalm-var TKey|null $currentKey
     */
    private null|int|string $currentKey;

    /** @var array<TKey,TValue> $objects */
    private array $objects = [];

    /**
     * @template TPassedKey as array-key
     * @template TPassedValue
     * @param Arrayable|array $objects
     * @psalm-param Arrayable<TPassedKey,TPassedValue>|array<TPassedKey,TPassedValue> $objects
     * @psalm-return static<TPassedKey,TPassedValue>
     * @psalm-pure
     */
    public static function from(array|Arrayable $objects): static
    {
        /** @var static<TPassedKey,TPassedValue> $value */
        $value = new static($objects);

        return $value;
    }

    public static function empty(): static
    {
        return new static([]);
    }

    /**
     * @param array<TKey,TValue>|Arrayable<TKey,TValue> $objects
     */
    public function __construct(array|Arrayable $objects)
    {
        if ($objects instanceof Arrayable) {
            $objects = $objects->toArray();
        }
        $this->objects = $objects;
        $this->currentKey = array_keys($objects)[0] ?? null;
    }

    /**
     * @param callable $callable
     * @psalm-param callable(TValue,TKey=,int=) $callable
     * @return void
     */
    public function each(callable $callable): void
    {
        $idx = 0;
        foreach ($this->objects as $key => $obj) {
            $callable($obj, $key, $idx);
            $idx++;
        }
    }

    /**
     * @template TNewValue
     * @param callable $callable
     * @psalm-param callable(TValue,TKey=,int=):TNewValue $callable
     * @return static
     * @psalm-return static<TKey,TNewValue>
     */
    public function map(callable $callable): static
    {
        /** @var array<TKey,TValue> $result */
        $result = [];
        $idx = 0;
        foreach ($this->objects as $key => $obj) {
            $result[$key] = $callable($obj, $key, $idx);
            $idx++;
        }

        return new static($result);
    }

    /**
     * @psalm-return static<TKey,TValue>
     */
    public function filterNull(): static
    {
        return $this->filter(fn (mixed $v): bool => $v !== null);
    }

    /**
     * @param callable $callable
     * @psalm-param callable(TValue,TKey=,int=):bool $callable
     * @return static
     * @psalm-return static<TKey,TValue>
     */
    public function filter(?callable $callable = null): static
    {
        if ($callable === null) {
            $callable = fn (mixed $v, mixed ...$_): bool => (bool) $v;
        }

        $result = [];
        $idx = 0;

        foreach ($this->objects as $key => $obj) {
            if ($callable($obj, $key, $idx)) {
                $result[$key] = $obj;
            };
            $idx++;
        }

        return new static($result);
    }

    public function implode(string $separator = ''): string
    {
        Assert::allString($this->objects);

        return \implode($separator, $this->objects);
    }

    public function isObject(): bool
    {
        foreach (\array_keys($this->objects) as $key) {
            if (!\is_string($key)) {
                return false;
            }
        }

        return true;
    }

    public function hasMixedKeys(): bool
    {
        $hasStringKeys = false;
        $hasIntKeys = false;

        foreach (\array_keys($this->objects) as $key) {
            if (\is_integer($key)) {
                $hasIntKeys = true;
            } else {
                $hasStringKeys = true;
            }
        }

        return $hasStringKeys && $hasIntKeys;
    }

    public function hasOnlyNumericKeys(): bool
    {
        foreach (\array_keys($this->objects) as $key) {
            if (!\is_integer($key)) {
                return false;
            }
        }

        return true;
    }

    public function isList(): bool
    {
        $i = 0;
        foreach (\array_keys($this->objects) as $key) {
            if ($key !== $i) {
                return false;
            }
            $i++;
        }

        return true;
    }

    public function merge(Arrayable|array $toMerge): static
    {
        if ($toMerge instanceof Arrayable) {
            $toMerge = $toMerge->toArray();
        }

        return new static(array_merge($this->objects, $toMerge));
    }

    public function count(): int
    {
        return \count($this->objects);
    }

    public function values(): static
    {
        return new static(\array_values($this->objects));
    }

    public function keys(): static
    {
        return new static(\array_keys($this->objects));
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * @psalm-return null|TValue
     */
    public function first(): mixed
    {
        if ($this->count() === 0) {
            return null;
        }

        $firstKey = \array_keys($this->objects)[0] ?? 0;

        return $this->objects[$firstKey] ?? null;
    }

    /**
     * @psalm-return null|TValue
     */
    public function last(): mixed
    {
        if ($this->count() === 0) {
            return null;
        }

        return $this->objects[\array_keys($this->objects)[$this->count() - 1]];
    }

    /**
     * @psalm-param TValue $value
     */
    public function has(mixed $value): bool
    {
        foreach ($this->objects as $obj) {
            if ($obj === $value) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string|int $offset
     * @psalm-param TKey $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->objects[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->objects[$offset];
    }

    /**
     * @psalm-param TKey $offset
     * @psalm-param TValue $value
     * @psalm-return never
     * @throws \FaunaDB\Exceptions\ImmutableException
     */
    public function offsetSet($offset, $value)
    {
        throw ImmutableException::withKeyAndValue($offset, $value);
    }

    /**
     * @psalm-param TKey $offset
     * @psalm-return never
     * @throws \FaunaDB\Exceptions\ImmutableException
     */
    public function offsetUnset($offset)
    {
        throw ImmutableException::withKey($offset);
    }

    /**
     * @psalm-return TValue|null
     */
    public function current()
    {
        if ($this->currentKey === null) {
            return null;
        }

        return $this->objects[$this->currentKey];
    }

    /**
     * @psalm-external-mutation-free
     */
    public function next()
    {
        $this->idx += 1;
        $this->currentKey = array_keys($this->objects)[$this->idx] ?? null;
    }

    /**
     * @psalm-return TKey
     */
    public function key(): null|int|string
    {
        return $this->currentKey;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        if ($this->currentKey === null) {
            return false;
        }

        return $this->offsetExists($this->currentKey);
    }

    /**
     * @psalm-external-mutation-free
     */
    public function rewind()
    {
        $this->idx = 0;
        $this->currentKey = array_keys($this->objects)[0];
    }

    public function toArray(): array
    {
        return $this->objects;
    }
}
