<?php

declare(strict_types=1);

namespace FaunaDB\Result;

use FaunaDB\Client\Client;
use FaunaDB\Expr\Expr;

/**
 * @internal This should not be used manually, only through `FaunaDB\Client\Client::paginate()`.
 */
final class PageHelper extends Collection
{
    private int $currentPage = 0;

    public function __construct(
        private Client $client,
        private Expr $expr,
        private array $params,
        private array $options,
    ) {
        parent::__construct([]);
        $this->nextPage();
    }

    public function nextPage()
    {
        $this->currentPage++;
    }

    public function prevPage()
    {
        $this->currentPage--;
    }
}
