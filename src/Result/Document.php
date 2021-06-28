<?php

namespace FaunaDB\Result;

abstract class Document
{
    /** @param array<string,mixed> $values */
    final public function __construct(private array $values)
    {
    }
}
