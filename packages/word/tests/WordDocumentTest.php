<?php

declare(strict_types=1);

namespace Serixa\Word\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Word\WordDocument;

final class WordDocumentTest extends TestCase
{
    public function testRtf(): void
    {
        $rtf = WordDocument::make()->title('T')->paragraph('Hello Word')->toRtf();
        self::assertStringStartsWith('{\\rtf1', $rtf);
        self::assertStringContainsString('Hello Word', $rtf);
    }
}
