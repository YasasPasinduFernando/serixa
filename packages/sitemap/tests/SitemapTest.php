<?php

declare(strict_types=1);

namespace Serixa\Sitemap\Tests;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Serixa\Sitemap\Sitemap;
use Serixa\Sitemap\SitemapUrl;

final class SitemapTest extends TestCase
{
    public function testXmlOutput(): void
    {
        $xml = Sitemap::make()
            ->add(SitemapUrl::make('https://example.com/', new DateTimeImmutable('2026-01-01'), 'weekly', 1.0))
            ->toXml();

        self::assertStringContainsString('<loc>https://example.com/</loc>', $xml);
        self::assertStringContainsString('<priority>1.0</priority>', $xml);
        self::assertStringContainsString('urlset', $xml);
    }
}
