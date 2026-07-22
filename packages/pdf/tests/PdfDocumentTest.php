<?php

declare(strict_types=1);

namespace Serixa\Pdf\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Pdf\PdfDocument;

final class PdfDocumentTest extends TestCase
{
    public function testBuildsPdf(): void
    {
        $pdf = PdfDocument::make()->title('Hello')->line('SERIXA')->toString();
        self::assertStringStartsWith('%PDF-1.4', $pdf);
        self::assertStringContainsString('SERIXA', $pdf);
        self::assertStringContainsString('%%EOF', $pdf);
    }
}
