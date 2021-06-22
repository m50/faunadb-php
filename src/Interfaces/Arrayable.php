<?php

namespace FaunaDB\Interfaces;

/**
 * @template TKey is array-key
 * @template TValue
 */
interface Arrayable
{
    /**
     * @return array<TKey,TValue>
     */
    public function toArray(): array;
}
