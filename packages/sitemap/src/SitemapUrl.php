<?php

declare(strict_types=1);

namespace Serixa\Sitemap;

use DateTimeImmutable;
use Serixa\Exception\InvalidArgumentException;

final class SitemapUrl
{
    public function __construct(
        private readonly string $loc,
        private readonly ?DateTimeImmutable $lastmod = null,
        private readonly ?string $changefreq = null,
        private readonly ?float $priority = null,
    ) {
        if ($loc === '') {
            throw new InvalidArgumentException('Sitemap URL loc is required.');
        }
        if ($priority !== null && ($priority < 0.0 || $priority > 1.0)) {
            throw new InvalidArgumentException('Sitemap priority must be between 0.0 and 1.0.');
        }
    }

    public static function make(
        string $loc,
        ?DateTimeImmutable $lastmod = null,
        ?string $changefreq = null,
        ?float $priority = null,
    ): self {
        return new self($loc, $lastmod, $changefreq, $priority);
    }

    public function loc(): string
    {
        return $this->loc;
    }

    public function lastmod(): ?DateTimeImmutable
    {
        return $this->lastmod;
    }

    public function changefreq(): ?string
    {
        return $this->changefreq;
    }

    public function priority(): ?float
    {
        return $this->priority;
    }
}
