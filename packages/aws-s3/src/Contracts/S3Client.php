<?php

declare(strict_types=1);

namespace Serixa\AwsS3\Contracts;

interface S3Client
{
    /**
     * @param array<string, mixed> $options
     */
    /**
     * @param array<string, mixed> $options
     */
    public function putObject(string $bucket, string $key, string $body, array $options = []): void;

    public function getObject(string $bucket, string $key): string;

    public function deleteObject(string $bucket, string $key): void;

    public function objectExists(string $bucket, string $key): bool;
}
