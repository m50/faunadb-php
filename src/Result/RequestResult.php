<?php

declare(strict_types=1);

namespace FaunaDB\Result;

use DateTimeImmutable;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class RequestResult
{
    public function __construct(
        private RequestInterface $request,
        private ResponseInterface $response,
        private DateTimeImmutable $start,
        private DateTimeImmutable $end,
    ) {
    }

    /**
     * @template TDoc
     * @param string $documentClass
     * @psalm-param class-string<TDoc> $documentClass
     * @return mixed
     * @psalm-return TDoc
     */
    public function toDocument(string $documentClass)
    {
        $result = \json_decode($this->response->getBody()->getContents(), true, flags: JSON_THROW_ON_ERROR);

        return new $documentClass($result);
    }

    public function getTimeTaken(): int
    {
        return $this->end->getTimestamp() - $this->start->getTimestamp();
    }
}
