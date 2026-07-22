<?php

declare(strict_types=1);

namespace Serixa\Meilisearch\Contracts;

interface MeilisearchClient
{
    /**
     * @param list<array<string, mixed>> $documents
     */
    public function addDocuments(string $index, array $documents, string $primaryKey = 'id'): void;

    public function deleteDocument(string $index, string $id): void;

    /**
     * @param array<string, mixed> $options
     * @return array{hits: list<array<string, mixed>>, estimatedTotalHits: int}
     */
    public function search(string $index, string $query, array $options = []): array;

    public function deleteIndex(string $index): void;
}
