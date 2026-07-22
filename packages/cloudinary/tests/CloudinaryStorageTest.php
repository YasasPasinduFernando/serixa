<?php

declare(strict_types=1);

namespace Serixa\Cloudinary\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Cloudinary\CloudinaryStorage;
use Serixa\Cloudinary\FakeCloudinaryClient;

final class CloudinaryStorageTest extends TestCase
{
    public function testUploadAndUrl(): void
    {
        $storage = CloudinaryStorage::make(new FakeCloudinaryClient(), 'media');
        $storage->put('logo.png', 'PNGDATA');
        self::assertSame('PNGDATA', $storage->get('logo.png'));
        self::assertStringContainsString('media/logo.png', $storage->url('logo.png'));
    }
}
