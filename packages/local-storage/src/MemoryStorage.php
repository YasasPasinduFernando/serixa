<?php

declare(strict_types=1);

namespace Serixa\LocalStorage;

use Serixa\LocalStorage\Contracts\Storage;

/**
 * In-memory storage for tests.
 */
final class MemoryStorage implements Storage
{
    /** @var array<string, string> */
    private array $files = [];

    private string $publicBaseUrl;

    public function __construct(string $publicBaseUrl = 'memory://')
    {
        $this->publicBaseUrl = rtrim($publicBaseUrl, '/');
    }

    public function put(string $path, string $contents, array $options = []): void
    {
        $this->files[$this->key($path)] = $contents;
    }

    public function get(string $path): string
    {
        $key = $this->key($path);
        if (! isset($this->files[$key])) {
            throw Exceptions\StorageException::notFound($path);
        }

        return $this->files[$key];
    }

    public function delete(string $path): void
    {
        unset($this->files[$this->key($path)]);
    }

    public function exists(string $path): bool
    {
        return isset($this->files[$this->key($path)]);
    }

    public function url(string $path): string
    {
        return $this->publicBaseUrl . '/' . $this->key($path);
    }

    private function key(string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        if ($path === '' || str_contains($path, '..')) {
            throw Exceptions\StorageException::invalidPath($path);
        }

        return $path;
    }
}
