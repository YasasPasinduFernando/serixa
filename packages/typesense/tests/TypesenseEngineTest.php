<?php

declare(strict_types=1);

namespace Serixa\Typesense\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Typesense\FakeTypesenseClient;
use Serixa\Typesense\TypesenseEngine;

final class TypesenseEngineTest extends TestCase
{
    public function testFakeRoundTrip(): void
    {
        $engine = TypesenseEngine::make(new FakeTypesenseClient());
        $engine->index('books', 'b1', ['title' => 'Typesense Guide']);
        $result = $engine->search('books', 'guide');
        self::assertSame(1, $result->count());
        self::assertSame('b1', $result->hits[0]['id']);
        $engine->delete('books', 'b1');
        self::assertTrue($engine->search('books', 'guide')->isEmpty());
    }
}
