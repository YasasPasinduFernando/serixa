<?php

declare(strict_types=1);

namespace Serixa\Dropbox;

use Serixa\Dropbox\Contracts\DropboxClient;
use Serixa\LocalStorage\Contracts\Storage;
use Serixa\LocalStorage\Exceptions\StorageException;

final class DropboxStorage implements Storage
{
    public function __construct(
        private readonly DropboxClient $client,
        private readonly string $root = '',
    ) {
    }

    public static function make(DropboxClient $client, string $root = ''): self
    {
        return new self($client, '/' . trim($root, '/'));
    }

    /**
     * @param array<string, mixed> $options
     */
    /**
     * @param array<string, mixed> $options
     */
    public function put(string $path, string $contents, array $options = []): void
    {
        $this->client->upload($this->dropboxPath($path), $contents, $options);
    }

    public function get(string $path): string
    {
        return $this->client->download($this->dropboxPath($path));
    }

    public function delete(string $path): void
    {
        $this->client->delete($this->dropboxPath($path));
    }

    public function exists(string $path): bool
    {
        return $this->client->exists($this->dropboxPath($path));
    }

    public function url(string $path): string
    {
        return $this->client->temporaryLink($this->dropboxPath($path));
    }

    private function dropboxPath(string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        if ($path === '' || str_contains($path, '..')) {
            throw StorageException::invalidPath($path);
        }
        $root = rtrim($this->root, '/');
        if ($root === '' || $root === '/') {
            return '/' . $path;
        }

        return $root . '/' . $path;
    }
}
