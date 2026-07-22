<?php

declare(strict_types=1);

namespace Serixa\Dropbox;

use Serixa\Dropbox\Contracts\DropboxClient;
use Serixa\LocalStorage\Exceptions\StorageException;

final class FakeDropboxClient implements DropboxClient
{
    /** @var array<string, string> */
    private array $files = [];

    /**
     * @param array<string, mixed> $options
     */
    /**
     * @param array<string, mixed> $options
     */
    public function upload(string $path, string $contents, array $options = []): void
    {
        $this->files[$path] = $contents;
    }

    public function download(string $path): string
    {
        if (! isset($this->files[$path])) {
            throw StorageException::notFound($path);
        }

        return $this->files[$path];
    }

    public function delete(string $path): void
    {
        unset($this->files[$path]);
    }

    public function exists(string $path): bool
    {
        return isset($this->files[$path]);
    }

    public function temporaryLink(string $path): string
    {
        if (! $this->exists($path)) {
            throw StorageException::notFound($path);
        }

        return 'https://dl.dropboxusercontent.com/fake' . $path;
    }
}
