<?php

declare(strict_types=1);

namespace Serixa\AwsS3;

use Serixa\AwsS3\Contracts\S3Client;
use Serixa\LocalStorage\Exceptions\StorageException;

final class FakeS3Client implements S3Client
{
    /** @var array<string, string> keyed bucket/key */
    private array $objects = [];

    /**
     * @param array<string, mixed> $options
     */
    /**
     * @param array<string, mixed> $options
     */
    public function putObject(string $bucket, string $key, string $body, array $options = []): void
    {
        $this->objects[$bucket . '/' . $key] = $body;
    }

    public function getObject(string $bucket, string $key): string
    {
        $id = $bucket . '/' . $key;
        if (! isset($this->objects[$id])) {
            throw StorageException::notFound($key);
        }

        return $this->objects[$id];
    }

    public function deleteObject(string $bucket, string $key): void
    {
        unset($this->objects[$bucket . '/' . $key]);
    }

    public function objectExists(string $bucket, string $key): bool
    {
        return isset($this->objects[$bucket . '/' . $key]);
    }
}
