<?php

namespace FaunaDB\Interfaces;

/**
 * @template TKey as array-key
 * @template TValue
 */
interface Arrayable
{
    /**
     * @return array<TKey,TValue>
     * @psalm-mutation-free
     * @psalm-pure
     */
    public function toArray(): array;
}
