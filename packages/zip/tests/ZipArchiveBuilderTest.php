<?php

declare(strict_types=1);

namespace Serixa\Zip\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Zip\ZipArchiveBuilder;

final class ZipArchiveBuilderTest extends TestCase
{
    public function testCreateAndExtract(): void
    {
        if (! class_exists(\ZipArchive::class)) {
            self::markTestSkipped('zip extension missing');
        }
        $path = sys_get_temp_dir() . '/serixa-zip-' . bin2hex(random_bytes(4)) . '.zip';
        ZipArchiveBuilder::make()->addFromString('a.txt', 'hello')->save($path);
        $files = ZipArchiveBuilder::make()->extractToArray($path);
        self::assertSame('hello', $files['a.txt']);
        unlink($path);
    }
}
