<?php

declare(strict_types=1);

namespace Serixa\GoogleDrive\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\GoogleDrive\FakeGoogleDriveClient;
use Serixa\GoogleDrive\GoogleDriveStorage;

final class GoogleDriveStorageTest extends TestCase
{
    public function testRoundTrip(): void
    {
        $storage = GoogleDriveStorage::make(new FakeGoogleDriveClient());
        $storage->put('notes.txt', 'hello');
        self::assertSame('hello', $storage->get('notes.txt'));
        self::assertStringContainsString('drive.google.com', $storage->url('notes.txt'));
    }
}
