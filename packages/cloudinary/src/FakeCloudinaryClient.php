<?php

declare(strict_types=1);

namespace Serixa\Cloudinary;

use Serixa\Cloudinary\Contracts\CloudinaryClient;
use Serixa\LocalStorage\Exceptions\StorageException;

final class FakeCloudinaryClient implements CloudinaryClient
{
    /** @var array<string, string> */
    private array $assets = [];

    /**
     * @param array<string, mixed> $options
     */
    /**
     * @param array<string, mixed> $options
     */
    public function upload(string $publicId, string $contents, array $options = []): string
    {
        $this->assets[$publicId] = $contents;

        return 'https://res.cloudinary.com/demo/raw/upload/' . $publicId;
    }

    public function download(string $publicId): string
    {
        if (! isset($this->assets[$publicId])) {
            throw StorageException::notFound($publicId);
        }

        return $this->assets[$publicId];
    }

    public function destroy(string $publicId): void
    {
        unset($this->assets[$publicId]);
    }

    public function exists(string $publicId): bool
    {
        return isset($this->assets[$publicId]);
    }
}
