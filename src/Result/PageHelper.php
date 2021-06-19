<?php

declare(strict_types=1);

namespace FaunaDB\Result;

use FaunaDB\Client\Client;
use FaunaDB\Expr\Expr;

class PageHelper extends Collection
{
    private int $currentPage = 0;

    public function __construct(
        private Client $client,
        private Expr $expr,
        private array $params,
        private array $headers,
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
