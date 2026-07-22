<?php

declare(strict_types=1);

namespace Serixa\Cloudinary;

use Serixa\Cloudinary\Contracts\CloudinaryClient;
use Serixa\LocalStorage\Contracts\Storage;
use Serixa\LocalStorage\Exceptions\StorageException;

final class CloudinaryStorage implements Storage
{
    /** @var array<string, string> publicId => delivery url */
    private array $urls = [];

    public function __construct(
        private readonly CloudinaryClient $client,
        private readonly string $folder = '',
    ) {
    }

    public static function make(CloudinaryClient $client, string $folder = ''): self
    {
        return new self($client, trim($folder, '/'));
    }

    /**
     * @param array<string, mixed> $options
     */
    /**
     * @param array<string, mixed> $options
     */
    public function put(string $path, string $contents, array $options = []): void
    {
        $id = $this->publicId($path);
        $this->urls[$id] = $this->client->upload($id, $contents, $options);
    }

    public function get(string $path): string
    {
        return $this->client->download($this->publicId($path));
    }

    public function delete(string $path): void
    {
        $id = $this->publicId($path);
        $this->client->destroy($id);
        unset($this->urls[$id]);
    }

    public function exists(string $path): bool
    {
        return $this->client->exists($this->publicId($path));
    }

    public function url(string $path): string
    {
        $id = $this->publicId($path);
        if (isset($this->urls[$id])) {
            return $this->urls[$id];
        }

        return 'https://res.cloudinary.com/demo/raw/upload/' . $id;
    }

    private function publicId(string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        if ($path === '' || str_contains($path, '..')) {
            throw StorageException::invalidPath($path);
        }

        return $this->folder === '' ? $path : $this->folder . '/' . $path;
    }
}
