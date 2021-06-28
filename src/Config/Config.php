<?php

declare(strict_types=1);

namespace FaunaDB\Config;

use FaunaDB\Exceptions\InvalidConfigurationException;
use FaunaDB\Interfaces\Arrayable;
use FaunaDB\Result\Collection;
use Webmozart\Assert\Assert;

/**
 * @implements Arrayable<string,mixed>
 * @psalm-immutable
 */
final class Config implements Arrayable
{
    /**
     * @psalm-param 'http'|'https' $scheme
     * @psalm-param array<string,string> $headers
     * @throws \FaunaDB\Exceptions\InvalidConfigurationException
     * @throws \Webmozart\Assert\InvalidArgumentException
     */
    public function __construct(
        private string $domain = 'db.fauna.com',
        private string $scheme = 'https',
        private ?int $port = null,
        private ?string $secret = null,
        private array $headers = [],
        private int $timeout = 60,
    ) {
        if ($secret === null) {
            getenv('FAUNADB_SECRET') ?: throw InvalidConfigurationException::withInvalidSecret();
        }
        Assert::inArray($scheme, ['https', 'http']);
        $this->port = $port ?? ($scheme === 'https' ? 443 : 80);
    }

    /**
     * @return array<string,mixed>
     * @psalm-mutation-free
     */
    public function toArray(): array
    {
        return [
            'domain' => $this->domain,
            'scheme' => $this->scheme,
            'port' => $this->port,
            'secret' => $this->secret,
            'headers' => $this->headers,
            'timeout' => $this->timeout,
        ];
    }

    public function getBaseUri(): string
    {
        return "{$this->scheme}://{$this->domain}:{$this->port}/";
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @return array<string,string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
