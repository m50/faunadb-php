<?php

declare(strict_types=1);

namespace FaunaDB\Result;

use FaunaDB\Exceptions\ImmutableException;

trait ArrayMethodsTrait
{
    private int $idx = 0;
    /** @var TKey $currentKey */
    private null|int|string $currentKey;

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
}
