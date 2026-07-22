<?php

declare(strict_types=1);

namespace Serixa\Algolia\Contracts;

interface AlgoliaClient
{
    /**
     * @param array<string, mixed> $object
     */
    public function saveObject(string $index, array $object): void;

    public function deleteObject(string $index, string $objectId): void;

    /**
     * @param array<string, mixed> $options
     * @return array{hits: list<array<string, mixed>>, nbHits: int}
     */
    public function search(string $index, string $query, array $options = []): array;

    public function clearIndex(string $index): void;
}
