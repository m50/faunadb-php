<?php

declare(strict_types=1);

namespace FaunaDB\Result;

use Iterator;
use ArrayAccess;
use FaunaDB\Expr\Expr;
use FaunaDB\Client\Client;
use FaunaDB\Interfaces\Arrayable;

/**
 * @internal This should not be used manually, only through `FaunaDB\Client\Client::paginate()`.
 */
final class PageHelper
{
    private array $objects = [];
    private int $currentPage;

    public function __construct(
        private Client $client,
        private Expr $expr,
        private array $params,
        private array $options,
    ) {
        $this->currentPage = 0;
    }

    public function nextPage(): void
    {
        $this->currentPage++;
    }

    public function prevPage(): void
    {
        $this->currentPage--;
    }
}
