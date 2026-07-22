<?php

declare(strict_types=1);

namespace Serixa\Dropbox\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Dropbox\DropboxStorage;
use Serixa\Dropbox\FakeDropboxClient;

final class DropboxStorageTest extends TestCase
{
    public function testRoundTrip(): void
    {
        $storage = DropboxStorage::make(new FakeDropboxClient(), 'apps/serixa');
        $storage->put('file.txt', 'dbx');
        self::assertSame('dbx', $storage->get('file.txt'));
        self::assertStringContainsString('dropboxusercontent', $storage->url('file.txt'));
    }
}
