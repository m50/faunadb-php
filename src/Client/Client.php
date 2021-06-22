<?php

declare(strict_types=1);

namespace FaunaDB\Client;

use FaunaDB\Expr\Expr;
use FaunaDB\Config\Config;
use FaunaDB\Result\PageHelper;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class Client
{
    private const GET = 'GET';
    private const POST = 'POST';
    private const PUT = 'PUT';
    private const PATCH = 'PATCH';
    private const DELETE = 'DELETE';

    public function __construct(
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory,
        private Config $config,
    ) {
    }

    public function query(Expr $expr, array $headers = [])
    {
        $response = $this->request(self::POST, '', [
            'body' => \FaunaDB\FQL\wrap($expr),
            'headers' => $headers,
        ]);

        return \json_decode($response->getBody()->getContents(), flags: JSON_THROW_ON_ERROR);
    }

    public function paginate(Expr $expr, array $params, array $headers = []): PageHelper
    {
        return new PageHelper($this, $expr, $params, $headers);
        ;
    }

    public function ping(?string $scope = null, ?int $timeout = null): string
    {
        $response = $this->request(self::GET, 'ping', [
            'query' => [
                'scope' => $scope,
                'timeout' => $timeout,
            ],
        ]);

        return $response->getBody()->getContents();
    }

    public function close()
    {
    }

    private function request(string $method, string $path, array $options = []): ResponseInterface
    {
        $path = $this->config->getBaseUri() . $path;
        if (isset($options['query'])) {
            $query = \is_string($options['query']) ? $options['query'] : $this->buildQueryString($options['query']);
            $path .= "?{$query}";
        }
        $request = $this->requestFactory->createRequest($method, $path);
        foreach ($this->config->getHeaders() as $header => $value) {
            $request = $request->withAddedHeader($header, $value);
        }
        if (isset($options['body'])) {
            $body = Expr::toString($options['body']);
            $request = $request->withBody(
                $this->streamFactory->createStream($body),
            );
        }

        return $this->httpClient->sendRequest($request);
    }

    private function buildQueryString(array $query): string
    {
        $query = array_filter($query, fn ($v) => $v !== null);
        return \implode(
            '&',
            \array_map(
                fn ($k, $v) => "{$k}={$v}",
                \array_keys($query),
                \array_values($query),
            ),
        );
    }
}
