<?php

declare(strict_types=1);

namespace Serixa\LocalStorage;

use Serixa\LocalStorage\Contracts\Storage;
use Serixa\LocalStorage\Exceptions\StorageException;

/**
 * Local disk storage rooted at a directory.
 */
final class LocalStorage implements Storage
{
    public function __construct(
        private readonly string $root,
        private readonly string $publicBaseUrl = '',
    ) {
        if ($root === '') {
            throw StorageException::invalidPath($root);
        }
        if (! is_dir($this->root) && ! mkdir($this->root, 0777, true) && ! is_dir($this->root)) {
            throw StorageException::writeFailed($this->root);
        }
    }

    public static function make(string $root, string $publicBaseUrl = ''): self
    {
        return new self($root, rtrim($publicBaseUrl, '/'));
    }

    public function put(string $path, string $contents, array $options = []): void
    {
        $full = $this->fullPath($path);
        $dir = dirname($full);
        if (! is_dir($dir) && ! mkdir($dir, 0777, true) && ! is_dir($dir)) {
            throw StorageException::writeFailed($path);
        }
        if (file_put_contents($full, $contents) === false) {
            throw StorageException::writeFailed($path);
        }
    }

    public function get(string $path): string
    {
        $full = $this->fullPath($path);
        if (! is_file($full)) {
            throw StorageException::notFound($path);
        }
        $contents = file_get_contents($full);
        if ($contents === false) {
            throw StorageException::notFound($path);
        }

        return $contents;
    }

    public function delete(string $path): void
    {
        $full = $this->fullPath($path);
        if (is_file($full)) {
            unlink($full);
        }
    }

    public function exists(string $path): bool
    {
        return is_file($this->fullPath($path));
    }

    public function url(string $path): string
    {
        $normalized = $this->normalize($path);
        if ($this->publicBaseUrl === '') {
            return $normalized;
        }

        return $this->publicBaseUrl . '/' . $normalized;
    }

    public function root(): string
    {
        return $this->root;
    }

    private function fullPath(string $path): string
    {
        return $this->root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $this->normalize($path));
    }

    private function normalize(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');
        if ($path === '' || str_contains($path, '..')) {
            throw StorageException::invalidPath($path);
        }

        return $path;
    }
}
