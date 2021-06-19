<?php

declare(strict_types=1);

namespace FaunaDB\Result;

use ArrayAccess;
use FaunaDB\Exceptions\ImmutableException;
use FaunaDB\Interfaces\Arrayable;
use Iterator;

/**
 * @template TKey
 * @template TValue
 */
class Collection implements ArrayAccess, Iterator, Arrayable
{
    private int $idx = 0;
    /** @var TKey $currentKey */
    private null|int|string $currentKey;

    public function fromArrayable(Arrayable $arr): static
    {
        return new static ($arr->toArray());
    }

    /**
     * @param array<TKey,TValue> $objects
     */
    public function __construct(private array $objects)
    {
        $this->currentKey = array_keys($objects)[0];
    }

    public function offsetExists($offset)
    {
        return isset($this->objects[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->objects[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw ImmutableException::withKeyAndValue($offset, $value);
    }

    public function offsetUnset($offset)
    {
        throw ImmutableException::withKey($offset);
    }

    public function current()
    {
        return $this->objects[$this->currentKey];
    }

    public function next()
    {
        $this->idx++;
        $this->currentKey = array_keys($this->objects)[$this->idx] ?? null;
    }

    public function key()
    {
        return $this->currentKey;
    }

    public function valid()
    {
        return $this->offsetExists($this->currentKey);
    }

    public function rewind()
    {
        $this->idx = 0;
        $this->currentKey = array_keys($this->objects)[0];
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

    public function toArray(): array
    {
        return $this->objects;
    }
}
