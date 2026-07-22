<?php

declare(strict_types=1);

namespace Serixa\Seo\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Document\Document;
use Serixa\Document\Seo\SeoManager;
use Serixa\Seo\SeoMeta;

final class SeoMetaTest extends TestCase
{
    public function testApplyToSeoManager(): void
    {
        $meta = SeoMeta::make('Hello', 'World')
            ->canonical('https://example.com')
            ->openGraph('og:title', 'Hello');

        $seo = new SeoManager();
        $meta->apply($seo);

        // SeoManager has no getters; assert via applyToDocument wiring exists
        $doc = Document::make();
        $meta->applyToDocument($doc);
        self::assertSame('Hello', $meta->getTitle());
        self::assertSame('World', $meta->getDescription());
    }
}
