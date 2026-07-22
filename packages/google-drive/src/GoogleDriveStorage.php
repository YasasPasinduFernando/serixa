<?php

declare(strict_types=1);

namespace Serixa\GoogleDrive;

use Serixa\GoogleDrive\Contracts\GoogleDriveClient;
use Serixa\LocalStorage\Contracts\Storage;
use Serixa\LocalStorage\Exceptions\StorageException;

final class GoogleDriveStorage implements Storage
{
    /** @var array<string, string> */
    private array $fileIds = [];

    public function __construct(private readonly GoogleDriveClient $client)
    {
    }

    public static function make(GoogleDriveClient $client): self
    {
        return new self($client);
    }

    /**
     * @param array<string, mixed> $options
     */
    /**
     * @param array<string, mixed> $options
     */
    public function put(string $path, string $contents, array $options = []): void
    {
        $path = $this->normalize($path);
        $this->fileIds[$path] = $this->client->upload($path, $contents, $options);
    }

    public function get(string $path): string
    {
        return $this->client->download($this->normalize($path));
    }

    public function delete(string $path): void
    {
        $path = $this->normalize($path);
        $this->client->delete($path);
        unset($this->fileIds[$path]);
    }

    public function exists(string $path): bool
    {
        return $this->client->exists($this->normalize($path));
    }

    public function url(string $path): string
    {
        $path = $this->normalize($path);
        $id = $this->fileIds[$path] ?? 'unknown';

        return 'https://drive.google.com/file/d/' . $id . '/view';
    }

    private function normalize(string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        if ($path === '' || str_contains($path, '..')) {
            throw StorageException::invalidPath($path);
        }

        return $path;
    }
}
