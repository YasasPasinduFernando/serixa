<?php

declare(strict_types=1);

namespace Serixa\LocalStorage\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\LocalStorage\LocalStorage;
use Serixa\LocalStorage\MemoryStorage;

final class LocalStorageTest extends TestCase
{
    public function testMemoryRoundTrip(): void
    {
        $storage = new MemoryStorage('https://cdn.test');
        $storage->put('a/b.txt', 'hello');
        self::assertTrue($storage->exists('a/b.txt'));
        self::assertSame('hello', $storage->get('a/b.txt'));
        self::assertSame('https://cdn.test/a/b.txt', $storage->url('a/b.txt'));
        $storage->delete('a/b.txt');
        self::assertFalse($storage->exists('a/b.txt'));
    }

    public function testDiskRoundTrip(): void
    {
        $root = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'serixa-local-' . bin2hex(random_bytes(4));
        $storage = LocalStorage::make($root, 'https://assets.test');
        $storage->put('docs/readme.txt', 'SERIXA');
        self::assertSame('SERIXA', $storage->get('docs/readme.txt'));
        self::assertSame('https://assets.test/docs/readme.txt', $storage->url('docs/readme.txt'));
        $storage->delete('docs/readme.txt');
        self::assertFalse($storage->exists('docs/readme.txt'));
    }
}
