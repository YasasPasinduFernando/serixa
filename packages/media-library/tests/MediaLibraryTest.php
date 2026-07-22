<?php

declare(strict_types=1);

namespace Serixa\MediaLibrary\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\LocalStorage\MemoryStorage;
use Serixa\MediaLibrary\MediaLibrary;

final class MediaLibraryTest extends TestCase
{
    public function testAddAndRead(): void
    {
        $lib = MediaLibrary::memory(new MemoryStorage('https://cdn.test'));
        $item = $lib->add('photo.jpg', 'JPEG', 'image/jpeg');
        self::assertSame('JPEG', $lib->contents($item->id()));
        self::assertStringContainsString('media/', $lib->url($item->id()));
        $lib->delete($item->id());
        self::assertCount(0, $lib->catalog()->all());
    }
}
