<?php

declare(strict_types=1);

namespace FaunaDB\Result;

use ArrayAccess;
use FaunaDB\Interfaces\Arrayable;
use Iterator;
use Webmozart\Assert\Assert;

/**
 * @template TKey
 * @template TValue
 */
class Collection implements ArrayAccess, Iterator, Arrayable
{
    use ArrayMethodsTrait;

    public static function fromArrayable(Arrayable $arr): static
    {
        return new static($arr->toArray());
    }

    /**
     * @param array<TKey,TValue> $objects
     */
    public static function from(array $objects)
    {
        return new static($objects);
    }

    /**
     * @param array<TKey,TValue> $objects
     */
    public function __construct(private array $objects)
    {
        $this->currentKey = array_keys($objects)[0];
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

    public function filter(callable $callable): static
    {
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

    public function count(): int
    {
        return \count($this->objects);
    }

    public function toArray(): array
    {
        return $this->objects;
    }
}
