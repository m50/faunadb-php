<?php

declare(strict_types=1);

namespace FaunaDB\Result;

use ArrayAccess;
use FaunaDB\Interfaces\Arrayable;
use Iterator;
use Webmozart\Assert\Assert;

/**
 * @template TKey as array-key
 * @template TValue
 * @implements ArrayAccess<Tkey,TValue>
 * @implements Iterator<TKey,TValue>
 * @implements Arrayable<TKey,TValue>
 */
class Collection implements ArrayAccess, Iterator, Arrayable
{
    use ArrayMethodsTrait;

    public static function fromArrayable(Arrayable $arr): static
    {
        return new static($arr->toArray());
    }

    /**
     * @param array<TKey,TValue>|Arrayable<TKey,TValue> $objects
     */
    public static function from(array|Arrayable $objects)
    {
        if ($objects instanceof Arrayable) {
            return static::fromArrayable($objects);
        }

        return new static($objects);
    }

    public static function empty()
    {
        return new static([]);
    }

    /**
     * @param array<TKey,TValue> $objects
     */
    public function __construct(private array $objects)
    {
        $this->currentKey = array_keys($objects)[0] ?? 0;
    }

    public function each(callable $callable): void
    {
        $idx = 0;
        foreach ($this->objects as $key => $obj) {
            $callable($obj, $key, $idx);
            $idx++;
        }
    }

    public function map(callable $callable): static
    {
        $result = [];
        $idx = 0;
        foreach ($this->objects as $key => $obj) {
            $result[$key] = $callable($obj, $key, $idx);
            $idx++;
        }

        return new static($result);
    }

    public function filterNull(): static
    {
        return $this->filter(fn ($v) => $v !== null);
    }

    public function filter(?callable $callable = null): static
    {
        if ($callable === null) {
            $callable = fn ($v) => (bool) $v;
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

    public function implode(string $separator = '')
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
            if (\is_numeric($key)) {
                $hasIntKeys = true;
            } elseif (\is_string($key)) {
                $hasStringKeys = true;
            }
        }

        return $hasStringKeys && $hasIntKeys;
    }

    public function hasOnlyNumericKeys(): bool
    {
        foreach (\array_keys($this->objects) as $key) {
            if (!\is_numeric($key)) {
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

        return $this->objects[\array_keys($this->objects)[0]];
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

    public function toArray(): array
    {
        return $this->objects;
    }
}
