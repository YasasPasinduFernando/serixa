<?php

declare(strict_types=1);

namespace Serixa\MediaLibrary;

use Serixa\LocalStorage\Contracts\Storage;
use Serixa\MediaLibrary\Contracts\MediaStore;
use Serixa\MediaLibrary\Exceptions\MediaException;
use Serixa\MediaLibrary\Memory\MemoryMediaStore;

final class MediaLibrary
{
    private int $seq = 0;

    public function __construct(
        private readonly Storage $storage,
        private readonly MediaStore $catalog,
        private readonly string $directory = 'media',
    ) {
    }

    public static function memory(Storage $storage): self
    {
        return new self($storage, new MemoryMediaStore());
    }

    public function storage(): Storage
    {
        return $this->storage;
    }

    public function catalog(): MediaStore
    {
        return $this->catalog;
    }

    public function add(
        string $name,
        string $contents,
        string $mimeType = 'application/octet-stream',
        ?string $id = null,
    ): MediaItem {
        $id ??= $this->nextId();
        $path = trim($this->directory, '/') . '/' . $id . '_' . $this->safeName($name);
        $this->storage->put($path, $contents);
        $item = MediaItem::make($id, $path, $name, $mimeType, strlen($contents));
        $this->catalog->put($item);

        return $item;
    }

    public function get(string $id): MediaItem
    {
        $item = $this->catalog->get($id);
        if ($item === null) {
            throw MediaException::notFound($id);
        }

        return $item;
    }

    public function contents(string $id): string
    {
        return $this->storage->get($this->get($id)->path());
    }

    public function url(string $id): string
    {
        return $this->storage->url($this->get($id)->path());
    }

    public function delete(string $id): void
    {
        $item = $this->get($id);
        $this->storage->delete($item->path());
        $this->catalog->remove($id);
    }

    private function nextId(): string
    {
        ++$this->seq;

        return 'media_' . $this->seq;
    }

    private function safeName(string $name): string
    {
        return preg_replace('/[^A-Za-z0-9._-]+/', '_', $name) ?? 'file';
    }
}
