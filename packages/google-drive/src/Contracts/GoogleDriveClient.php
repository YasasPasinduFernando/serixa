<?php

declare(strict_types=1);

namespace Serixa\GoogleDrive\Contracts;

interface GoogleDriveClient
{
    /**
     * @param array<string, mixed> $options
     */
    /**
     * @param array<string, mixed> $options
     */
    public function upload(string $path, string $contents, array $options = []): string;

    public function download(string $path): string;

    public function delete(string $path): void;

    public function exists(string $path): bool;
}
