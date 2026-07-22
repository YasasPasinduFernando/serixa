<?php

declare(strict_types=1);

namespace Serixa\GoogleDrive;

use Serixa\GoogleDrive\Contracts\GoogleDriveClient;
use Serixa\LocalStorage\Exceptions\StorageException;

final class FakeGoogleDriveClient implements GoogleDriveClient
{
    /** @var array<string, string> */
    private array $files = [];

    /** @var array<string, string> */
    private array $ids = [];

    /**
     * @param array<string, mixed> $options
     */
    /**
     * @param array<string, mixed> $options
     */
    public function upload(string $path, string $contents, array $options = []): string
    {
        $this->files[$path] = $contents;
        $id = 'gdrive_' . md5($path);
        $this->ids[$path] = $id;

        return $id;
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
        unset($this->files[$path], $this->ids[$path]);
    }

    public function exists(string $path): bool
    {
        return isset($this->files[$path]);
    }

    public function fileId(string $path): ?string
    {
        return $this->ids[$path] ?? null;
    }
}
