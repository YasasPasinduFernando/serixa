<?php

declare(strict_types=1);

namespace Serixa\Algolia\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Algolia\AlgoliaEngine;
use Serixa\Algolia\FakeAlgoliaClient;

final class AlgoliaEngineTest extends TestCase
{
    public function testFakeRoundTrip(): void
    {
        $engine = AlgoliaEngine::make(new FakeAlgoliaClient());
        $engine->index('catalog', '42', ['title' => 'Algolia Adapter']);
        $result = $engine->search('catalog', 'adapter');
        self::assertSame(1, $result->count());
        self::assertSame('42', $result->hits[0]['objectID']);
        $engine->clear('catalog');
        self::assertTrue($engine->search('catalog', 'adapter')->isEmpty());
    }
}
