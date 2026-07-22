<?php

declare(strict_types=1);

namespace Serixa\Elasticsearch\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Elasticsearch\ElasticsearchEngine;
use Serixa\Elasticsearch\FakeElasticsearchClient;

final class ElasticsearchEngineTest extends TestCase
{
    public function testFakeRoundTrip(): void
    {
        $engine = ElasticsearchEngine::make(new FakeElasticsearchClient());
        $engine->index('logs', 'e1', ['message' => 'Elasticsearch adapter']);
        $result = $engine->search('logs', 'adapter');
        self::assertSame(1, $result->count());
        self::assertSame('e1', $result->hits[0]['id']);
        $engine->clear('logs');
        self::assertTrue($engine->search('logs', 'adapter')->isEmpty());
    }
}
