<?php

declare(strict_types=1);

namespace Serixa\FileManager\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\FileManager\FileManager;
use Serixa\LocalStorage\MemoryStorage;
use Serixa\MediaLibrary\MediaLibrary;

final class FileManagerTest extends TestCase
{
    public function testListAndRead(): void
    {
        $storage = new MemoryStorage();
        $media = MediaLibrary::memory($storage);
        $media->add('logo.png', 'PNG', 'image/png');
        $fm = FileManager::make($storage, $media);
        $fm->write('docs/a.txt', 'A');

        $root = $fm->list();
        self::assertNotEmpty($root);
        self::assertSame('A', $fm->read('docs/a.txt'));
    }
}
