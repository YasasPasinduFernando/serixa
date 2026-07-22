<?php

declare(strict_types=1);

namespace Serixa\Meilisearch\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Meilisearch\FakeMeilisearchClient;
use Serixa\Meilisearch\MeilisearchEngine;
use Serixa\Meilisearch\MemorySearchEngine;

final class MeilisearchEngineTest extends TestCase
{
    public function testMemorySearch(): void
    {
        $engine = new MemorySearchEngine();
        $engine->index('products', '1', ['title' => 'Blue Widget', 'sku' => 'W-1']);
        $engine->index('products', '2', ['title' => 'Red Gadget', 'sku' => 'G-2']);

        $result = $engine->search('products', 'widget');
        self::assertSame(1, $result->count());
        self::assertSame('1', $result->hits[0]['id']);
    }

    public function testFakeClientRoundTrip(): void
    {
        $engine = MeilisearchEngine::make(new FakeMeilisearchClient());
        $engine->index('posts', 'a', ['title' => 'Hello Serixa']);
        $result = $engine->search('posts', 'serixa');
        self::assertFalse($result->isEmpty());
        $engine->delete('posts', 'a');
        self::assertTrue($engine->search('posts', 'serixa')->isEmpty());
    }
}
