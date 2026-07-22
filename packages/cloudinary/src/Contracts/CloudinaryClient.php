<?php

declare(strict_types=1);

namespace Serixa\Cloudinary\Contracts;

interface CloudinaryClient
{
    /**
     * @param array<string, mixed> $options
     */
    /**
     * @param array<string, mixed> $options
     */
    public function upload(string $publicId, string $contents, array $options = []): string;

    public function download(string $publicId): string;

    public function destroy(string $publicId): void;

    public function exists(string $publicId): bool;
}
