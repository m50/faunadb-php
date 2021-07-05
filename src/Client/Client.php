<?php

declare(strict_types=1);

namespace FaunaDB\Client;

use DateTimeImmutable;
use FaunaDB\Expr\Expr;
use FaunaDB\Config\Config;
use FaunaDB\Result\Collection;
use FaunaDB\Result\PageHelper;
use FaunaDB\Result\RequestResult;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class Client
{
    private const GET = 'GET';
    private const POST = 'POST';

    private const API_VERSION = 4;

    private ?DateTimeImmutable $lastTxnTime = null;

    public function __construct(
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory,
        private Config $config,
    ) {
    }

    public function query(Expr $expr, ?Config $options = null): RequestResult
    {
        $options = $this->configToOptions($options);
        $options['body'] = $expr;

        return $this->request(self::POST, '', $options);
    }

    public function paginate(Expr $expr, array $params, ?Config $options = null): PageHelper
    {
        $options = $this->configToOptions($options);

        return new PageHelper($this, $expr, $params, $options);
    }

    public function ping(?string $scope = null, ?int $timeout = null): string
    {
        $response = $this->request(self::GET, 'ping', [
            'query' => [
                'scope' => $scope,
                'timeout' => $timeout,
            ],
        ]);

        return (string) $response;
    }

    public function close(): void
    {
    }

    private function request(string $method, string $path, array $options = []): RequestResult
    {
        $start = new DateTimeImmutable();

        $path = $this->config->getBaseUri() . $path;
        if (isset($options['query'])) {
            /** @var string|array<string,string> $query */
            $query = $options['query'];
            $query = \is_string($query) ? $query : $this->buildQueryString($query);
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

        /** @var string $secret */
        $secret = $options['secret'] ?? $this->config->getSecret();

        $request = $request->withAddedHeader('Authorization', "Bearer {$secret}")
            ->withAddedHeader('X-Query-Timeout', (string) ($options['timeout'] ?? $this->config->getTimeout()))
            ->withAddedHeader('X-FaunaDB-API-Version', (string) static::API_VERSION)
            ->withAddedHeader('X-Driver-Env', $this->buildDriverEnv())
            ->withAddedHeader('Accept', 'application/json');

        if ($this->lastTxnTime !== null) {
            $request = $request->withAddedHeader('X-Last-Seen-Txn', (string) $this->lastTxnTime->getTimestamp());
        }

        $response = $this->httpClient->sendRequest($request);

        $txnTime = (int) $response->getHeader('X-Txn-Time')[0];
        $this->lastTxnTime = (new DateTimeImmutable())->setTimestamp($txnTime) ?: null;

        return new RequestResult(
            $request,
            $response,
            $start,
            new DateTimeImmutable(),
        );
    }

    private function buildDriverEnv(): string
    {
        $env = 'Unknown';
        if (getenv('NETLIFY_IMAGES_CDN_DOMAIN')) {
            $env = 'Netlify';
        } elseif (getenv('VERCEL')) {
            $env = 'Vercel';
        } elseif (str_contains((string)getenv('PATH'), 'heroku')) {
            $env = 'Heroku';
        } elseif (getenv('AWS_LAMBDA_FUNCTION_VERSION')) {
            $env = 'AWS Lambda';
        } elseif (str_contains((string)getenv('_'), 'google')) {
            $env = 'GCP Cloud Functions';
        } elseif (getenv('GOOGLE_CLOUD_PROJECT')) {
            $env = 'GCP Compute Instances';
        } elseif (str_contains((string)getenv('ORYX_ENV_TYPE'), 'AppService')) {
            $env = 'Azure Compute';
        }

        return Collection::from([
            'driver' => \sprintf('php-%d', static::API_VERSION),
            'runtime' => PHP_VERSION,
            'env' => $env,
            'os' => PHP_OS_FAMILY,
        ])
            ->map(fn ($v, $k) => "{$k}={$v}")
            ->values()
            ->implode('; ');
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

    private function configToOptions(?Config $config): array
    {
        if ($config !== null) {
            return $config->toArray();
        }

        return [];
    }
}
