<?php

declare(strict_types=1);

namespace Serixa\AwsS3;

use Serixa\AwsS3\Contracts\S3Client;
use Serixa\LocalStorage\Contracts\Storage;
use Serixa\LocalStorage\Exceptions\StorageException;

final class S3Storage implements Storage
{
    public function __construct(
        private readonly S3Client $client,
        private readonly string $bucket,
        private readonly string $publicBaseUrl = '',
        private readonly string $prefix = '',
    ) {
        if ($bucket === '') {
            throw StorageException::invalidPath('bucket');
        }
    }

    public static function make(S3Client $client, string $bucket, string $publicBaseUrl = '', string $prefix = ''): self
    {
        return new self($client, $bucket, rtrim($publicBaseUrl, '/'), trim($prefix, '/'));
    }

    /**
     * @param array<string, mixed> $options
     */
    /**
     * @param array<string, mixed> $options
     */
    public function put(string $path, string $contents, array $options = []): void
    {
        $this->client->putObject($this->bucket, $this->key($path), $contents, $options);
    }

    public function get(string $path): string
    {
        return $this->client->getObject($this->bucket, $this->key($path));
    }

    public function delete(string $path): void
    {
        $this->client->deleteObject($this->bucket, $this->key($path));
    }

    public function exists(string $path): bool
    {
        return $this->client->objectExists($this->bucket, $this->key($path));
    }

    public function url(string $path): string
    {
        $key = $this->key($path);
        if ($this->publicBaseUrl !== '') {
            return $this->publicBaseUrl . '/' . $key;
        }

        return sprintf('https://%s.s3.amazonaws.com/%s', $this->bucket, $key);
    }

    private function key(string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        if ($path === '' || str_contains($path, '..')) {
            throw StorageException::invalidPath($path);
        }

        return $this->prefix === '' ? $path : $this->prefix . '/' . $path;
    }
}
