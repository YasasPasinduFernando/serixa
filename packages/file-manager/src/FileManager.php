<?php

declare(strict_types=1);

namespace Serixa\FileManager;

use Serixa\LocalStorage\Contracts\Storage;
use Serixa\LocalStorage\Exceptions\StorageException;
use Serixa\MediaLibrary\MediaLibrary;

/**
 * Browse/manage files through Storage, optionally listing MediaLibrary catalog.
 */
final class FileManager
{
    /** @var array<string, true> known paths for memory-backed listing */
    private array $index = [];

    public function __construct(
        private readonly Storage $storage,
        private readonly ?MediaLibrary $media = null,
    ) {
    }

    public static function make(Storage $storage, ?MediaLibrary $media = null): self
    {
        return new self($storage, $media);
    }

    public function write(string $path, string $contents): void
    {
        $this->storage->put($path, $contents);
        $this->index[$this->normalize($path)] = true;
    }

    public function read(string $path): string
    {
        return $this->storage->get($path);
    }

    public function delete(string $path): void
    {
        $this->storage->delete($path);
        unset($this->index[$this->normalize($path)]);
    }

    public function exists(string $path): bool
    {
        return $this->storage->exists($path);
    }

    /**
     * @return list<FileEntry>
     */
    public function list(string $prefix = ''): array
    {
        $prefix = trim(str_replace('\\', '/', $prefix), '/');
        $paths = array_keys($this->index);

        if ($this->media !== null) {
            foreach ($this->media->catalog()->all() as $item) {
                $paths[] = $item->path();
            }
        }

        $paths = array_values(array_unique($paths));
        sort($paths);

        $entries = [];
        $dirs = [];
        foreach ($paths as $path) {
            if ($prefix !== '' && ! str_starts_with($path, $prefix . '/') && $path !== $prefix) {
                continue;
            }
            $relative = $prefix === '' ? $path : ltrim(substr($path, strlen($prefix)), '/');
            if ($relative === '') {
                continue;
            }
            $parts = explode('/', $relative);
            if (count($parts) > 1) {
                $dirName = $parts[0];
                $dirPath = $prefix === '' ? $dirName : $prefix . '/' . $dirName;
                if (! isset($dirs[$dirPath])) {
                    $dirs[$dirPath] = true;
                    $entries[] = new FileEntry($dirPath, $dirName, true);
                }
                continue;
            }
            $size = 0;
            try {
                $size = strlen($this->storage->get($path));
            } catch (StorageException) {
                // ignore missing
            }
            $entries[] = new FileEntry($path, $parts[0], false, $size);
        }

        return $entries;
    }

    private function normalize(string $path): string
    {
        return ltrim(str_replace('\\', '/', $path), '/');
    }
}
