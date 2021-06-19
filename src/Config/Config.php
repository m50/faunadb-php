<?php

declare(strict_types=1);

namespace FaunaDB\Config;

use FaunaDB\Exceptions\InvalidConfigurationException;
use FaunaDB\Result\Collection;
use Webmozart\Assert\Assert;

final class Config
{
    /**
     * @psalm-param 'http'|'https' $scheme
     * @throws \FaunaDB\Exceptions\InvalidConfigurationException
     * @throws \Webmozart\Assert\InvalidArgumentException
     */
    public function __construct(
        private string $domain = 'db.fauna.com',
        private string $scheme = 'https',
        private ?int $port = null,
        private ?string $secret = null,
        private array $headers = [],
    ) {
        if ($secret === null) {
            $secret = getenv('FAUNADB_SECRET') ?: throw InvalidConfigurationException::withInvalidSecret();
        }
        Assert::inArray($scheme, ['https', 'http']);
    }

    public function getBaseUri(): string
    {
        $port = $this->port ?? ($this->scheme === 'https' ? 443 : 80);

        return "{$this->scheme}://{$this->domain}:{$port}/";
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

    public function getKeepAlive(): bool
    {
        return $this->keepAlive;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getQueryTimeout(): ?int
    {
        return $this->queryTimeout;
    }
}
