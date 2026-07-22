<?php

declare(strict_types=1);

namespace Serixa\AwsS3\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\AwsS3\FakeS3Client;
use Serixa\AwsS3\S3Storage;

final class S3StorageTest extends TestCase
{
    public function testFakeRoundTrip(): void
    {
        $storage = S3Storage::make(new FakeS3Client(), 'my-bucket', 'https://cdn.example', 'uploads');
        $storage->put('a.txt', 'data');
        self::assertTrue($storage->exists('a.txt'));
        self::assertSame('data', $storage->get('a.txt'));
        self::assertSame('https://cdn.example/uploads/a.txt', $storage->url('a.txt'));
    }
}
